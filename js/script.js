/**
 * Created by Skoroid on 08.07.2017.
 */

String.prototype.isEmpty = function() {
    return (this.length === 0 || !this.trim());
};

window.cookies = {
    getNameFromDefaultVariableName : function(defaultCookie) {
        return defaultCookie.slice(defaultCookie.indexOf('DEF_') + 'DEF_'.length);
    },
    get : function(name) {
        var matches = document.cookie.match(new RegExp(
            "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
        ));
        return matches ? decodeURIComponent(matches[1]) : undefined;
    },
    getSession : function(name) {
        name = 'SESSION_' + name;
        return window.cookies.get(name);
    },
    getDefault : function(defaultCookie) {
        return window.cookies.getSession(window.cookies.getNameFromDefaultVariableName(defaultCookie));
    },
    set : function(cookieName, cookieValue, exdays) {
        var d = new Date();
        if (exdays === undefined) exdays = 1000;
        d.setTime(d.getTime() + (exdays*24*60*60*1000));
        var expires = "expires="+d.toUTCString();
        document.cookie = cookieName + '=' + cookieValue + '; ' + expires + ';path=/';
    },
    setSession : function(cookieName, cookieValue, exdays) {
        cookieName = 'SESSION_' + cookieName;
        window.cookies.set(cookieName, cookieValue, exdays);
    },
    setByDefault : function(defaultVariableName, defaultValue) {
        var isThisDefualtConst = window.helper.isDefualtConst(defaultVariableName);
        if (!isThisDefualtConst)
            window.cookies.setSession(window.cookies.getNameFromDefaultVariableName(defaultVariableName), defaultValue);
    }
};

window.helper = {
    isDefualtConst : function(defaultVariableName) {
        return defaultVariableName.indexOf('DEF_CONST_') === -1 ? false : true;
    },
    getVariableCamelCaseNameFromUpperCaseAndUnderline : function(variableName, ignoredValue) {
        var splitted = variableName.split('_');
        var result = '';
        var isFirst = true;
        splitted.forEach(function(value){
            if (ignoredValue !== null && value === ignoredValue) return;
            value = value.toLowerCase();
            if (!isFirst) value = value.charAt(0).toUpperCase() + value.slice(1);
            result += value;
            isFirst = false;
        });
        return result;
    },
    getObjectVariableNameBySessionCookie : function(cookieVariableName) {
        return window.helper.getVariableCamelCaseNameFromUpperCaseAndUnderline(cookieVariableName, null);
    },
    getObjectVariableNameByDefault : function(defaultVariableName) {
        return window.helper.getVariableCamelCaseNameFromUpperCaseAndUnderline(defaultVariableName, 'DEF');
    },
    ksort : function(obj){
        var keys = Object.keys(obj).sort()
            , sortedObj = {};

        for(var i in keys) {
            sortedObj[keys[i]] = obj[keys[i]];
        }

        return sortedObj;
    },
    changeBadge : function(e, trueValue, falseValue) {
        var $element = $(e);
        if ($element.hasClass('badge-success')) {
            $element
                .removeClass('badge-success')
                .addClass('badge-secondary')
                .text(falseValue);
            return;
        }
        $element
            .removeClass('badge-secondary')
            .addClass('badge-success')
            .text(trueValue);
    },
    isBadge : function(e) {
        return $(e).hasClass('badge-success');
    },
    buildIdAssociateObjectFromIndexByIds : function() {
        // TODO
    }
};

