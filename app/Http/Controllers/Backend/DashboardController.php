<?php

namespace App\Http\Controllers\Backend;

class DashboardController extends BaseController
{
    /**
     * DashboardController constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(int $workspaceId)
    {
        return view($this->guard.'.dashboard.index');
    }

}
