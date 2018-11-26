/**
 * Created by Skoroid on 26.11.2018.
 */

$(document).ready(function() {
    $('form div > button').on('click', function(e){
        e.preventDefault();
        login();
    });
});

function login() {
    var errorMessage = 'Ошибка авторизации: ';
    var $buttonSubmit = $('form > div:nth-last-child(1)');
    if ($('.js-response')[0] === undefined)
        $buttonSubmit.before(
            '<div class="form-group">' +
                '<div class="col-sm-12 text-center js-response"></div>' +
            '</div>'
        );
    $('.js-response').text('');
    $('.js-response').append('<div class="fas fa-spinner fa-spin my-spinner js-login-block-spinner"></div>');

    $.ajax({
        url: document.location.origin + '/login/json',
        method: 'post',
        data: {
            intent:   'Get login',
            login:    $('form input[name="login"]').val(),
            password: $('form input[name="password"]').val(),
        },
        dataType: 'json',
        async: false,
        complete: function () {
        },
        error: function (xhr, status, error) {
            $.fn.iNotify(errorMessage + status, 'warning');
            $('body').append(xhr.responseText);
        },
        success: function (json) {
            if (json.error) {
                $('.js-login-block-spinner').remove();
                $.fn.iNotify(errorMessage + json.response, 'warning');
                $('body').append(errorMessage + json.response);
                return;
            }

            $('.js-login-block-spinner').remove();

            var cookies = json.response.cookies;
            $.each(cookies, function(index, value) {
                window.cookies.set(index, value['value'], value['value'] / 8600, value['path']);
            });

            $('.js-response').text(json.response.text);
            $('.js-response').addClass('text-success');
        }
    });
}