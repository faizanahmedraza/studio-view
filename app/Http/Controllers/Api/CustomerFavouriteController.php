<?php

namespace App\Http\Controllers\Api;

use App\Classes\RestAPI;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CustomerFavouriteRequest;
use App\Http\Resources\StudioListResource;
use App\Repositories\Interfaces\CustomerFavouriteRepositoryInterface;
use App\Repositories\Interfaces\StudioRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\DB;
use stdClass;

class CustomerFavouriteController extends Controller
{
    private $userRepository;
    private $studioRepository;
    private $customerFavouriteRepository;

    public function __construct(UserRepositoryInterface $userRepository, StudioRepositoryInterface $studioRepository, CustomerFavouriteRepositoryInterface $customerFavouriteRepository)
    {
        $this->userRepository = $userRepository;
        $this->studioRepository = $studioRepository;
        $this->customerFavouriteRepository = $customerFavouriteRepository;
    }

    public function index()
    {
        try {
            $favStudioIds = $this->customerFavouriteRepository->getFavouriteStudioIds();
            $allStudios = $this->studioRepository->getFavouriteStudios($favStudioIds);
            $response = StudioListResource::collection($allStudios);
        } catch (\Exception $e) {
            return RestAPI::response($e->getMessage(), false, 'error_exception');
        }
        return RestAPI::response($response, true, 'Studios List');
    }

    public function store(CustomerFavouriteRequest $request)
    {
        $data = $request->all();
        DB::beginTransaction();
        try {
            $exist = $this->studioRepository->findByWhereArray(['id' => $data['studio_id'], 'status' => 0]);
            if ($exist) {
                return RestAPI::response('Studio is not active.', false, 'error_exception');
            }
            $customerData = [
                'user_id' => $this->userRepository->find(auth()->user()->id)->id,
                'studio_id' => $this->studioRepository->find($data['studio_id'])->id ?? null,
            ];

            $this->customerFavouriteRepository->create($customerData);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return RestAPI::response($e->getMessage(), false, 'error_exception');
        }
        return RestAPI::response(new stdClass(), true, 'Studio saved Successfully');

    }

    public function destroy(CustomerFavouriteRequest $request)
    {
        $data = $request->all();
        DB::beginTransaction();
        try {
            $this->customerFavouriteRepository->delete($this->studioRepository->find($data['studio_id'])->id);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return RestAPI::response($e->getMessage(), false, 'error_exception');
        }
        return RestAPI::response(new stdClass(), true, 'Studio has been removed from saved items Successfully');
    }
}
