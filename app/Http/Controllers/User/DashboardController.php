<?php

namespace App\Http\Controllers\User;

use App\Models\Checkouts;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $data['checkouts'] = Checkouts::with('Camp')->whereUserId(Auth::id())->get();
        return view('user.dashboard', $data);
    }
}
