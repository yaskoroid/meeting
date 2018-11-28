/**
 * Created by Skoroid on 26.11.2018.
 */

$(document).ready(function() {
    $('form div > button').on('click', function(e){
        e.preventDefault();
        $('form div > button').attr('disabled', 'disabled');
        var isCancel = $(e.target).attr('type') === 'cancel';
        var error = isCancel ? 'Ошибка отмены' : 'Ошибка подтверждения';
        var data = prepareData(isCancel);
        userCreationConfirmaionAjax(data, error);
    });
});

function prepareData(cancel = false) {
    var data = {
        intent:      window['ACTION_INTENT'],
        hash:        $.urlParam('hash')
    };

    $.each($('form input'), function() {
        data[$(this).attr('name')] = $(this).val();
    });

    if (cancel)
        data['cancel'] = true;

    return data;
}

function userCreationConfirmaionAjax(data, error) {
    prepareAnswerContainer();
    window.ajax.run(
        '/confirm/json',
        data,
        undefined,
        function() {
            $('form div > button').attr('disabled', false);
        },
        error,
        '.js-response',
        '.js-response',
        true
    );
}

function prepareAnswerContainer() {
    var $buttonSubmit = $('form > div:nth-last-child(2)');
    if ($('.js-response')[0] === undefined)
        $buttonSubmit.before(
            '<div class="form-group">' +
                '<div class="col-sm-12 text-center js-response"></div>' +
            '</div>'
        );
}