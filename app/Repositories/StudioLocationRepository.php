<?php

namespace App\Repositories;

use App\Models\Interfaces\StudioLocationInterface;
use App\Repositories\Interfaces\StudioLocationRepositoryInterface;


/**
 * Class StudioLocationRepository
 *
 */
class StudioLocationRepository implements StudioLocationRepositoryInterface
{
    private $model;

    public function __construct(StudioLocationInterface $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model::all();
    }

    public function create(array $data)
    {
        $model = $this->model::create($data);

        return $model;
    }

    public function find($id)
    {
        return $this->model::where('id', $id)->first();
    }

    public function findBy($attribute, $value)
    {
        return $this->model->where($attribute, '=', $value)->get();
    }

    public function update($id, array $data)
    {
        $this->model::where('id', $id)->update($data);
    }

    public function delete($id)
    {
        $this->model::where('id', $id)->delete();
    }
    public function updateByStudioId($id, array $data)
    {
        $this->model::where('studio_id', $id)->update($data);
    }

}
