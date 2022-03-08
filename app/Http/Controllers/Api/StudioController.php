<?php

namespace App\Http\Controllers\Api;

use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\Interfaces\StudioRepositoryInterface;

use Illuminate\Http\Request;


class StudioController extends ApiBaseController
{
    private $userRepository;
    private $studioRepository;

    public function __construct(UserRepositoryInterface $userRepository,StudioRepositoryInterface $studioRepository)
    {
        $this->userRepository = $userRepository;
        $this->studioRepository = $studioRepository;
    }

    public function store(Request $request)
    {
        $data=$request->all();
        print_r($data);
    }




}
