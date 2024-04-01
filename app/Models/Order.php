<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'order';

    public static function create ($list) {
        foreach ($list as $product_id => $quantity) {
            $item = new Order;
            $item->product_id = $product_id;
            $item->quantity = $quantity;
            $item->user_id = auth()->id();
            $item->save();
        }

        return response()->json(['status' => true, 'items' => $list], 200);
    }

    public static function get ($userId) {
        return Order::where('user_id', $userId)->get();
    }
}
