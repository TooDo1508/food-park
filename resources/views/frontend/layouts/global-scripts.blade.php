<script>
    function showLoader() {
        $('.overlay-container').removeClass('d-none');
        $('.overlay').addClass('active');
    }

    function hidenLoader() {
        $('.overlay').removeClass('active');
        $('.overlay-container').addClass('d-none');
    }

    function loadProductModal(productId) {
        $.ajax({
            method: 'GET',
            url: '{{ route('load-product-modal', ':productId') }}'.replace(':productId', productId),
            beforeSend: function() {
                showLoader();
            },
            success: function(response) {
                productPopup = response;
                $(".load_product_modal_body").html(productPopup);
                $('#cartModal').modal('show');
            },
            error: function(xhr, status, error) {
                console.error(error);
            },
            complete: function() {
                hidenLoader();
            },
        })
    }

    function updateSidebarCart(callback = null) {
        $.ajax({
            method: 'GET',
            url: '{{ route('get-cart-products') }}',
            success: function(response) {
                $('.cart_contents').html(response);
                let cartTotal = $('#cart_total').val();
                $('.cart_subtotal').text("{{ currencyPosition(':cartTotal') }}", cartTotal);

                let cartCount = $('#cart_product_count').val();
                $('.cart_count').text(cartCount);

                if (callback && typeof callback === 'function') {
                    callback();
                }
            },
            error: function(xhr, status, error) {
                console.error(error);
            },
        })
    }
    // remove item cart product
    function removeProductFromSidebar(rowId) {
        $.ajax({
            method: 'GET',
            url: "{{ route('cart-product-remove', ':rowId') }}".replace(':rowId', rowId),
            beforeSend: function() {
                showLoader();
            },
            success: function(response) {
                if (response.status === 'success') {
                    updateSidebarCart(function() {
                        toastr.success(response.message);
                        hidenLoader();
                    });
                }
            },
            error: function(xhr, status, error) {
                let errorMessage = xhr.responseJSON.message;
                toastr.error(errorMessage);
            },
            complete: function() {
                hidenLoader();
            },
        })
    }
</script>
