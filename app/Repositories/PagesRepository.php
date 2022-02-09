<?php

namespace App\Repositories;

use App\Models\Interfaces\PagesInterface;
use App\Repositories\Interfaces\PagesRepositoryInterface;

/**
 * Class UserRepository
 *
 */
class PagesRepository implements PagesRepositoryInterface
{
    private $model;

    public function __construct(PagesInterface $model)
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
        // dd($id);
        $this->model::where('id', $id)->update($data);
    }

    public function delete($id)
    {
        $this->model::where('id', $id)->delete();
    }

    /**
     * Return's page data with media file
     * @param  array $where
     * @return Illuminate\Database\Query\Builder
     */
    public function getPage($slug)
    {
        return $this->model::where('slug', $slug)->first();
    }
}
