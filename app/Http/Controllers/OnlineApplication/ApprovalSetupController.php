<?php

namespace App\Http\Controllers\OnlineApplication;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ApprovalSetupController extends Controller
{
    public function index(){
        return User::where("type", 'employee')->paginate();
    }
}
