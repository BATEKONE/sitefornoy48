$(document).ready(function() {
    $('#delete-form').submit(function(e) {
       if(!confirm('Вы точно хотите удалить товар?'))
           e.preventDefault();
    });
    $('.admin-form-attribute-add').click(function() {
        $(this).blur();
        let $clone = $('.admin-form-attribute').last().clone();
        $clone.find('input').each(function() {
            if($(this).attr('type') == 'number')
                $(this).val(0);
            else
                $(this).val('');
        });
        $('.admin-form-attributes').append($clone);

        if($('.admin-form-attribute').length > 1)
            $('.admin-form-attribute-delete').removeClass('hidden');
        else
            $('.admin-form-attribute-delete').addClass('hidden');
    });
    $('body').on('click', '.admin-form-attribute-delete:not(.hidden)', function() {
        $(this).closest('.admin-form-attribute').remove();

        if($('.admin-form-attribute').length > 1)
            $('.admin-form-attribute-delete').removeClass('hidden');
        else
            $('.admin-form-attribute-delete').addClass('hidden');
    });
    $(document).bind("keyup keydown", function(e){
        if(e.ctrlKey && e.which == 83) {
            e.preventDefault();
            $('button[form="product-form"]').first().trigger('click');
        }
    });
    $('#product-form').submit(function() {
        $('.image-elem.template').remove();
        // $('#product-image').remove();
    });
    $('#product-image').change(function() {
        let $image = $('.image-elem.template').clone().removeClass('template');
        $image.find('input[name="images[]"]').remove();

        let $clone = $(this).clone().attr('name', 'images[]');
        $image.append($clone);

        $image.find('.image-elem-name').text(this.files[0].name);
        $(this).val('');

        $('.images-wrap').append($image);

        if($('.image-elem:not(.template)').length > 1)
            $('.image-elem-delete').removeClass('hidden');
        else
            $('.image-elem-delete').addClass('hidden');
    });
    $('body').on('click', '.image-elem-delete:not(.hidden)', function() {
        $(this).closest('.image-elem').remove();

        if($('.image-elem:not(.template)').length > 1)
            $('.image-elem-delete').removeClass('hidden');
        else
            $('.image-elem-delete').addClass('hidden');
    });
});
