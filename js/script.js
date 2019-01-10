/**
 * Created by Skoroid on 08.07.2017.
 */

String.prototype.isEmpty = function() {
    return (this.length === 0 || !this.trim());
};

Object.size = function(obj) {
    var size = 0, key;
    for (key in obj) {
        if (obj.hasOwnProperty(key)) size++;
    }
    return size;
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
    getVariableUnderlineNameFromCamelCase : function(variableName, separator = '_') {
        variableName = this.lowerFirstLetter(variableName);
        return variableName.replace(/([A-Z])/g, separator + '$1').toLowerCase();
    },
    lowerFirstLetter(string) {
        return string.charAt(0).toLowerCase() + string.slice(1);
    },
    upperFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
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
    },
    checkNotEmptyObject(object, objectName = undefined) {
        if (typeof object === 'object' && Object.size(object) > 0)
            return true;

        if (objectName !== undefined)
            console.log(objectName + ' is empty object');
        return false;
    },
    checkNotEmptyArray(array, arrayText = undefined) {
        if (typeof array === 'object' && Array.isArray(array) && array.length > 0)
            return true;

        if (arrayText !== undefined)
            console.log(arrayText + ' is empty array');
        return false;
    },
    checkNotEmptyValue(value, valueText = undefined) {
        if (value === undefined || value === null || value === '') {
            if (valueText !== undefined)
                console.log(valueText + ' value is empty');
            return false;
        }
        return true;
    },
    checkPermissionCrud(permissionCrud) {
        if (window.helper.permissionsCrud.indexOf(permissionCrud) !== -1)
            return true;

        console.log('Permission CRUD is wrong');
        return false;
    },
    checkNotEmptyString(str) {
        if (typeof str === 'string' && !str.isEmpty())
            return true;
        return false;
    },
    checkDomElementTextContent(element) {
        if (this.checkNotEmptyString($(element).text()) && $(element).text() === $(element).html())
            return true;
        return false;
    },
    checkIntPositiveString(str) {
        var n = Math.floor(Number(str));
        return n !== Infinity && String(n) === str && n >= 0;
    }
};

window.helper.permissionsCrud = ['create', 'read', 'update', 'delete'];

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
    },
    getSpinner : function() {
        return $(
            '<div class="text-center js-spinner">' +
                '<i class="fas fa-spinner fa-spin my-spinner">' +
                '</i>' +
            '</div>'
        );
    },
    handleCheckboxCheckedByParentClick($mainCheckboxSelector, $dependentCheckboxesSelector) {
        var changeDependentCheckboxesCallback = function($mainCheckbox, isSelfChecked, isChecked) {
            $mainCheckbox.checked = isSelfChecked;
            if ($dependentCheckboxesSelector !== undefined)
                $($dependentCheckboxesSelector).each(function(id, element) {
                    element.checked = isChecked;
                });
        };
        $mainCheckboxSelector.on('click', function() {
            this.checked = !this.checked;
        });
        $mainCheckboxSelector.parent().on('click', function() {
            var $mainCheckbox = $mainCheckboxSelector[0];
            changeDependentCheckboxesCallback($mainCheckbox, !$mainCheckbox.checked, !$mainCheckbox.checked);
        });
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
        isFormData = false,
        isAsync = true,
        isDebug = false,
        errorJsonCallback = undefined,
        errorCallback = undefined
    ) {

        if (responseTextSelector) $(responseTextSelector).text('');
        if (spinnerSelector) {
            $(spinnerSelector).append(window.pageBuilder.getSpinner());
            var $spinner = $(spinnerSelector).find('.js-spinner');
        }

        errorMessage = errorMessage ? errorMessage += ': ' : '';
        var ajaxObject = {
            url:      document.location.origin + path,
            method:   'post',
            data:     data,
            dataType: 'json',
            async:    isAsync,
            complete: function () {
                if (typeof completeCallback === 'function')
                    completeCallback();
            },
            error: function (xhr, status, error) {
                if ($spinner) $spinner.remove();

                if (isDebug) $('body').append(errorMessage + status + xhr.responseText);

                typeof errorCallback === 'function'
                    ? errorCallback(xhr, status, error)
                    : $.fn.iNotify(errorMessage + status, 'warning');
            },
            success: function (json) {
                if ($spinner) $spinner.remove();

                if (json.error) {
                    if (isDebug) $('body').append(errorMessage + json.response);

                    if (typeof errorJsonCallback === 'function') {
                        var isReturn = errorJsonCallback(json);
                        if (isReturn) return;
                    }
                    $.fn.iNotify(errorMessage + json.response, 'warning');
                    return;
                }

                if (typeof successCallback === 'function') {
                    var isReturn = successCallback(json);
                    if (isReturn) return;
                }

                if (responseTextSelector)
                    json.response === undefined
                        ? $.fn.iNotify(errorMessage + 'No response', 'warning')
                        : (json.response.text === undefined
                            ? $.fn.iNotify(errorMessage + 'No text field in response', 'warning')
                            : $(responseTextSelector).text(json.response.text).addClass('text-success'));
            }
        }
        if (isFormData) {
            ajaxObject.processData = !isFormData;
            ajaxObject.contentType = !isFormData;
        }
        $.ajax(ajaxObject);
    },
    checkJson(json) {
        if (!window.helper.checkNotEmptyObject(json, 'Json'))
            return false;

        if (!window.helper.checkNotEmptyValue(json.response, 'Json'))
            return false;

        return true;
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
        z_index: 1500,
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