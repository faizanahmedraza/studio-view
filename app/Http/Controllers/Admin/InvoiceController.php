<?php

namespace App\Http\Controllers\Admin;


use DateTime;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;



class InvoiceController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth:admin');
        parent::__construct();

    }

    /**
     * get app verified user list
     */
    public function index()
    {
        return view('admin.invoice.index');
    }

    /**
     * yajra call after user list
     */
    public function invoiceList(DataTables $datatables, Request $request): JsonResponse
    {
        $query = Invoice::where('status', 1);

        return $datatables->eloquent($query)
            ->setRowId(static function ($record) {
                return $record->id;
            })
            ->addColumn('studio_owner', static function ($record) {
                $user=$record->studioOwner;
                return view('admin.invoice.action', compact('user'));
            })
            ->addColumn('request_user', static function ($record) {
                $user=$record->requestUser;
                return view('admin.invoice.action', compact('user'));
            })
            ->addColumn('studio', static function ($record) {
                $studio=$record->studioBooking->studio;
                return view('admin.invoice.action', compact('studio'));
            })
            // ->editColumn('status', static function ($record) {
            //     return view('admin.studio.status', compact('record'));
            // })
            ->addColumn('action', static function ($record) {
                $studioBooking=$record->studioBooking;
                return view('admin.invoice.action', compact('studioBooking'));
            })
            ->editColumn('created_at', static function ($record) {
                $date = DateTime::createFromFormat('Y-m-d H:i:s', $record->created_at);
                return $date->format('d M Y H:i a');
            })
            ->make(true);
    }



}
