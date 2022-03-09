<?php

namespace App\Repositories;

use App\Models\Interfaces\StudioInterface;
use App\Models\Studio;
use App\Repositories\Interfaces\StudioRepositoryInterface;
use App\Models\StudioType;
use App\Models\StudioLocation;
use App\Models\StudioPrice;
use App\Models\StudioImage;

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

    public function getUserActiveStudios($userId)
    {
        return $this->model->where('user_id', $userId)->where('status', 1)->orderBy('created_at')->get();
    }

    public function getUserPendingStudios($userId)
    {
        return $this->model->where('user_id', $userId)->where('status', 0)->orderBy('created_at')->get();
    }

}
