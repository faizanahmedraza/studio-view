<?php

namespace App\Http\Controllers\Admin;

use DB;
use stdClass;
use Validator;
use App\Models\Studio;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\UserRepositoryInterface;

class StudioController extends Controller
{
    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
        {
            $this->middleware('auth:admin');
            parent::__construct();
            $this->userRepository = $userRepository;
        }

    /**
     * get app verified user list
     */
    public function index()
    {
        return view('admin.studio.index');
    }

    /**
     * yajra call after user list
     */
    public function studiosList(DataTables $datatables, Request $request): JsonResponse
    {
        $query = Studio::where('status',1);

        return $datatables->eloquent($query)
            ->setRowId(static function ($record) {
                return $record->id;
            })
            // ->editColumn('fullname', function ($record) {
            //     return $record->name();
            // })
            ->editColumn('status', static function ($record) {
                return view('admin.studio.status', compact('record'));
            })
            ->addColumn('action', static function ($record) {
                return view('admin.studio.action', compact('record'));
            })
            ->make(true);
    }

    public function toggleStatus(Studio $studio)
    {
        $studio->status= $studio->status ? false : true ;
        $studio->save();
        return redirect()->back()
        ->with('success', 'Status Changed Successfully!');
    }

      /**
     * get app verified user list
     */
    public function indexPending()
    {
        return view('admin.studio.pending');
    }

    /**
     * yajra call after user list
     */
    public function studiosListPending(DataTables $datatables, Request $request): JsonResponse
    {
        $query = Studio::where('status',0);

        return $datatables->eloquent($query)
            ->setRowId(static function ($record) {
                return $record->id;
            })
            // ->editColumn('fullname', function ($record) {
            //     return $record->name();
            // })
            ->editColumn('status', static function ($record) {
                return view('admin.studio.status', compact('record'));
            })
            ->addColumn('action', static function ($record) {
                return view('admin.studio.action', compact('record'));
            })
            ->make(true);
    }




}
