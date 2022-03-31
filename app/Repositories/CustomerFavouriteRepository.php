<?php

namespace App\Repositories;

use App\Models\Interfaces\CustomerFavouriteInterface;
use App\Repositories\Interfaces\CustomerFavouriteRepositoryInterface;

/**
 * Class CustomerFavouriteRepository
 */
class CustomerFavouriteRepository implements CustomerFavouriteRepositoryInterface
{
    private $model;

    public function __construct(CustomerFavouriteInterface $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        //
    }

    public function getFavouriteStudioIds()
    {
        $model = $this->model::where('user_id',auth()->id())->pluck('studio_id')->toArray();
        return $model;
    }

    public function create(array $data)
    {
        $model = $this->model::updateOrCreate($data);

        return $model;
    }

    public function find($id){
     //
    }

    public function update($id, array $data)
    {
        //
    }

    public function delete($studio_id)
    {
        $this->model::where('user_id', auth()->id())->where('studio_id', $studio_id)->delete();
    }
}
