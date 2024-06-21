<?php

namespace App\Http\Controllers;

use App\Models\ProductFaq;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function product($id)
    {
        $product = ProductFaq::where('product_id', $id)->first();
        $items = $product->faqs;

        return response()->json($items, 200);
    }
}
