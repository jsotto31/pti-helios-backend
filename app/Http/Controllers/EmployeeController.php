<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function __invoke()
    {
        return User::where("type", 'employee')->get();
    }
}
