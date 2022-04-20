<?php

namespace App\Repositories;

use App\Models\Interfaces\StudioTypeInterface;
use App\Repositories\Interfaces\StudioTypeRepositoryInterface;


/**
 * Class StudioRepository
 *
 */
class StudioTypeRepository implements StudioTypeRepositoryInterface
{
    private $model;

    public function __construct(StudioTypeInterface $model)
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

    public function addTypes(array $types,$studioId)
    {
        $data=[];
        foreach ($types as $type) {
            $data[]=[
                'studio_id'=>$studioId,
                'type_id'=>$type,
                'created_at'=>date('Y-m-d H:i:s'),
                'updated_at'=>date('Y-m-d H:i:s'),
            ];

        }
        if(count($data) > 0){
            $this->model::insert($data);
        }
    }
}
