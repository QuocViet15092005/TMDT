<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::all();

        $products = Product::with(['category', 'variants'])
            ->where('is_active', true)
            ->latest()
            ->take(8)
            ->get();

        $topSellingProducts = Product::getTopSelling(8);

        return view('home', compact(
            'categories',
            'products',
            'topSellingProducts'
        ));
    }
}