<?php

namespace App\Repositories;

use App\Models\Interfaces\StudioInterface;
use App\Models\Studio;
use App\Repositories\Interfaces\StudioRepositoryInterface;

/**
 * Class StudioRepository
 *
 */
class StudioRepository implements StudioRepositoryInterface
{
    private $model;

    public function __construct(StudioInterface $model)
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
        return $this->model->where($attribute, '=', $value)->first();
    }

    public function update($id, array $data)
    {
        $this->model::where('id', $id)->update($data);
    }

    public function delete($id)
    {
        $this->model::where('id', $id)->delete();
    }

}
