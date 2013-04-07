$(document).ready(function() {
    console.log('refreshToken.js');

    // enable expire logic ?

    if($('.dv-expires').length > 0)
    {
        var serviceKey = $('.dv-expires').data('service');
        

        setInterval(function() {
            var expires = $('.dv-expires').html();
            expires = expires - 1;
            $('.dv-expires').html(expires);

            if(expires < 60)
            {
                refreshToken();
            }
        }, 1000);
    }


    // send a refreshToken request

    function refreshToken()
    {
        var serviceKey = $('.dv-expires').data('service');

        // send refresh request and expect seconds in result

        var data = {
            serviceKey: serviceKey
        };

        Craft.postActionRequest('duktvideos/ajax/refreshToken', data, function(response) {
            console.log('refreshToken response', response);

            $('.dv-expires').html(response);
            
        });
    }

});