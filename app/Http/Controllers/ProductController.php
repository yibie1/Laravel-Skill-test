<?php

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return view('products.index');
    }

    public function store(Request $request)
    {
        // Validate the form data
        $request->validate([
            'name' => 'required',
            'quantity' => 'required|integer',
            'price' => 'required|numeric'
        ]);

        // Save the product to the database
        Product::create([
            'name' => $request->name,
            'quantity' => $request->quantity,
            'price' => $request->price
        ]);

        return response()->json(['message' => 'Product saved successfully!']);
    }

    public function getProducts()
    {
        // Fetch all products ordered by creation date
        $products = Product::orderBy('created_at', 'desc')->get();

        // Prepare data for frontend including total value and sum total of all products
        $sumTotal = $products->sum(function ($product) {
            return $product->quantity * $product->price;
        });

        return response()->json([
            'products' => $products,
            'sumTotal' => $sumTotal
        ]);
    }
}
