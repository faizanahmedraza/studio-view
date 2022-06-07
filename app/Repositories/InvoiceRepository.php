<?php

namespace App\Repositories;

use App\Models\Interfaces\InvoiceInterface;
use App\Repositories\Interfaces\InvoiceRepositoryInterface;


/**
 * Class StudioRepository
 *
 */
class InvoiceRepository implements InvoiceRepositoryInterface
{
    private $model;

    public function __construct(InvoiceInterface $model)
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

    public function update($id, array $data)
    {
        $this->model::where('id', $id)->update($data);
    }

    public function delete($id)
    {
        $this->model::where('id', $id)->delete();
    }
}
