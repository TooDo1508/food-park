<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\SectionTitle;
use App\Models\Slider;
use App\Models\WhyChooseUs;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    //
    public function index(): View
    {
        $sliders = Slider::where('status', 1)->get();
        $sectionTitles = $this->getSectionTitles();
        $whyChooseUs = WhyChooseUs::where('status', 1)->get();
        $products = Product::where([
            'status' => 1,
            'show_at_home' => 1,
        ])->orderBy('id', 'desc')
            ->take(8)
            ->get();


        $categories = Category::where([
            'status' => 1,
            'show_at_home' => 1,
        ])->get();
        return view('frontend.home.index', compact(
            'sliders',
            'sectionTitles',
            'whyChooseUs',
            'categories'
        ));
    }

    public function getSectionTitles()
    {
        $keys = [
            'why_choose_us_top_title',
            'why_choose_us_main_title',
            'why_choose_us_sub_title'
        ];
        return SectionTitle::whereIn('key', $keys)->pluck('value', 'key');
    }

    public function showProduct(string $slug)
    {
        $product = Product::with([
            'productImages',
            'productSizes',
            'productOptions',
        ])->where([
            'slug' => $slug,
            'status' => 1,
        ])->firstOrFail();

        $relatedProducts = Product::where('category_id', $product->category_id)->where('id', '!=', $product->id)->take(8)->latest()->get();
        return view('frontend.pages.product-view', compact('product', 'relatedProducts'));
    }

    public function loadProductModal($productId)
    {
        $product = Product::with(['productSizes', 'productOptions'])->findOrFail($productId);
        return view('frontend.layouts.ajax-files.product-popup-modal', compact('product'))->render();
    }

    public function applyCoupon(Request $request)
    {

        $subtotal = $request->subtotal;
        $code = $request->code;
        $coupon = Coupon::where('code', $code)->first();
        if (!$coupon) {
            return response([
                'message' => 'Invalid coupon code',
            ], 422);
        }

        if ($coupon->quantity <= 0) {
            return response([
                'message' => 'Coupon has been fully redeemed',
            ], 422);
        }

        if ($coupon->expire_date < now()) {
            return response([
                'message' => 'Coupon has expire',
            ], 422);
        }

        if ($coupon->discount_type === 'percent') {
            $discount = $subtotal * $coupon->discount / 100;
            $discount = number_format($subtotal * $coupon->discount / 100, 2);
        } elseif ($coupon->discount_type === 'amount') {
            $discount = number_format($coupon->discount, 2);
        }

        $finalTotal = $subtotal - $discount;

        session()->put('coupon', ['code' => $code, 'discount' => $discount]);
        return response([
            'message' => 'Coupon applied successfully',
            'discount' => $discount,
            'finalTotal' => $finalTotal
        ]);
    }
}
