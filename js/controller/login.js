/**
 * Created by Skoroid on 26.11.2018.
 */

$(document).ready(function() {

    $('form div > a').on('click', function(e){
        e.preventDefault();
        var modalClass = 'js-modal-create-new-password-request';
        createModalNewPasswordRequestCreation(
            modalClass,
            'Запрос изменения пароля',
            'Отправить запрос'
        );
        fillModal(modalClass);
    });

    $('form div > button').on('click', function(e){
        e.preventDefault();
        $(e.target).attr('disabled', 'disabled');
        var data = prepareData();
        loginAjax(
            data,
            function(json) {
                var cookies = json.response.cookies;
                $.each(cookies, function(index, value) {
                    window.cookies.set(index, value['value'], value['value'] / 8600, value['path']);
                });

                $('.js-response').text(json.response.text);
                $('.js-response').addClass('text-success');

                $('form input[name="login"]').val('');
                $('form input[name="password"]').val('');

                setTimeout(function() {
                    $('.js-response').text('');
                    $('.js-response').append('<div class="fas fa-spinner fa-spin my-spinner js-login-block-spinner"></div>');
                    setTimeout(function() {
                        document.location = document.location.origin;
                    }, 1000)
                }, 1000)
                return true;
            },
            function() {
                $(e.target).attr('disabled', false);
            },
            'Ошибка авторизации',
            '.js-response');
    });
});

function createModalNewPasswordRequestCreation(className, title, action) {
    var $modal = $(
        '<div class="modal fade show ' + className + '" tabindex="-1" role="dialog"' +
        'aria-labelledby="exampleModalLongTitle" aria-hidden="true" style="display: block; padding-right: 17px;">' +
            '<div class="modal-dialog" role="document">' +
                '<div class="modal-content">' +
                    '<div class="modal-header">' +
                        '<h5 class="modal-title">' + title + '</h5>' +
                        '<button type="button" class="close" data-dismiss="modal" aria-label="Close">' +
                            '<span aria-hidden="true">&times;</span>' +
                        '</button>' +
                    '</div>' +
                    '<div class="modal-body">' +
                    '</div>' +
                    '<div class="modal-footer">' +
                        '<button type="button" class="btn btn-primary js-modal-action">' + action + '</button>' +
                    '</div>' +
                '</div>' +
            '</div>' +
        '</div>' +
        '<div class="modal-backdrop fade show"></div>'
    );
    $modal.find('.modal-header button').on('click', function(){
        $modal.remove();
    });
    $('body').append($modal);
}

function fillModal(classModal) {
    var $modalBody = $('.' + classModal + ' .modal-content .modal-body');
    $modalBody.append(
        '<div>' +
            '<label class="control-label col-sm-3">Пользователь</label>' +
            '<div class="js-login">' +
                '<input class="form-control" type="text" placeholder="Логин">' +
            '</div>' +
        '</div>' +
        '<div>' +
            '<div class="col-sm-12 text-center js-response-modal"></div>' +
        '</div>'
    );

    var $modalFooterButton = $('.' + classModal + ' .modal-content .modal-footer button');
    $modalFooterButton.on('click', function(e){
        $(e.target).attr('disabled', 'disabled');
        loginAjax(
            prepareData(true),
            undefined,
            function() {
                $(e.target).attr('disabled', false);
            },
            'Ошибка запроса пароля',
            '.js-response-modal');
    });
}

function prepareData(isCreateNewPasswordRequest = false) {
    return isCreateNewPasswordRequest
        ? {
            intent:   'Create new password request',
            login:    $('.js-login input').val(),
        }
        : {
            intent:   'Get login',
            login:    $('form input[name="login"]').val(),
            password: $('form input[name="password"]').val(),
        };
}

function loginAjax(data, successFunction, completeFunction, error, responseTextAndSpinnerSelector) {
    prepareAnswerContainer();
    window.ajax.run(
        '/login/json',
        data,
        successFunction,
        completeFunction,
        error,
        responseTextAndSpinnerSelector,
        responseTextAndSpinnerSelector
    );
}

function prepareAnswerContainer() {
    var $buttonSubmit = $('form > div:nth-last-child(1)');
    if ($('.js-response')[0] === undefined)
        $buttonSubmit.before(
            '<div class="form-group">' +
                '<div class="col-sm-12 text-center js-response"></div>' +
            '</div>'
        );
}