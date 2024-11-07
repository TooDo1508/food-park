<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductOption;
use Illuminate\Http\Request;

class ProductOptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
        ], [
            'name.required' => 'Product Option name is required',
            'name.max' => 'Product Option max length is 100',
            'price.required' => 'Product Option price is required',
            'price.numeric' => 'Product Option have to be numeric',
        ]);

        $productOption = new ProductOption();
        $productOption->name = $request->name;
        $productOption->price = $request->price;
        $productOption->product_id = $request->product_id;
        $productOption->save();


        toastr()->success('Created product size');

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $productOption = ProductOption::findOrFail($id);
            $productOption->delete();
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
