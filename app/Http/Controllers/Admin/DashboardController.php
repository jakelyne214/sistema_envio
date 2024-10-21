<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Http\Models\Auditoria;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('isadmin');
        $this->middleware('user.status');
        $this->middleware('user.permissions'); 
    }
    public function getDashboard(){
        return view('admin.home');
    }
    public function getAuditoria(){
        $auditoria = Auditoria::all();
        $users = User::all();
        $filter = ['users' => $users];
        $data['auditoria'] = $auditoria;
        return view('admin.auditoria.home', $data,$filter);
    }
}
