<?php

namespace App\Http\Controllers;

use App\Models\Checkouts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function dashboard()
    {
        $data['checkouts'] = Checkouts::with('Camp')->whereUserId(Auth::id())->get();
        return view('user.dashboard', $data);
    }
}
