<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductOption;
use App\Models\ProductSize;
use Illuminate\Http\Request;

class ProductSizeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(string $productId)
    {
        //
        $product = Product::findOrFail($productId);
        $productSizes = ProductSize::where('product_id', $productId)->get();
        $productOptions = ProductOption::where('product_id', $productId)->get();
        return view('admin.product.size.index', compact('product', 'productSizes', 'productOptions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'name' => ['required', 'max:100'],
            'price' => ['required', 'numeric'],
            'product_id' => ['required', 'integer'],
        ]);

        $productSize = new ProductSize();
        $productSize->name = $request->name;
        $productSize->price = $request->price;
        $productSize->product_id = $request->product_id;
        $productSize->save();


        toastr()->success('Created product size');

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $productSize = ProductSize::findOrFail($id);
            $productSize->delete();
            return response([
                'status' => 'success',
                'message' => 'Deleted product size successfully.',
            ]);
        } catch (\Exception $e) {
            return response([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }
}
