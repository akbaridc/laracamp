<?php

namespace App\Http\Controllers\User;

use App\Models\Camps;
use App\Models\Checkouts;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\Checkout\AfterCheckout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\User\Checkout\Store;
use Exception;
use Midtrans;

class CheckoutController extends Controller
{

    public function __construct()
    {
        Midtrans\Config::$serverKey = env('MIDTRANS_SERVERKEY');
        Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION');
        Midtrans\Config::$isSanitized = env('MIDTRANS_IS_SANITIZED');
        Midtrans\Config::$is3ds = env('MIDTRANS_IS_3DS');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Camps $camp, Request $request)
    {

        if ($camp->isRegistered) {
            $request->session()->flash('error', "You already registered on {$camp->title} camp.");
            return redirect(route('user.dashboard'));
        }

        $data['camp'] = $camp;
        return view('checkout.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function success()
    {
        return view('checkout.success');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Store $request, Camps $camp)
    {
        // return $request->all();
        //mapping request data
        $data = $request->all();
        $data['user_id'] = Auth::id();
        $data['camp_id'] = $camp->id;

        //update user data
        $user = Auth::user();
        $user->email = $data['email'];
        $user->name = $data['name'];
        $user->occupation = $data['occupation'];
        $user->save();

        //create tabel checkout
        $checkout = Checkouts::create($data);

        $this->getSnapRedirect($checkout);

        Mail::to(Auth::user()->email)->send(new AfterCheckout($checkout));

        return redirect(route('checkout.success'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Checkouts  $checkouts
     * @return \Illuminate\Http\Response
     */
    public function show(Checkouts $checkout)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Checkouts  $checkouts
     * @return \Illuminate\Http\Response
     */
    public function edit(Checkouts $checkout)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Checkouts  $checkouts
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Checkouts $checkout)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Checkouts  $checkouts
     * @return \Illuminate\Http\Response
     */
    public function destroy(Checkouts $checkout)
    {
        //
    }

    /**
     * Midtrans handler
     */
    public function getSnapRedirect(Checkouts $checkout)
    {
        $orderId = $checkout->id . '-' . Str::random(5);
        $price = $checkout->Camp->price * 1000;
        $checkout->midtrans_booking_code = $orderId;

        $transaction_detail = [
            'order_id' => $checkout,
            'gross_amount' => $price,

        ];

        $item_details = [
            'id' => $orderId,
            'price' => $price,
            'quantity' => 1,
            'name' => "Payment for {$checkout->Camp->title}"

        ];

        $userData = [
            "first_name" => $checkout->User->name,
            "last_name" => "",
            "address" => $checkout->User->address,
            "city" => "",
            "postal_code" => "",
            "phone" => $checkout->User->phone,
            "country_code" => "IDN"
        ];

        $customer_details = [
            "first_name" => $checkout->User->name,
            "lst_name" => "",
            "email" => $checkout->User->email,
            "phone" => $checkout->User->phone,
            "billing_address" => $userData,
            "shipping_address" => $userData
        ];

        $midtrans_params = [
            "transaction_details" => $transaction_detail,
            "customer_details" => $customer_details,
            "item_detail" => $item_details
        ];

        try {
            //get snap payment page url
            $paymentURL = \Midtrans\Snap::createTransaction($params)->redirect_url;
            $checkout->midtrans_url = $paymentURL;
            $checkout->save();

            return $paymentURL;
        } catch (Exception $e) {
            return false;
        }
    }
}
