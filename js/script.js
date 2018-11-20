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
    setByDefualt : function(defaultVariableName, defaultValue) {
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
    getPagesArray : function(pagesCount, pageCurrent, paginationCountOfPagesNearCurrent)
    {
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
$(document).ready(function() {

    $.fn.iNotify = function(message, type) {
        $.fn.myNotify({
            message: message,
            type: type
        });
    }
});
// Функция изменения превью логина и почты пользователя
function jsSetPreviewMameAndEmail() {
    var select = document.getElementById("addTaskSelect");
    // Создаем данные запроса
    data = new FormData();
    data.append("ajax", true);
    data.append("intent", "Get user email");
    data.append("id_user", select.options[select.selectedIndex].value);
    // Выполняем запрос
    handleAjax(0, data, "0-preview");
}

// Функция изменения превью задачи
function jsSetPreviewTask() {taskLenght
    var task = document.getElementById("addTaskTask").value;
    var taskPreview = document.getElementById("previewTask");
    var taskLenght = document.getElementById("taskLenght");
    taskPreview.innerHTML = task.substr(0, taskLenght.innerHTML);
}

// Функция изменения превью изображения задачи
function jsSetPreviewImage() {
    var inputFile = document.getElementById("addTaskImage").files[0];
    var fReader = new FileReader();
    fReader.onloadend = function(event){
        var previewImage = document.getElementById("previewImage");
        previewImage.src = event.target.result;
    }
    // Проверяем введен ли какой-то файл
    if (inputFile) {
        fReader.readAsDataURL(inputFile);
    }
}

// Функция появления элементов редактирования задачи
function jsEditTask(id) {
    document.getElementById(id).style.visibility = "hide";
    var id = id.substr("taskEdit".length, id.length - 1);
    var taskLoadValue = document.getElementById("taskLoadValue" + id);
    var cell = document.getElementById("task" + id);
    cell.innerHTML =
        '<div class="my-inline-parent">' +
            '<div class="my-float-left hidden" id="taskLoadValue' + id + '">' + taskLoadValue.innerHTML + '</div>' +
                '<input id="taskEditInput' + id + '" class="form-control my-inline" style="display: inline-block; ' +
                    'width: 75px;" value="' + taskLoadValue.innerHTML + '">' +
            '<div class="my-inline">' +
                '<div class="btn btn-xs btn-primary" id="taskEditSend' + id + '" onclick="jsEditTaskRun(this.id)">Ok</div>' +
            '</div>' +
            '<div class="my-inline">' +
                '<div class="btn btn-xs btn-danger btn-my-xs" id="taskEditClose' + id +
                    '" onclick="jsEditTaskClose(this.id)">&#x2716;</div>' +
            '</div>' +
            '<div class="my-inline">' +
                '<div class="hidden" id="taskEditWait' + id + '"></div>' +
            '</div>' +
        '</div>';
}

// Функция закрытия элементов редактирования задачи
function jsEditTaskClose(id) {
    var id = id.substr("taskEditClose".length, id.length - 1);
    var taskLoadValue = document.getElementById("taskLoadValue" + id);
    var cell = document.getElementById("task" + id);
    cell.innerHTML =
        '<div class="my-float-left" id="taskLoadValue' + id + '">' + taskLoadValue.innerHTML + '</div>' +
        '<div id="taskEdit' + id + '" class="btn btn-xs btn-success btn-my-xs my-float-right" ' +
                'onclick="jsEditTask(this.id)">' +
            '<div class="glyphicon glyphicon-pencil" style="cursor: pointer;"></div>' +
        '</div>';
}

// Функция отправки AJAX и обработки ответа редактирования задачи
function jsEditTaskRun(id) {
    var id = id.substr("taskEditSend".length, id.length - 1);
    var input = document.getElementById("taskEditInput" + id);
    var taskLoadValue = document.getElementById("taskLoadValue" + id);
    var wait = document.getElementById("taskEditWait" + id);
    // Создаем данные запроса
    data = new FormData();
    data.append("ajax", true);
    data.append("intent", "Admin change task");
    data.append("id_user", id);
    data.append("new_task", input.value);
    // Выполняем запрос
    handleAjax(id, data, "1-task");
}

// Функция появления элементов редактирования выполнения задачи
function jsEditDone(id) {
    document.getElementById(id).style.visibility = "hide";
    var id = id.substr("doneEdit".length, id.length - 1);
    var doneLoadValue = document.getElementById("doneLoadValue" + id);
    var cell = document.getElementById("done" + id);
    cell.innerHTML =
        '<div class="my-inline-parent">' +
            '<div class="my-float-left hidden" id="doneLoadValue' + id + '">' + doneLoadValue.innerHTML + '</div>' +
                '<select id="doneEditSelect' + id + '" class="form-control my-inline" style="display: inline-block; ' +
                    'width: 75px;">' + '<option value="0">Нет</option><option value="1">Да</option>' +
                '</select>' +
            '<div class="my-inline">' +
                '<div class="btn btn-xs btn-primary" id="doneEditSend' + id + '" onclick="jsEditDoneRun(this.id)">Ok</div>' +
            '</div>' +
            '<div class="my-inline">' +
                '<div class="btn btn-xs btn-danger btn-my-xs" id="doneEditClose' + id +
                    '" onclick="jsEditDoneClose(this.id)">&#x2716;</div>' +
            '</div>' +
            '<div class="my-inline">' +
                '<div class="hidden" id="doneEditWait' + id + '"></div>' +
            '</div>' +
        '</div>';
}

// Функция закрытия элементов редактирования выполнения задачи
function jsEditDoneClose(id) {
    var id = id.substr("doneEditClose".length, id.length - 1);
    var doneLoadValue = document.getElementById("doneLoadValue" + id);
    var cell = document.getElementById("done" + id);
    cell.innerHTML =
        '<div class="my-float-left" id="doneLoadValue' + id + '">' + doneLoadValue.innerHTML + '</div>' +
        '<div id="doneEdit' + id + '" class="btn btn-xs btn-success btn-my-xs my-float-right" ' +
                'onclick="jsEditDone(this.id)">' +
            '<div class="glyphicon glyphicon-pencil" style="cursor: pointer;"></div>' +
        '</div>';
}

// Функция отправки AJAX и обработки ответа редактирования выполнения задачи
function jsEditDoneRun(id) {
    var id = id.substr("doneEditSend".length, id.length - 1);
    var select = document.getElementById("doneEditSelect" + id);
    var selectOptionValue = select.options[select.selectedIndex].value;
    var doneLoadValue = document.getElementById("doneLoadValue" + id);
    var wait = document.getElementById("doneEditWait" + id);
    // Создаем данные запроса
    data = new FormData();
    data.append("ajax", true);
    data.append("intent", "Admin change done");
    data.append("id_user", id);
    data.append("new_done", selectOptionValue);
    // Выполняем запрос
    handleAjax(id, data, "2-done");
}

// Функция отображения ожидания запроса
function ajaxWait(id, type) {

    // Определяем тип действия пользователя
    if (type == '0-preview') {
        document.getElementById("previewError").innerHTML = "";
        document.getElementById("previewWait").innerHTML = "Ожидание...";
    } else if (type == '1-task') {
        var wait = document.getElementById("taskEditWait" + id);
        wait.classList.add('show');
        wait.classList.add('text-success');
        wait.classList.add('bg-success');
        wait.classList.remove('text-danger');
        wait.classList.remove('bg-danger');
        wait.classList.remove('hidden');
        wait.innerHTML = "Wait..";
    } else if (type == '2-done') {
        var wait = document.getElementById("doneEditWait" + id);
        wait.classList.add('show');
        wait.classList.add('text-success');
        wait.classList.add('bg-success');
        wait.classList.remove('text-danger');
        wait.classList.remove('bg-danger');
        wait.classList.remove('hidden');
        wait.innerHTML = "Wait..";
    }
}

// Функция отображения результата запроса
function ajaxOk(id, data, type) {

    // Определяем тип действия пользователя
    if (type == '0-preview') {
        var select = document.getElementById("addTaskSelect");
        document.getElementById("previewLogin").innerHTML =
            select.options[select.selectedIndex].text;
        document.getElementById("previewEmail").innerHTML =
            data["response"];
        document.getElementById("previewError").innerHTML = "";
        document.getElementById("previewWait").innerHTML = "";
    } else if (type == '1-task') {
        var taskLoadValue = document.getElementById("taskLoadValue" + id);
        var wait = document.getElementById("taskEditWait" + id);
        taskLoadValue.innerHTML = data["newValue"];
        wait.classList.add('show');
        wait.classList.add('text-success');
        wait.classList.add('bg-success');
        wait.classList.remove('text-danger');
        wait.classList.remove('bg-danger');
        wait.classList.remove('hidden');
        wait.innerHTML = "Ok";
        setTimeout(function() {clearInside("taskEditWait" + id)}, 1000);
    } else if (type == '2-done') {
        var doneLoadValue = document.getElementById("doneLoadValue" + id);
        var wait = document.getElementById("doneEditWait" + id);
        doneLoadValue.innerHTML = data["newValue"] == 1 ? "Да" : "Нет";
        wait.classList.add('show');
        wait.classList.add('text-success');
        wait.classList.add('bg-success');
        wait.classList.remove('text-danger');
        wait.classList.remove('bg-danger');
        wait.classList.remove('hidden');
        wait.innerHTML = "Ok";
        setTimeout(function() {clearInside("doneEditWait" + id)}, 1000);
    }
}

// Функция отображения ошибки запроса
function ajaxError(id, data, type) {

    // Определяем тип действия пользователя
    if (type == '0-preview') {
        document.getElementById("previewError").innerHTML = data["error"];
        document.getElementById("previewWait").innerHTML = "";
    } else if (type == '1-task') {
        var taskLoadValue = document.getElementById("taskLoadValue" + id);
        var wait = document.getElementById("taskEditWait" + id);
        wait.classList.add('show')
        wait.classList.add('text-danger');
        wait.classList.add('bg-danger');
        wait.classList.remove('text-success');
        wait.classList.remove('bg-success');
        wait.classList.remove('hidden');
        wait.innerHTML = data["error"];
        setTimeout(function() {clearInside("taskEditWait" + id)}, 5000);
    } else if (type == '2-done') {
        var wait = document.getElementById("doneEditWait" + id);
        wait.classList.add('show')
        wait.classList.add('text-danger');
        wait.classList.add('bg-danger');
        wait.classList.remove('text-success');
        wait.classList.remove('bg-success');
        wait.classList.remove('hidden');
        wait.innerHTML = data["error"];
        setTimeout(function() {clearInside("doneEditWait" + id)}, 5000);
    }
}

// Функция отправки AJAX запроса
function handleAjax(id, data, type) {
    var xhr = new XMLHttpRequest();
    xhr.open("post", '/', true);
    xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 400) {
            // Все хорошо
            try {
                var text = xhr.responseText;//data['result'];
                text = text.substr(0, text.indexOf("skoroid_boundary_123sodjfoi4923jsdsjd0"));
                text = JSON.parse(text);
                if (text["error"] == null) {
                    ajaxOk(id, text, type);
                } else {
                    // Сервер вернул ошибку обработки запроса
                    ajaxError(id, {error: text["response"]}, type);
                }
            } catch (e) {
                // Ошибка обработки try
                ajaxError(id, {error: 'Error ' + e.name + ":" + e.message}, type);
            }
        } else {
            // Сервер вернул ошибку
            ajaxError(id, {error: xhr.status + " " + xhr.statusText}, type);
        }
    }
    xhr.onerror = function() {
        // Ошибка осуществления запроса
        ajaxError(id, {error: xhr.getErrorMessage}, type);
    }
    ajaxWait(id, type);
    xhr.send(data);
}

// Функция очищает содержимое блока
function clearInside(id) {
    var item = document.getElementById(id);
        item.innerHTML = "";
}