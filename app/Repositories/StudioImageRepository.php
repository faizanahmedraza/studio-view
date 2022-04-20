<?php

namespace App\Repositories;

use App\Models\Interfaces\StudioImageInterface;
use App\Repositories\Interfaces\StudioImageRepositoryInterface;


/**
 * Class StudioImageRepository
 *
 */
class StudioImageRepository implements StudioImageRepositoryInterface
{
    private $model;

    public function __construct(StudioImageInterface $model)
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


    public function addImages(array $images,$studioId)
    {
        $data=[];
        foreach ($images as $image) {
            $data[]=[
                'studio_id'=>$studioId,
                'image_url'=>$image,
                'created_at'=>date('Y-m-d H:i:s'),
                'updated_at'=>date('Y-m-d H:i:s'),
            ];

        }
        if(count($data) > 0){
            $this->model::insert($data);
        }
    }
}
