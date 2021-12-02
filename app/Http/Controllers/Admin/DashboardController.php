<?php

namespace App\Http\Controllers\Admin;

use App\Models\Checkouts;
use App\Mail\Checkout\Paid;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

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

        Mail::to($checkouts->User->email)->send(new Paid($checkouts));

        $request->session()->flash('success', "Checkout with ID {$checkouts->id} has been updated");
        return redirect(route('admin.dashboard'));
    }
}
