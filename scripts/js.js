const animItems = document.querySelectorAll('._anim-items');

function offset(el) {
    const rect = el.getBoundingClientRect(),
        scrollLeft = window.pageXOffset || document.documentElement.scrollLeft,
        scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    return { top: rect.top + scrollTop, left: rect.left + scrollLeft };
}

if(animItems.length > 0) {
    $(window).scroll(function() {
        animOnScroll();
    });
    function animOnScroll() {
        for(let i = 0; i < animItems.length; i++) {
            const animItem = animItems[i];
            const animItemHeight = animItem.offsetHeight;
            const animItemOffset = offset(animItem).top;
            const animStart = 4;

            let animItemPoint = window.innerHeight - animItemHeight / animStart;

            if(animItemHeight > window.innerHeight)
                animItemPoint = window.innerHeight - window.innerHeight / animStart;

            if((pageYOffset > animItemOffset - animItemPoint) && pageYOffset < (animItemOffset + animItemHeight))
                $(animItem).addClass('_active');
            else if(!$(animItem).hasClass('_anim-no-hide'))
                $(animItem).removeClass('_active');
        }
    }
}

function load_products(cat, page = 1, more = false) {
    $.ajax({
        url: '/ajax/products?doing_ajax=1',
        data: 'get_cat='+cat+'&page='+page,
        method: 'post',
        dataType: 'json',
        success: function(response) {
            window.history.replaceState(null, document.title, "/?cat="+cat);
            if(more)
                $('.cat-products-inner').append(response.products);
            else {
                $('.cat-products-inner').html(response.products);
                $('.cat-products-categories').html(response.categories);
                $('.cat-product-additions').html(response.addition_products);
                $('.section_slick-slider').slick({
                    slidesToShow: 4,
                    infinite: false,
                });
                $('body, html').animate({ scrollTop: $('.big_menu_four').offset().top }, 800);
                $('.load_more').data('cat_id', cat);
                if(response.cat_gallery) {
                    $('.cat-gallery-wrap').html(response.cat_gallery);
                    $('.cat-gallery').slick({
                        slidesToShow: 4,
                        slidesToScroll: 1,
                        infinite: false,
                        responsive: [
                            {
                                breakpoint: 905,
                                settings: {
                                    slidesToShow: 2,
                                }
                            },
                            {
                                breakpoint: 480,
                                settings: {
                                    slidesToShow: 1,
                                }
                            },
                        ]
                    });
                }
                else {
                    $('.cat-gallery-wrap').html('');
                }
            }

            $('.load_more').removeClass('hidden').data('page', page + 1).prop('disabled', false);
            if(!response.show_more) {
                $('.load_more').slideUp(400, function() {
                    $(this).addClass('hidden').removeAttr('style');
                });
            }
        },
        error: function(e) {
            $('.load_more').prop('disabled', false);
            console.log(e.responseText);
        }
    });
}
function load_cat_products(cat, page = 1, more = false) {
    $.ajax({
        url: '/ajax/products?doing_ajax=1',
        data: 'get_cat='+cat+'&page='+page,
        method: 'post',
        dataType: 'json',
        success: function(response) {
            if(more)
                $('.under_cat-products').append(response.products);
            else {
                $('.under_cat-products').html(response.products);
                $('body, html').animate({ scrollTop: $('.under_cat-products').offset().top }, 800);
                $('.load_more_cat').data('cat_id', cat);
            }

            $('.load_more_cat').removeClass('hidden').data('page', page + 1).prop('disabled', false);
            $('.load_more_cat').data('cat_id', cat);
            if(!response.show_more) {
                $('.load_more_cat').slideUp(400, function() {
                    $(this).addClass('hidden').removeAttr('style');
                });
            }

        },
        error: function(e) {
            $('.load_more').prop('disabled', false);
            console.log(e.responseText);
        }
    });
}
function number_format(number, decimals, dec_point, thousands_sep) {
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec);
            return '' + Math.round(n * k) / k;
        };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
}
$(document).ready(function() {
    $('.count_basket_button').click(function() {
        let $input = $(this).parent().find('.count_input');
        let val = parseInt($input.val());
        let min = parseInt($input.attr('min'));
        let max = parseInt($input.attr('max'));

        if($(this).hasClass('minus-btn') && val > min)
            $input.val(--val);
        else if($(this).hasClass('plus-btn') && val < max)
            $input.val(++val);

        $input.trigger('change');
    });
    let count_timeout;
    $('.count_input').change(function() {
        let count = parseInt($(this).val());
        let min = parseInt($(this).attr('min'));
        let max = parseInt($(this).attr('max'));

        let $product = $(this).closest('.section_basket_one_stroke');
        let attribute_id = $product.data('attribute_id');
        let product_id = $product.data('id');

        let $price = $(this).closest('.section_basket_one_stroke').find('.section_basket_price-number');
        let price = parseInt($price.data('price'));

        if(count > max)
            count = max;
        if(count < min)
            count = min;
        $(this).val(count);

        $price.text(number_format(price * count, 0, '.', ' '));

        clearTimeout(count_timeout);
        count_timeout = setTimeout(function() {
            let full_price = 0;
            $('.count_input').each(function() {
                let $price = $(this).closest('.section_basket_one_stroke').find('.section_basket_price-number');
                let price = parseInt($price.data('price'));
                full_price += price * parseInt($(this).val());
            });
            $('.section_price-value').text(number_format(full_price, 0, '.', ' '));
            $('#odd_money').attr('min', full_price);

            $('.section_delivery_button').prop('disabled', full_price < 700);


            $.ajax({
                url: '/ajax/basket?doing_ajax=1',
                method: 'post',
                data: 'update_count=1&product_id='+product_id+'&attribute_id='+attribute_id+'&count='+count,
                success: function(response) {
                    // console.log(response);
                },
                error: function(e) {
                    console.log(e.responseText);
                }
            });
        }, 500);
    });
    $('.show_cat').click(function(e) {
        e.preventDefault();
        load_products($(this).data('id'));
    });
    $('.load_more').click(function() {
        let page = parseInt($(this).data('page'));
        let cat_id = $(this).data('cat_id');
        $(this).prop('disabled', true);
        load_products(cat_id, page, true);
    });
    $('body').on('click', '.show_under_cat', function(e) {
        e.preventDefault();
        load_cat_products($(this).data('id'));
    });
    $('body').on('click', '.load_more_cat', function(e) {
        e.preventDefault();
        $(this).prop('disabled', true);
        let page = parseInt($(this).data('page'));
        load_cat_products($(this).data('cat_id'), page, true);
    });
    $('.cat-gallery').slick({
        slidesToShow: 4,
        slidesToScroll: 1,
        infinite: false,
        responsive: [
            {
                breakpoint: 905,
                settings: {
                    slidesToShow: 2,
                }
            },
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 1,
                }
            },
        ]
    });

    $('body').on('click', '.add_to_cart', function(e) {
        e.preventDefault();
        if($(this).hasClass('disabled'))
            return true;

        let attribute_id = $(this).data('attribute_id');
        let product_id = $(this).data('id');
        let $button = $(this);
        
        $.ajax({
            url: '/ajax/basket?doing_ajax=1',
            method: 'post',
            data: 'add_to_cart=1&product_id='+product_id+'&attribute_id='+attribute_id,
            dataType: 'json',
            success: function(response) {
                $('.menu_nav_sector4_basket .basket-count').text(response.count);
                if($button.hasClass('add_to_cart_cart'))
                    window.location.reload();
                else {
                    $('.added-to-cart').addClass('active');
                    setTimeout(function() {
                        $('.added-to-cart').removeClass('active');
                    }, 2500);
                }
            }
        });
    });
    $('body').on('click', '.remove_product', function() {
        if(!confirm('Вы точно хотите удалить товар из корзины?'))
            return false;

        let $product = $(this).closest('.section_basket_one_stroke');
        let attribute_id = $product.data('attribute_id');
        let product_id = $product.data('id');

        $.ajax({
            url: '/ajax/basket?doing_ajax=1',
            method: 'post',
            data: 'remove_product=1&product_id='+product_id+'&attribute_id='+attribute_id,
            success: function(response) {
                window.location.reload();
            }
        });
    });

    $('.order-form').submit(function(e) {
        e.preventDefault();

        $('.section_delivery_button').prop('disabled', true);

        let data = 'add_order=1&'+$(this).serialize();
        $.ajax({
            url: '/ajax/order?doing_ajax=1',
            method: 'post',
            data: data,
            dataType: 'json',
            success: function(response) {
                $('.section_delivery_button').prop('disabled', false);
                if(response.success)
                    $('.order-success--success').addClass('active');
                else {
                    $('.order-success--error .message').text(response.error);
                    $('.order-success--error').addClass('active');
                }

                if(response.debug)
                    console.log(response.debug);
            },
            error: function(e) {
                $('.section_delivery_button').prop('disabled', false);
                console.log(e);
            }
        });

        return false;
    });
    $('#odd_money_trigger').change(function() {
        if($(this).val() == '0') {
            $('#odd_money').addClass('hidden').removeAttr('required');
        }
        else {
            $('#odd_money').removeClass('hidden').attr('required', 'required');
        }
    });
    $('.section_pay_pay input[name="pay"]').change(function() {
        if($(this).val() == 'cash') {
            $('.section_pay_how').removeClass('hidden');
            if($('#odd_money_trigger').val() == 0)
                $('#odd_money').removeAttr('required');
            else
                $('#odd_money').attr('required', 'required');
        }
        else {
            $('.section_pay_how').addClass('hidden');
            $('#odd_money').removeAttr('required');
        }
    });
});