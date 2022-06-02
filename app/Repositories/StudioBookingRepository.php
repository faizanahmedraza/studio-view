<?php

namespace App\Repositories;

use App\Models\Interfaces\StudioBookingInterface;
use App\Repositories\Interfaces\StudioBookingRepositoryInterface;


/**
 * Class StudioBookingRepository
 *
 */
class StudioBookingRepository implements StudioBookingRepositoryInterface
{
    private $model;

    public function __construct(StudioBookingInterface $model)
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
        return $this->model->where($attribute, '=', $value);
    }

    public function update($id, array $data)
    {
        $this->model::where('id', $id)->update($data);
    }

    public function delete($id)
    {
        $this->model::where('id', $id)->delete();
    }

    public function initiateQuery()
    {
        return $this->model::query();
    }

    public function getIn($attr,$where)
    {
        return $this->model->whereIn($attr,$where)->get();
    }
    public function where($where)
    {
        return $this->model->where($where)->get();
    }
}
