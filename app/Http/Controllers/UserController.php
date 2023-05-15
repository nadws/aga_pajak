<?php

namespace App\Http\Controllers;

use App\Models\Posisi;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Data User',
            'user' => User::with('posisi')->where('nonaktif', 'T')->get(),
            'posisi' => Posisi::all()
        ];
        return view('user.user', $data);
    }
}