window.pageBuilder = {
    getPagesArray : function(pagesCount, pageCurrent, paginationCountOfPagesNearCurrent) {
        if (typeof pagesCount === 'string') pagesCount = parseInt(pagesCount);
        if (typeof pageCurrent === 'string') pageCurrent = parseInt(pageCurrent);
        if (typeof paginationCountOfPagesNearCurrent === 'string') paginationCountOfPagesNearCurrent = parseInt(paginationCountOfPagesNearCurrent);
        var pagesArray = {};
        var count = ( 1 + 2 * paginationCountOfPagesNearCurrent ) + 4;

        // Троеточие с обеих сторон
        if (pagesCount > count) {
            if ((pageCurrent - paginationCountOfPagesNearCurrent - 2) > 1 && (pageCurrent + paginationCountOfPagesNearCurrent + 2) < pagesCount) {
                    pagesArray[0] = 1;
                    pagesArray[1] = "...";
                    pagesArray[count - 2] = "...";
                    pagesArray[count - 1] = pagesCount;
                for (i = 2; i < count - 2; i++) {
                        pagesArray[i] = pageCurrent - (paginationCountOfPagesNearCurrent + 2) + i;
                }
                // Троеточие слева
            } else if ((pageCurrent - paginationCountOfPagesNearCurrent - 2) > 0) {
                    pagesArray[0] = 1;
                    pagesArray[1] = "...";
                    pagesArray[count - 1] = pagesCount;
                var centerPage = pagesCount - (paginationCountOfPagesNearCurrent + 2);
                for (var i = 2; i < count - 1; i++) {
                        pagesArray[i] = centerPage - (paginationCountOfPagesNearCurrent + 2) + i;
                }
                // Троеточие справа
            } else if ((pageCurrent + paginationCountOfPagesNearCurrent + 2) < pagesCount) {
                    pagesArray[0] = 1;
                    pagesArray[count - 2] = "...";
                    pagesArray[count - 1] = pagesCount;
                var centerPage = paginationCountOfPagesNearCurrent + 2 + 1;
                for (var i = 1; i < count - 2; i++) {
                    pagesArray[i] = centerPage - (paginationCountOfPagesNearCurrent + 2) + i;
                }
            }
        } else {

            // Нет троеточия
            for (var i = 1; i <= pagesCount; i++) {
                pagesArray[i] = i;
            }
        }
        window.helper.ksort(pagesArray);
        return pagesArray;
    }
};

window.ajax = {
    run : function(
        path,
        data,
        successCallback,
        completeCallback,
        errorMessage = undefined,
        responseTextSelector = undefined,
        spinnerSelector = undefined,
        async = true,
        debug = false,
        errorJsonCallback = undefined,
        errorCallback = undefined
    ) {

        if (responseTextSelector) $(responseTextSelector).text('');
        if (spinnerSelector) {
            $(spinnerSelector).append('<div class="fas fa-spinner fa-spin my-spinner js-block-spinner"></div>');
            var $spinner = $(spinnerSelector).find('.js-block-spinner');
        }

        errorMessage = errorMessage ? errorMessage += ': ' : '';
        $.ajax({
            url: document.location.origin + path,
            method: 'post',
            data: data,
            dataType: 'json',
            async: async,
            complete: function () {
                completeCallback()
            },
            error: function (xhr, status, error) {
                if ($spinner) $spinner.remove();

                typeof errorCallback === 'function'
                    ? errorCallback(xhr, status, error)
                    : $.fn.iNotify(errorMessage + status, 'warning');
                if (debug) $('body').append(errorMessage + status);
            },
            success: function (json) {
                if ($spinner) $spinner.remove();

                if (json.error) {
                    typeof errorJsonCallback === 'function'
                        ? errorJsonCallback(json)
                        : $.fn.iNotify(errorMessage + json.response, 'warning');

                    if (debug) $('body').append(errorMessage + json.response);
                    return;
                }

                if (typeof successCallback === 'function') {
                    successCallback(json);
                    return;
                }

                if (responseTextSelector)
                    $(responseTextSelector).text(json.response.text).addClass('text-success');
            }
        });
    }
}

$.fn.myNotify = function(params) {
    $.notify({
        // options
        icon: 'glyphicon glyphicon-warning-sign',
        //title: '',
        message: params.message,
        //url: '',
        target: '_blank'
    },{
        // settings
        element: 'body',
        position: null,
        type: params.type,
        allow_dismiss: true,
        newest_on_top: false,
        showProgressbar: false,
        placement: {
            from: "top",
            align: "right"
        },
        offset: 20,
        spacing: 10,
        z_index: 1100,
        delay: 5000,
        timer: 1000,
        url_target: '_blank',
        mouse_over: null,
        animate: {
            enter: 'animated fadeInDown',
            exit: 'animated fadeOutUp'
        },
        onShow: null,
        onShown: null,
        onClose: null,
        onClosed: null,
        icon_type: 'class',
        template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
        '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
        '<span data-notify="icon"></span> ' +
        '<span data-notify="title">{1}</span> ' +
        '<span data-notify="message">{2}</span>' +
        '<div class="progress" data-notify="progressbar">' +
        '<div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>' +
        '</div>' +
        '<a href="{3}" target="{4}" data-notify="url"></a>' +
        '</div>'
    });
};

$.urlParam = function(name){
    var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
    if (results==null) {
        return null;
    }
    return decodeURI(results[1]) || 0;
}

$(document).ready(function() {

    $.fn.iNotify = function(message, type) {
        $.fn.myNotify({
            message: message,
            type: type
        });
    }
});