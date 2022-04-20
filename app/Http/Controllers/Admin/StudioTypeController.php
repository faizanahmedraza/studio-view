<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\CreateStudioTypeRequest;
use App\Models\Type;
use App\Repositories\Interfaces\TypeRepositoryInterface;
use App\Services\CloudinaryService;
use DB;
use Validator;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class StudioTypeController extends Controller
{
    private $typeRepository;

    public function __construct(TypeRepositoryInterface $typeRepository)
    {
        $this->middleware('auth:admin');
        parent::__construct();
        $this->typeRepository = $typeRepository;
    }

    /**
     * get app verified user list
     */
    public function index()
    {
        return view('admin.studio-types.index');
    }

    public function create()
    {
        return view('admin.studio-types.create');
    }

    public function store(CreateStudioTypeRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->all();
            $studioData = [
                'name' => $data['type_name'] ?? null,
                'status' => false,
            ];
            if ($request->has('image') && !empty($request->image)) {
                $studioData['image_url'] = CloudinaryService::upload($request->file('image')->getRealPath())->secureUrl;
            } else {
                $studioData['image_url'] = env('DEFAULT_STUDIO_TYPE_IMAGE', 'https://res.cloudinary.com/saasfa/image/upload/v1650350502/service-625e59a64167b.jpg');
            }
            $this->typeRepository->create($studioData);
            DB::commit();
        } catch (\Exception $e) {
        }
        return back()->with('success', 'Studio type created Successfully!');
    }

    public function edit($id)
    {
        $id = base64_decode($id);
        $type = Type::where('id', $id)->firstOrFail();
        return view('admin.studio-types.edit', compact('type'));
    }

    public function update($id, CreateStudioTypeRequest $request)
    {
        $type = Type::where('id', $id)->firstOrFail();
        try {
            DB::beginTransaction();
            $data = $request->all();
            $studioData = [
                'name' => $data['type_name'] ?? null
            ];
            if ($request->has('image') && !empty($request->image)) {
                $studioData['image_url'] = CloudinaryService::upload($request->file('image')->getRealPath())->secureUrl;
                $type->delete();
            } else {
                $studioData['image_url'] = env('DEFAULT_STUDIO_TYPE_IMAGE', 'https://res.cloudinary.com/saasfa/image/upload/v1650350502/service-625e59a64167b.jpg');
            }
            $this->typeRepository->update($type->id, $studioData);
            DB::commit();
        } catch (\Exception $e) {
        }
        return back()->with('success', 'Studio type updated Successfully!');
    }

    /**
     * yajra call after user list
     */
    public function studiosList(DataTables $datatables, Request $request): JsonResponse
    {
        $query = Type::query();

        return $datatables->eloquent($query)
            ->setRowId(static function ($record) {
                return $record->id;
            })
            ->editColumn('status', static function ($record) {
                return view('admin.studio-types.status', compact('record'));
            })
            ->addColumn('action', static function ($record) {
                return view('admin.studio-types.action', compact('record'));
            })
            ->make(true);
    }


    public function toggleStatus(Type $type)
    {
        $type->status = $type->status ? false : true;
        $type->save();
        return redirect()->back()
            ->with('success', 'Status Changed Successfully!');
    }

}
