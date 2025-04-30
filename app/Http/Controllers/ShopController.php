<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index()
    {
        $products = [
            ['id' => 1, 'name' => 'Shoes', 'price' => 1000],
            ['id' => 2, 'name' => 'Pants', 'price' => 2000],
            ['id' => 3, 'name' => 'Hat', 'price' => 3000],
        ];

        return view('shop.index', compact('products'));
    }
}
