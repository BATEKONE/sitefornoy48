$(document).ready(function() {
    // let audio = $('#alarm-sound')[0];
    let audio = new Audio('/admin/sounds/alarm-1.mp3');
    audio.muted = true;

    let notice_timeout;
    function playNotice() {
        if(audio.paused)
            audio.play();
        else
            audio.currentTime = 0;

        clearTimeout(notice_timeout);
        notice_timeout = setTimeout(function() {
            audio.pause();
            audio.currentTime = 0;
        }, 10000);

    }

    setInterval(function() {
        $.ajax({
            type: 'post',
            url: '/admin/handlers/orders.php',
            data: 'get_notices=1',
            success: function(response) {
                response = JSON.parse(response);
                if(response.size > 0) {
                    if($('.enable-notice').hasClass('active')) {
                        playNotice();
                    }

                    for(let i = 0; i < response.size; i++) {
                        let order = response.orders[i];
                        if($('.orders-table tr[data-id="'+order.id+'"]').length == 0)
                            $('.orders-table tbody tr').first().before(response.orders_html[order.id]);
                    }
                }
            }
        });
    }, 2000);

    $('.enable-notice').click(function() {
        $(this).toggleClass('active green');
        if($(this).hasClass('active')) {
            audio.muted = false;
            $(this).text('Выключить уведомления');
        }
        else {
            audio.muted = true;
            $(this).text('Включить уведомления');
        }
    });

});