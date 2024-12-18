<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Cart;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use PhpParser\Node\Stmt\TryCatch;

use function Laravel\Prompts\error;

class CartController extends Controller
{
    public function index()
    {
        return view('frontend.pages.cart-view');
    }

    public function addToCart(Request $request)
    {
        $product = Product::with(['productSizes', 'productOptions'])->findOrFail($request->product_id);
        if ($product->quantity < $request->quantity) {
            throw ValidationException::withMessages(['Quantity is not availlable']);
        }

        try {
            $product = Product::with(['productSizes', 'productOptions'])->findOrFail($request->product_id);
            $productSize = $product->productSizes->where('id', $request->product_size)->first();
            $productOption = $product->productOptions->whereIn('id', $request->product_option);
            $options = [
                'product_size' => [],
                'product_options' => [],
                'product_info' => [
                    'image' => $product->thumb_image,
                    'slug' => $product->slug,
                ],
            ];
            if ($productSize !== null) {
                $options['product_size'] = [
                    'id' => $productSize->id,
                    'name' => $productSize->name,
                    'price' => $productSize->price,
                ];
            };

            foreach ($productOption as $option) {
                $options['product_options'][] = [
                    'id' => $option->id,
                    'name' => $option->name,
                    'price' => $option->price,
                ];
            };

            Cart::add([
                'id' => $product->id,
                'name' => $product->name,
                'qty' => $request->quantity,
                'price' => $product->offer_price > 0 ? $product->offer_price : $product->price,
                'weight' => 0,
                'options' => $options,
            ]);

            return response([
                'status' => 'success',
                'message' => 'Product added into Cart',
            ], 200);
        } catch (\Exception $e) {
            return response([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function getCartProduct()
    {
        return view('frontend.layouts.ajax-files.sidebar-cart-item')->render();
    }

    public function cartProductRemove($rowId)
    {
        try {
            Cart::remove($rowId);
            return response([
                'status' => 'success',
                'message' => 'Item has been removed!',
                'cart_total' => cartTotal(),
                'grand_cart_total' => grandCartTotal(),
            ], 200);
        } catch (\Throwable $th) {
            return response([
                'status' => 'error',
                'message' => 'Sorry something went wrong!',
            ], 500);
        }
    }

    public function cartQtyUpdate(Request $request)
    {
        $cartItem = Cart::get($request->rowId);
        $product = Product::findOrFail($cartItem->id);

        if ($product->quantity < $request->qty) {
            return response([
                'status' => 'error',
                'message' => 'Quantity is not availlable!',
                'qty' => $cartItem->qty
            ]);
        }

        try {
            $cart = Cart::update($request->rowId, $request->qty);
            return response([
                'status' => 'success',
                'message' => 'Update qty item success!',
                'qty' => $cart->qty,
                'product_total' => productTotal($request->rowId),
                'cart_total' => cartTotal(),
                'grand_cart_total' => grandCartTotal(),
            ], 200);
        } catch (\Exception $e) {
            return response([
                'status' => 'error',
                'message' => 'Sorry something went wrong!',
            ], 500);
        }
    }

    public function cartDestroy()
    {
        Cart::destroy();
        session()->forget('coupon');
        return redirect()->back();
    }
}
