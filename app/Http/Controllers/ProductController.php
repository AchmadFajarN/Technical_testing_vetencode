<?php

namespace App\Http\Controllers;
use App\Models\Product;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::where('active', true)->get(['id', 'name', 'price']);

        return response()->json([
            "status"=> "success",
            "data"=> $products
        ]);
    }
}
