$(function(){
    $('.add_to_basket').on('click', function(e){
        e.preventDefault();
        let id = $(this).data('id');

        console.log(id);
    });
});