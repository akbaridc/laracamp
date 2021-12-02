<?php

namespace App\Http\Controllers\Admin;

use App\Models\Checkouts;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $data['checkouts'] = Checkouts::with(['camp', 'user'])->get();
        return view('admin.dashboard', $data);
    }

    public function update(Request $request, Checkouts $checkouts)
    {
        $checkouts->is_paid = true;
        $checkouts->save();
        $request->session()->flash('success', "Checkout with ID {$checkouts->id} has been updated");
        return redirect(route('admin.dashboard'));
    }
}
