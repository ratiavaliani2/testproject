<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Paysera\Transfer;

class OrderController extends Controller {

    protected $Transfer;
    protected function calculateAmount ($list) {
        // adding a theoretical amount

        return 500;
    }

    public function checkout (Request $request) {
        /* ---> Description <--- */

            // Getting list of items in the order by user ID
            $list = Order::get(auth()->id());
            $itemAmount = $this->calculateAmount($list);

            // Creating Transfer With Amount Only
            $this->Transfer = new Transfer();
            $this->Transfer->create($itemAmount);
            $this->Transfer->signTransfer($request);
    }

    public function callback (Request $request) {
        Transfer::callback($request);
    }
}
