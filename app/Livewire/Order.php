<?php

namespace App\Livewire;

use Midtrans\Snap;
use Midtrans\Config;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class Order extends Component
{
    public $total;
    public $snapToken;

    public function createSnapToken(Request $request)
    {
        Config::$serverKey = config('services.midtrans.serverKey');
        Config::$isProduction = config('services.midtrans.isProduction');
        Config::$isSanitized = config('services.midtrans.isSanitized');
        Config::$is3ds = config('services.midtrans.is3ds');

        // params   
        $params = [
            'transaction_details' => [
                'order_id' => uniqid(),
                'gross_amount' => $request->total, // Total amount
            ],
            'customer_details' => [
                'first_name' => Auth::user()->name,
                'email' => Auth::user()->email,
            ],
        ];

        // database


        try {
            // redirect 
            $this->snapToken = Snap::getSnapToken($params);
            return redirect()->back();
        } catch (\Exception $e) {
            dd($e->getMessage());
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function render()
    {
        return view('livewire.order');
    }
}
