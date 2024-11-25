@extends('frontend.layouts.master')

@section('content')
    <!-- BREADCRUMB START -->
    <section class="fp__breadcrumb" style="background: url({{ asset('frontend/images/counter_bg.jpg') }});">
        <div class="fp__breadcrumb_overlay">
            <div class="container">
                <div class="fp__breadcrumb_text">
                    <h1>cart view</h1>
                    <ul>
                        <li><a href="index.html">home</a></li>
                        <li><a href="#">cart view</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!-- BREADCRUMB END -->


    <!-- CART VIEW START -->
    <section class="fp__cart_view mt_125 xs_mt_95 mb_100 xs_mb_70">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 wow fadeInUp" data-wow-duration="1s">
                    <div class="fp__cart_list">
                        <div class="table-responsive">
                            <table>
                                <tbody>
                                    <tr>
                                        <th class="fp__pro_img">
                                            Image
                                        </th>

                                        <th class="fp__pro_name">
                                            details
                                        </th>

                                        <th class="fp__pro_status">
                                            price
                                        </th>

                                        <th class="fp__pro_select">
                                            quantity
                                        </th>

                                        <th class="fp__pro_tk">
                                            total
                                        </th>

                                        <th class="fp__pro_icon">
                                            <a class="clear_all" href="{{ route('cart.destroy') }}">clear all</a>
                                        </th>
                                    </tr>
                                    @foreach (Cart::content() as $product)
                                        <tr>
                                            <td class="fp__pro_img"><img
                                                    src="{{ $product->options->product_info['image'] }}" alt="product"
                                                    class="img-fluid w-100">
                                            </td>

                                            <td class="fp__pro_name">
                                                <a
                                                    href="{{ route('product.show', $product->options->product_info['slug']) }}">{{ $product->name }}</a>
                                                @if (count($product->options->product_size) > 0)
                                                    <span>{{ $product->options->product_size['name'] }}
                                                        ({{ currencyPosition($product->options->product_size['price']) }})
                                                    </span>
                                                @endif
                                                @foreach ($product->options->product_options as $option)
                                                    <p>{{ $option['name'] }}
                                                        ({{ currencyPosition($option['price']) }})
                                                    </p>
                                                @endforeach
                                            </td>

                                            <td class="fp__pro_status">
                                                <h6>{{ currencyPosition($product->price) }}</h6>
                                            </td>

                                            <td class="fp__pro_select">
                                                <div class="quentity_btn">
                                                    <button class="btn btn-danger decrement"><i
                                                            class="fal fa-minus"></i></button>
                                                    <input type="text" placeholder="1" value="{{ $product->qty }}"
                                                        id="quantity" readonly data-id="{{ $product->rowId }}">
                                                    <button class="btn btn-success increment"><i
                                                            class="fal fa-plus"></i></button>
                                                </div>
                                            </td>

                                            <td class="fp__pro_tk">
                                                <h6 class="product_cart_total">
                                                    {{ currencyPosition(productTotal($product->rowId)) }}</h6>
                                            </td>

                                            <td class="fp__pro_icon">
                                                <a href="#" class="remove_cart_product"
                                                    data-id="{{ $product->rowId }}"><i class="far fa-times"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @if (Cart::content()->count() === 0)
                                        <tr>
                                            <td colspan="6" class="text-center" style="width: 100%; display: inline">Cart
                                                is empty</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 wow fadeInUp" data-wow-duration="1s">
                    <div class="fp__cart_list_footer_button">
                        <h6>total cart</h6>
                        <p>subtotal: <span id="subtotal">{{ currencyPosition(cartTotal()) }}</span></p>
                        <p>delivery: <span>$00.00</span></p>
                        <p>discount: <span id="discount">
                                @if (isset(session()->get('coupon')['discount']))
                                    {{ session()->get('coupon')['discount'] }} {{ config('settings.site_currency_icon') }}
                                @else
                                    0 {{ config('settings.site_currency_icon') }}
                                @endif
                            </span></p>
                        <p class="total"><span>total:</span> <span id="final_total">
                                @if (isset(session()->get('coupon')['discount']))
                                    {{ cartTotal() - session()->get('coupon')['discount'] }}
                                    {{ config('settings.site_currency_icon') }}
                                @else
                                    {{ cartTotal() }} {{ config('settings.site_currency_icon') }}
                                @endif
                            </span></p>
                        <form id="coupon_form">
                            <input type="text" id="coupon_code" name="code" placeholder="Coupon Code">
                            <button type="submit">apply</button>
                        </form>
                        <div class="coupon_card"></div>
                        @if (session()->has('coupon'))
                            <div class="card mt-2">
                                <div class="m-3">
                                    <span>Applied Coupon:<b> {{ session()->get('coupon')['code'] }}</b></span>
                                    <button id="destroy_coupon"><i class="far fa-times"></i></button>
                                </div>
                            </div>
                        @endif

                        <a class="common_btn" href="{{ route('checkout.index') }}">checkout</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- CART VIEW END -->
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            var cartTotal = parseInt("{{ cartTotal() }}");

            $('.increment').on('click', function(e) {
                let quantity = $(this).siblings('#quantity');
                let currentQuantity = parseFloat(quantity.val());
                let rowId = quantity.data("id");

                quantity.val(currentQuantity + 1);

                cartQtyUpdate(rowId, quantity.val(), function(response) {
                    if (response.status === 'success') {
                        cartTotal = response.cart_total;
                        quantity.val(response.qty);
                        $('#subtotal').text("{{ currencyPosition(':test') }}".replace(':test',
                            response.cart_total));
                        $('#final_total').text(response.grand_cart_total +
                            "{{ config('settings.site_currency_icon') }}");
                        let productTotal = response.product_total;
                        quantity.closest("tr")
                            .find('.product_cart_total')
                            .text("{{ currencyPosition(':productTotal') }}"
                                .replace(':productTotal', productTotal));

                    } else if (response.status === 'error') {
                        quantity.val(response.qty);
                        toastr.error(response.message);
                    }
                });
            })

            $('.decrement').on('click', function(e) {
                let quantity = $(this).siblings('#quantity');
                let currentQuantity = parseFloat(quantity.val());
                let rowId = quantity.data("id");
                quantity.val(currentQuantity - 1);

                if (currentQuantity > 1) {
                    cartQtyUpdate(rowId, quantity.val(), function(response) {
                        cartTotal = response.cart_total;
                        quantity.val(response.qty);
                        if (response.status === 'success') {
                            $('#subtotal').text(response.cart_total);
                            $('#final_total').text(response.grand_cart_total +
                                "{{ config('settings.site_currency_icon') }}");

                            let productTotal = response.product_total;
                            quantity.closest("tr")
                                .find('.product_cart_total')
                                .text("{{ currencyPosition(':productTotal') }}"
                                    .replace(':productTotal', productTotal));

                        }
                    });
                }
            })

            function cartQtyUpdate(rowId, qty, callback = null) {
                $.ajax({
                    method: 'POST',
                    url: '{{ route('cart.quantity-update') }}',
                    data: {
                        'rowId': rowId,
                        'qty': qty,
                    },
                    beforeSend: function() {
                        showLoader();
                    },
                    success: function(response) {
                        if (callback && typeof callback === 'function') {
                            callback(response);
                        }
                    },
                    error: function(xhr, status, error) {
                        let errorMessage = xhr.responseJSON.message;
                        hidenLoader();
                        toastr.error(errorMessage);
                    },
                    complete: function() {
                        hidenLoader();
                    }
                })
            }

            $('.remove_cart_product').on('click', function(e) {
                e.preventDefault();
                let rowId = $(this).data("id");
                removeCartProduct(rowId);
                $(this).closest('tr').remove();
            })

            function removeCartProduct(rowId) {
                $.ajax({
                    method: 'GET',
                    url: "{{ route('cart-product-remove', ':rowId') }}".replace(':rowId', rowId),
                    beforeSend: function() {
                        showLoader();
                    },
                    success: function(response) {
                        cartTotal = response.cart_total;
                        updateSidebarCart();
                        $('#subtotal').text("{{ currencyPosition(':test') }}".replace(':test', response
                            .cart_total));
                        $('#final_total').text(response.grand_cart_total +
                            "{{ config('settings.site_currency_icon') }}");
                    },
                    error: function(xhr, status, error) {
                        let errorMessage = xhr.responseJSON.message;
                        hidenLoader();
                        toastr.error(errorMessage);
                    },
                    complete: function() {
                        hidenLoader();
                    },
                })
            }

            $('#coupon_form').on('submit', function(e) {
                e.preventDefault();
                let subtotal = cartTotal;
                let code = $("#coupon_code").val();

                couponApply(code, subtotal);
            })

            function couponApply(code, subtotal) {
                $.ajax({
                    method: 'POST',
                    url: '{{ route('apply-coupon') }}',
                    data: {
                        'code': code,
                        'subtotal': subtotal,
                    },
                    beforeSend: function() {

                    },
                    success: function(response) {
                        $('#coupon_code').val("");
                        $('#discount').text(response.discount + "{{ config('settings.site_currency_icon') }}");
                        $('#final_total').text("{{ config('settings.site_currency_icon') }}" + response
                            .finalTotal);
                        couponCartHtml = `<div class="card mt-2">
                                <div class="m-3">
                                    <span>Applied Coupon: <b class="v_coupon_code">${response.coupon_code}</b></span>
                                    <button id="destroy_coupon"><i class="far fa-times"></i></button>
                                </div>
                            </div>`;
                        $('.coupon_card').html(couponCartHtml);
                        toastr.success(response.message);
                    },
                    error: function(xhr, status, error) {
                        let errorMessage = xhr.responseJSON.message;
                        // hidenLoader();
                        toastr.error(errorMessage);
                    },
                    complete: function() {

                    }
                })
            }

            $(document).on('click', "#destroy_coupon", function(e) {
                destroyCoupon();
            })

            function destroyCoupon() {
                $.ajax({
                    method: 'GET',
                    url: '{{ route('destroy-coupon') }}',
                    beforeSend: function() {
                        showLoader();
                    },
                    success: function(response) {
                        $('.coupon_card').html("");
                        $('#discount').text("{{ config('settings.site_currency_icon') }}" + 0);
                        $('#final_total').text("{{ config('settings.site_currency_icon') }}" + response
                            .grand_cart_total);
                        toastr.success(response.message);
                    },
                    error: function(xhr, status, error) {
                        let errorMessage = xhr.responseJSON.message;
                        hidenLoader();
                        toastr.error(errorMessage);
                    },
                    complete: function() {
                        hidenLoader();
                    }
                })
            }
        })
    </script>
@endpush
