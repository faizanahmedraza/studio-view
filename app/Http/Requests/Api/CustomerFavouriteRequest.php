<?php

namespace App\Http\Requests\Api;

use App\Repositories\Interfaces\StudioRepositoryInterface;

class CustomerFavouriteRequest extends Request
{
    private $studioRepository;

    public function __construct(StudioRepositoryInterface $studioRepository)
    {
        $this->studioRepository = $studioRepository;
    }
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // description
            'studio_id'=>'required|in:'.implode(',',$this->studioRepository->initiateQuery()->pluck('id')->toArray()),
        ];
    }

}
