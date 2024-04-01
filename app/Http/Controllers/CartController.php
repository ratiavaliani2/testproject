<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller {

    public function currentItems (Request $request) {
        return $request->session()->get('cart');

    }

    public function __construct (Request $request) {
        $items = $request->session()->get('cart');
        if (is_null($items)) $request->session()->put('cart', []);
    }

    public function items (Request $request) {
        return response()->json($this->currentItems($request), 200);
    }

    public function insertItem (Request $request) {
        if (is_null($request->product_id)) false;

        $items = $this->currentItems($request);

        $items[$request->product_id] = 1;

        $request->session()->put('cart', $items);
        return $items;
    }

    public static function status (Request $request, $status) {
        return response()->json(['status' => $status] , 200);
    }

    public function create (Request $request) {
        return response()->json($this->insertItem($request), 200);
    }

    public function add_amount (Request $request) {
        $items = $this->currentItems($request);

        $items[$request->product_id] = $items[$request->product_id] + 1;

        $request->session()->put('cart', $items);

        return self::status($request, true);
    }

    public function reduce_amount (Request $request) {
        $items = $this->currentItems($request);

        if (
            array_key_exists($request->product_id, $items) &&
            (intval($items[$request->product_id]) - 1) >= 1
        ) {
            $items[$request->product_id] = $items[$request->product_id]-1;

            $request->session()->put('cart', $items);

            return self::status($request, true);
        }

        return self::status($request, false);
    }

    public function remove (Request $request) {
        $items = $this->currentItems($request);

        if (array_key_exists($request->product_id, $items)) unset($items[$request->product_id]);

        return self::status($request, true);
    }

    public function order (Request $request) {
        return Order::create($this->currentItems($request));
    }
}
