<script>
    function loadProductModal(productId){
        $.ajax({
            method: 'GET',
            url: '{{ route("load-product-modal", ":productId") }}' .replace(':productId', productId),
            success: function(response){
                productPopup = response;
                $(".load_product_modal_body").html(productPopup);
                $('#cartModal').modal('show');
            },
            erro: function(xhr, status, error){
                console.error(error);
            }
        })
    }
</script>
