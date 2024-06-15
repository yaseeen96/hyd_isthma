$(function () {
    function removeErrorClass(selector) 
    {
         $.each($(`#${selector} input, #${selector} textarea, #${selector} select`), function (key, value) {
                value.classList.remove('is-invalid');
         });
         $(`#${selector} .error`).html('');
    }
    function otpVerification()
    {
        $('#otp_verification_card').show().siblings().css('display', 'none');
        $("html, body").animate({
            scrollTop: 0,
        }, 1000);
    }
    $('#login_with_phone').on('click', function (e)  {
        $('#login_acnt_with_otp_card').show().siblings().css('display', 'none');
        $("html, body").animate({
            scrollTop: 0,
        }, 1000);
    });
    $('#login_otp_form').on('submit', function (e) {
        e.preventDefault();
        $this = $(this);
        
        $('#login_otp_form').find('button').attr('disabled', true);
        $.ajax({
            headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            dataType: 'JSON',
            data: $this.serialize(),
            url: $this.attr('action'),
            success: function (response) {
                removeErrorClass('login_otp_form');
                otpVerification();
                $('#login_type').val(response.type);
                $('#user_id').val(response.user);
                $('#response-phone').html(response.phone);
            },
            error: function (response) {
                removeErrorClass('login_otp_form');
                $('#login_otp_form').find('button').attr('disabled', false);
                $('#login_otp_form .error').html('');
                $.each(response.responseJSON.errors, function (key, value) {
                    $('#error-' + key).html(value).show();
                    $(`input[name="${key}"]`).addClass('is-invalid');
                });
            }
        });

        
    }) 
    $('#otp_verification_form').on('submit', function (e) {
        e.preventDefault();
        $this = $(this);
        const phone = $('#phone').val();
        $('#otp_verification_form').find('button').attr('disabled', true);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            dataType: 'JSON',
            data: $this.serialize() + '&phone='+phone,
            url: $this.attr('action'),
            success: function (response) {
                removeErrorClass('otp_verification_form');
                if (response.error) {
                    $('#otp-error-otp').html(response.error).show();
                } else {
                    if (response.intended != null) {
                        window.location.href = response.intended;
                    } else {
                        location.reload();
                    }
                }
            },
            error: function (response) {
                removeErrorClass('otp_verification_form');
                $('#otp_verification_form').find('button').attr('disabled', false);
                $('.error').html('').hide();
                $.each(response.responseJSON.errors, function (key, value) {
                    $('#otp-error-' + key).html(value).show();
                    $(`input[name="${key}"]`).addClass('is-invalid');
                });
            }
        });
    });
});