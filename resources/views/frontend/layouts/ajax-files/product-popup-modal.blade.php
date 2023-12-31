<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="fal fa-times"></i></button>

<form action="" id="modal_add_to_cart_form">

    <input type="hidden" name="product_id" value="{{ $product->id }}">

    <div class="fp__cart_popup_img">
        <img src="{{ asset($product->thumb_image) }}" alt="{{ $product->name }}" class="img-fluid w-100">
    </div>
    <div class="fp__cart_popup_text">
        <a href="{{ route('product.show', $product->slug) }}" class="title">{!! $product->name !!}</a>
        <p class="rating">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
            <i class="far fa-star"></i>
            <span>(201)</span>
            {{-- <h1>Quantity!!! - {{ $product->quantity }}</h1> --}}
        </p>
        <h4 class="price">
            @if ($product->offer_price > 0)
                <input type="hidden" name="base_price" value="{{ $product->offer_price }}">
                {{ currencyPosition($product->offer_price) }}
                <del>{{ currencyPosition($product->price) }}</del>
            @else
                <input type="hidden" name="base_price" value="{{ $product->price }}">
                {{ currencyPosition($product->price) }}
            @endif

        </h4>

        @if ($product->productSizes()->exists())
            <div class="details_size">
                <h5>select size</h5>

                @foreach ($product->productSizes as $productSize)
                    <div class="form-check">
                        <input class="form-check-input" type="radio" data-price="{{ $productSize->price }}"
                            name="product_size" id="size-{{ $productSize->name }}" value="{{ $productSize->id }}">
                        <label class="form-check-label" for="size-{{ $productSize->name }}"
                            style="max-width: 30% !important;">
                            {{ $productSize->name }} <span>+ {{ currencyPosition($productSize->price) }}</span>
                        </label>
                    </div>
                @endforeach
            </div>
        @endif

        @if ($product->productOptions()->exists())
            <div class="details_extra_item">
                <h5>select option <span>(optional)</span></h5>

                @foreach ($product->productOptions as $productOption)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="product_option[]"
                            data-price="{{ $productOption->price }}" value="{{ $productOption->id }}"
                            id="option-{{ $productOption->name }}">
                        <label class="form-check-label" for="option-{{ $productOption->name }}"
                            style="max-width: 30% !important;">
                            {{ $productOption->name }} <span>+ {{ currencyPosition($productOption->price) }}</span>
                        </label>
                    </div>
                @endforeach

            </div>
        @endif


        <div class="details_quentity">
            <h5>select quentity</h5>
            <div class="quentity_btn_area d-flex flex-wrapa align-items-center">
                <div class="quentity_btn">
                    <button class="btn btn-danger decrement"><i class="fal fa-minus"></i></button>
                    <input type="text" placeholder="1" value="1" name="quantity" id="quantity" readonly>
                    <button class="btn btn-success increment"><i class="fal fa-plus"></i></button>
                </div>
                <h3 id="total_price">
                    @if ($product->offer_price && $product->offer_price > 0)
                        {{ currencyPosition($product->offer_price) }}
                    @else
                        {{ currencyPosition($product->price) }}
                    @endif
                </h3>
            </div>
        </div>
        <ul class="details_button_area d-flex flex-wrap">
            @if($product->quantity === 0)
                <li><button class="common_btn modal_cart_button bg-danger" type="button" disabled>Stock Out</button></li>
            @else
            <li><button class="common_btn modal_cart_button" type="submit">add to cart</button></li>
            @endif
        </ul>
    </div>

</form>

<script>
    $(document).ready(function() {
        $('input[name="product_size"]').on('change', function() {
            updateTotalPrice();
        });

        $('input[name="product_option[]"]').on('change', function() {
            updateTotalPrice();
        });

        //Event handler for increment and decrement buttons
        $('.increment').on('click', function(e) {
            e.preventDefault();

            let quantity = $('#quantity');
            let currentQuantity = parseFloat(quantity.val());
            quantity.val(currentQuantity + 1);
            updateTotalPrice();
        });

        $('.decrement').on('click', function(e) {
            e.preventDefault();

            let quantity = $('#quantity');
            let currentQuantity = parseFloat(quantity.val());
            if (currentQuantity > 1) {
                quantity.val(currentQuantity - 1);
            }
            updateTotalPrice();

        });

        //Function to update total price based on selected options
        function updateTotalPrice() {
            let basePrice = parseFloat($('input[name="base_price"]').val());
            let selectedSizePrice = 0;
            let selectedOptionsPrice = 0;
            let quantity = parseFloat($('#quantity').val());


            //Calculate size price selected
            let selectedSize = $(
                'input[name="product_size"]:checked'
            ); //e kemi qit :checked per me kontrollu se cili button eshte i chekun se jo me ja marr vleren e secilit
            if (selectedSize.length > 0) {
                selectedSizePrice = parseFloat(selectedSize.data("price"));
            }

            //Calculate options price
            let selectedOptions = $('input[name="product_option[]"]:checked');
            $(selectedOptions).each(function() {
                selectedOptionsPrice += parseFloat($(this).data("price"));
            })



            //Shuma totale
            let totalPrice = (basePrice + selectedOptionsPrice + selectedSizePrice) * quantity;
            totalPrice = totalPrice.toFixed(2);
            $('#total_price').text("{{ config('settings.site_currency_icon') }}" + totalPrice);

        }

        //Add to cart function
        $('#modal_add_to_cart_form').on('submit', function(e) {
            e.preventDefault();

            console.log('a jemi ne pike');

            //Validation
            let selectedSize = $(this).find('input[name="product_size"]');
            console.log(selectedSize);
            if (selectedSize.length > 0) {
                if ($('input[name="product_size"]:checked').val() === undefined) {
                    toastr.error('Please select a size');
                    console.log('select size');
                    return;
                }
            }

            let formData = $(this).serialize();
            $.ajax({
                method: "POST",
                url: '{{ route('add-to-cart') }}',
                data: formData,
                beforeSend: function() {
                    $('.modal_cart_button').attr('disabled', 'true');
                    $('.modal_cart_button').html(
                        `<span class="spinner-border spinner-border-sm text-light" role="status" aria-hidden="true"></span>`
                    );
                },
                success: function(response) {
                    updateSidebarCart();
                    toastr.success(response.message);
                },
                error: function(xhr, status, error) {
                    console.log(xhr , status , error);
                    let errorMsg = xhr.responseJSON.message;
                    console.log(errorMsg);
                    toastr.error(errorMsg);
                },
                complete: function() {
                    $('.modal_cart_button').removeAttr('disabled');
                    $('.modal_cart_button').html(
                        'Add to cart'
                    );
                },
            });
        })


    });
</script>
