/**
 * Created by Skoroid on 30.10.2018.
 */
$(document).ready(function() {

    //actualFilterState();

    class User {
        constructor(firstName, lastName) {
            this.usersCountOnPage = this.getDefaultOrSessionCookie('DEF_USERS_COUNT_ON_PAGE');
            this.constUsersCountOnPageValues = this.getDefaultOrSessionCookie('DEF_CONST_USERS_COUNT_ON_PAGE_VALUES');
            this.usersSorting = window.cookies.getSession('USERS_SORTING');
            this.sortingDirection = this.getDefaultOrSessionCookie('DEF_SORTING_DIRECTION');
            this.usersSearchText = window.cookies.getSession('USERS_SEARCH_TEXT');
            this.pageNumber = this.getDefaultOrSessionCookie('DEF_PAGE_NUMBER');
            this.constImageUserPath = this.getDefaultOrSessionCookie('DEF_CONST_IMAGE_USER_PATH');
            this.usersCount = 0;
            this.foundedUsersData = undefined;
            this.storeUserData = {};
            this.lastSearchText = this.usersSearchText;
            this.permissions = {};
            this.permissions.create = [];
            this.permissions.users = [];

            this.create();
        }

        setUsersObjectProperyAndSessionCookieByDefault(defaultVariableName, value) {
            var variable = window.helper.getObjectVariableNameByDefault(defaultVariableName);
            document.users[variable] = value;
            window.cookies.setByDefault(defaultVariableName, document.users[variable])
        }
        setUsersObjectProperyAndSessionCookieByCookie(cookieVariableName, value) {
            var variable = window.helper.getObjectVariableNameBySessionCookie(cookieVariableName);
            document.users[variable] = value;
            window.cookies.setSession(cookieVariableName, document.users[variable])
        }
        getDefaultOrSessionCookie(defaultVariableName) {
            var cookie = window.cookies.getDefault(defaultVariableName);
            if (cookie === undefined) {
                window.cookies.setByDefault(defaultVariableName, window[defaultVariableName]);
                return window[defaultVariableName];
            }
            return cookie;
        }
        createSearchBlock() {
            var self = this;
            var $jsMainForm = $('.js-main-form');
            $jsMainForm.append('<!-- Блок с поиском -->');

            var div = document.createElement('div');
            div.classList= 'table-responsive js-search-block';

            var table = document.createElement('table');
            table.classList = 'table table-striped table-hover';

            var tbody = document.createElement('tbody');

            var tr = document.createElement('tr');

            var td = document.createElement('td');

            var input = document.createElement('input');
            input.className = 'form-control js-users-search';
            input.setAttribute('name', 'usersSearch');
            input.setAttribute('type', 'text');
            input.setAttribute('placeholder', 'Поиск...');
            input.setAttribute('value', this.usersSearchText === undefined ? '' : this.usersSearchText);
            $(input).on('input', function(e) {
                self.setUsersObjectProperyAndSessionCookieByCookie('USERS_SEARCH_TEXT', $(e.target).val());
                self.setUsersObjectProperyAndSessionCookieByDefault('DEF_PAGE_NUMBER', 1);
                var isNeedLoadSearch = self.usersCount > 0 ||
                    e.target.value.length <= self.lastSearchText.length ||
                    e.target.value.substr(0, self.lastSearchText.length) !== self.lastSearchText;
                if (isNeedLoadSearch) {
                    self.reloadSearch();
                }

                self.lastSearchText = e.target.value;
            });

            td.appendChild(input);
            tr.appendChild(td);

            td = document.createElement('td');

            var select = document.createElement('select');
            select.classList = 'form-control js-users-sorting-select';

            td.appendChild(select);
            tr.appendChild(td);

            td = document.createElement('td');

            var buttonUp = document.createElement('button');
            buttonUp.setAttribute('data-type', 'asc');
            buttonUp.classList = 'btn btn-xs btn-primary my-inline js-users-sort-up';
            buttonUp.innerText = '▲';
            $(buttonUp).on('click', function(e) {
                e.preventDefault();
                self.setUsersObjectProperyAndSessionCookieByDefault(
                    'DEF_SORTING_DIRECTION',
                    $(e.target).attr('data-type')
                );
                $(e.target).blur();
                self.reloadSearch();

            });
            var buttonDown = document.createElement('button');
            buttonDown.setAttribute('data-type', 'desc');
            buttonDown.classList = 'btn btn-xs btn-primary my-inline js-users-sort-down';
            buttonDown.innerText = '▼';
            $(buttonDown).on('click', function(e) {
                e.preventDefault();
                self.setUsersObjectProperyAndSessionCookieByDefault(
                    'DEF_SORTING_DIRECTION',
                    $(e.target).attr('data-type')
                );
                $(e.target).blur();
                self.reloadSearch();
            });

            td.appendChild(buttonUp);
            td.appendChild(buttonDown);

            tr.appendChild(td);

            td = document.createElement('td');
            var select = document.createElement('select');
            select.classList = 'form-control';

            var options = this.constUsersCountOnPageValues.split(',');
            var $select = $(select);
            options.forEach(function(value){
                self.createSelectOptions($select, value, value, self.usersCountOnPage);
            });

            $select.on('change', function(e){
                self.setUsersObjectProperyAndSessionCookieByDefault('DEF_USERS_COUNT_ON_PAGE', $(e.target)
                    .find('option:selected').val());
                self.setUsersObjectProperyAndSessionCookieByDefault('DEF_PAGE_NUMBER', 1);
                self.reloadSearch();
            });

            td.appendChild(select);
            tr.appendChild(td);

            td = document.createElement('td');
            td.classList = 'js-search-block-spinner';
            var i = document.createElement('i');
            i.classList = 'fas fa-spinner fa-spin my-spinner';

            td.appendChild(i);
            tr.appendChild(td);
            tbody.appendChild(tr);
            table.appendChild(tbody);
            div.appendChild(table);
            $jsMainForm.append($(div));

            this.getSortingFields();
        }
        reloadSearch(){
            var $usersBlock = $('.js-main-form .js-users-block');
            $usersBlock.before().remove()
            $usersBlock.remove();

            var $createBlock = $('.js-main-form .js-users-create-block');
            $createBlock.before().remove()
            $createBlock.remove();

            var $pagesBlock = $('.js-main-form .js-users-pages-block');
            $pagesBlock.before().remove()
            $pagesBlock.remove();

            this.createUsersBlock();
        }
        createUsersBlock() {
            $('.js-main-form').append(
                '<!-- Блок с пользователями -->' +
                '<div class="table-responsive js-users-block">' +
                '<table class="table table-striped table-hover">' +
                '<tbody>' +
                '<tr class="js-users-block-spinner">' +
                '<td class="text-center">' +
                '<i class="fas fa-spinner fa-spin my-spinner">' +
                '</i>' +
                '</td>' +
                '</tr>' +
                '</tbody>' +
                '</table>' +
                '</div>'
            );

            this.getUsersBySearch();
        }
        createCreateBlock() {
            if (this.permissions.create !== []) {
                $('.js-main-form').append(
                    '<!-- Блок добавления пользователя -->' +
                    '<div class="table-responsive js-users-create-block text-center">' +
                    '<button class="btn btn-success js-user-create-button">Добавить пользователя</button>' +
                    '</div>'
                );
                var self = this;
                $('.js-user-create-button').on('click', function (e) {
                    var className = 'js-user-create-modal';
                    self.createStoreModal(className, 'Создать пользователя', 'Создать');
                    self.fillStoreModal(className);
                    self.fillUserTabStoreFields(className);

                    $('div.modal.fade.show.' + className + ' div.modal-footer .js-modal-action')
                        .on('click', function (e) {
                            self.storeUser(className, null);
                        }
                    );
                    e.preventDefault();
                });
            }
        }
        createPagesBlock() {
            var self = this;
            var $jsMainForm = $('.js-main-form');
            if (this.usersCount == 0 || this.usersCount == undefined) return;
            $('.js-main-form').append(
                '<!-- Блок со страницами выдачи -->' +
                '<div class="text-center js-users-pages-block">' +
                    '<span>Страница <span class="js-page"></span></span>' +
                    '<!-- Навигация (пагинация) -->' +
                    '<nav class="text-center" aria-label="Page navigation">' +
                    '<ul class="pl-0 text-center js-users-pages-pagination"></ul>' +
                    '</nav>' +
                '</div>'
            );

            $('.js-users-pages-block .js-page').text(this.pageNumber);
            var $ul = $('.js-users-pages-block .js-users-pages-pagination');

            var pagesArray = window.pageBuilder.getPagesArray(
                Math.ceil(this.usersCount / this.usersCountOnPage),
                this.pageNumber,
                this.getDefaultOrSessionCookie('DEF_CONST_PAGINATION_COUNT_OF_PAGES_NEAR_CURRENT')
            );

            $.each(pagesArray, function(index, page) {
                var disabledState = parseInt(page) === parseInt(self.pageNumber) ? ' disabled' : '';
                $ul.append(
                    $(document.createElement('li'))
                        .append(
                            '<button class="btn btn-xs ml-1 mr-1"' + disabledState + '></button>'
                        )
                        .find('button')
                        .text(page)
                        .addClass(page === '...' ? ' btn-light' : ' btn-primary')
                        .on('click', function(e){
                            self.setUsersObjectProperyAndSessionCookieByDefault('DEF_PAGE_NUMBER', $(e.target).text());
                            self.reloadSearch();
                            e.preventDefault();
                        })
                );
            });
        }
        createUsers(users) {
            var self = this;
            $.each(self.foundedUsersData, function(index, user){
                self.createUserTab(user);
                $('.js-usertab-' + user['id']).html(self.getUserTab());
                self.fillUserTabFieldsToShow(user);

                /*self.createUserTabFieldsToChange(user);
                self.fillUserTabFieldsToChange(user);*/
            });
        }
        createUserTab(user) {
            var tr = document.createElement('tr');
            var td = document.createElement('td');

            var div = document.createElement('div');
            div.classList = 'js-usertab-' + user['id'];
            td.appendChild(div);
            tr.appendChild(td);

            $('.js-users-block table tbody').append($(tr));
        }
        getUserTab() {
            var retult =
            '<div class="card">' +
                '<div class="card-header">' +
                    '<div>' +
                        '<div class="float-left js-login"></div>' +
                        '<div class="float-right js-sex"></div>' +
                    '</div>' +
                '</div>' +
                '<div class="card-body">' +
                    '<div class="media">' +
                        '<div style="width: 250px;">' +
                            '<div class="media js-image">' +
                                 '<img onerror="this.onerror=null;this.src=\'' + this.constImageUserPath + '0.jpg\';" class="img-thumbnail rounded float-left" src="">' +
                            '</div>' +
                        '</div>' +
                        '<div class="media-body ml-3">' +
                            '<div>' +
                                '<div>' +
                                    '<div class="card-title">' +
                                        '<span class="font-weight-bold js-name"></span> ' +
                                        '<span class="font-weight-bold js-surname"></span>' +
                                    '</div>' +
                                    '<p class="card-text js-comment"></p>' +
                                '</div>' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                    '<div class="w-100 p-2 float-right">' +
                        '<div class="mr-1 badge badge-success js-is_ready">Готов</div>' +
                        '<div class="mr-1 badge badge-success js-is_ready_only_for_partnership">Готов для задания</div>' +
                    '</div>' +
                '</div>' +
                '<div class="card-footer">' +
                    '<div class="float-left js-phone"></div>' +
                    '<div class="float-right js-email"></div>' +
                '</div>' +
            '</div>';
            return retult;
        }
        fillUserTabFieldsToShow(user) {
            var $tab = $('.js-usertab-' + user['id']);

            if (this.permissions.users[user.id]['update'] === true) {
                var self = this;
                $tab.hover(function(){
                    $tab.prepend(
                        '<div class="js-user-store-container" style="position:relative;">' +
                            '<button class="btn btn-xs btn-success fas fa-pencil-alt js-user-store" style="position:absolute; top: 12px; right: 12px; z-index:1300;"></button>' +
                        '</div>'
                    )
                    .find('.js-user-store')
                    .on('click', function(e) {
                        $('.js-user-store-container').remove();
                        var className = 'js-user-store-modal-' + user['id'];
                        var secondAction = self.permissions.users[user.id]['delete'] === true ? 'Удалить' : null;
                        self.createStoreModal(className, 'Редактировать', 'Изменить', secondAction);
                        self.fillStoreModal(className);
                        self.fillUserTabStoreFields(className);
                        self.fillUserTabStoreRealValues(className, user);
                        e.preventDefault();
                    });
                }, function(){
                    $tab.find('.js-user-store-container').remove();
                });
            }

            $tab.find('.js-image img').attr('src', this.constImageUserPath + user['id'] + '.' + user['ext']);
            $tab.find('.js-name').text(user['name']);
            $tab.find('.js-surname').text(user['surname']);
            $tab.find('.js-email').text(user['email']);
            $tab.find('.js-login').text(user['login']);
            if (parseInt(user['is_ready']) !== 1)
                window.helper.changeBadge($tab.find('.js-is_ready'), 'Готов', 'Не готов');
            if (parseInt(user['is_ready_only_for_partnership']) !== 1)
                window.helper.changeBadge($tab.find(
                    '.js-is_ready_only_for_partnership'),
                    'Готов для задания',
                    'Только партнер'
                );
            $tab.find('.js-comment').text(user['comment']);
            $tab.find('.js-sex').text(user['sex'] == 1 ? 'Мужчина' : 'Женщина');
            $tab.find('.js-phone').text(user['phone']);
        }
        fillStoreModal(className) {
            $('.' + className + ' .modal-body').append(
                this.getUserTab()
            )
        }
        fillUserTabStoreFields(className) {
            var $tab = $('div.modal.fade.show.' + className + ' > div > div > div.modal-body > div');

            $tab.find('.js-login').html('<input class="form-control" type="text" data-start-value="" placeholder="Логин">');
            $tab.find('.js-login').after('<span class="fas" style="margin-left: 12px; margin-top: 8px; color: #ce6223; font-size: 20px;"></span>');
            $tab.find('.js-sex').html('<div class="badge badge-success" style="cursor: pointer;">Мужской</div>');

            $tab.find('.js-image img').css('max-height', '130px');
            $tab.find('.card-body .media > div').css('width', '130px');

            $tab.find('.js-name').html('<input class="form-control" type="text" placeholder="Имя">');
            $tab.find('.js-surname').html('<input class="form-control" type="text" placeholder="Фамилия">');
            $tab.find('.js-comment').html('<textarea class="form-control" placeholder="Комментарий"></textarea>');

            $tab.find('.js-is_ready').css('cursor','pointer');
            $tab.find('.js-is_ready_only_for_partnership').css('cursor','pointer');

            var phoneStart = this.getDefaultOrSessionCookie('DEF_CONST_PHONE_START');
            var phoneNumberLength = this.getDefaultOrSessionCookie('DEF_CONST_PHONE_NUMBER_LENGTH');
            $tab.find('.js-phone').html('<input class="form-control" type="text" placeholder="Телефон" ' +
                'value="' + phoneStart + '" data-last-right-value="' + phoneStart + '">');
            $tab.find('.js-email').html('<input class="form-control" type="text" placeholder="E-mail">');

            $tab.find('.js-sex div.badge').on('click', function(e){
                window.helper.changeBadge(e.target, 'Мужчина', 'Женщина');
            });
            $tab.find('.js-is_ready.badge').on('click', function(e){
                window.helper.changeBadge(e.target, 'Готов', 'Не готов');
            });
            $tab.find('.js-is_ready_only_for_partnership.badge').on('click', function(e){
                window.helper.changeBadge(e.target, 'Готов для задания', 'Только партнер');
            });
            $tab.find('.js-phone input').on('input', function(e){
                if (e.target.value.length < phoneStart.length ||
                    e.target.value.slice(phoneStart.length).match(/[^0-9]/g) ||
                    e.target.value.match(/[0-9]/g).length > phoneNumberLength
                ) {
                    e.target.value = e.target.getAttribute('data-last-right-value');
                    return;
                }
                e.target.setAttribute('data-last-right-value', e.target.value);
            });
            var self = this;
            $tab.find('.js-login input').on('input', function(e){
                self.checkIsLoginPossible(className, $(e.target).data('startValue'), e.target.value);
            });
            $tab.find('.js-image').hover(function(e){
                var $image = $tab.find('.js-image');
                $image.prepend(
                    '<div class="js-user-store-image-button-container" style="position:relative;">' +
                        '<label class="btn btn-xs btn-success fas fa-pencil-alt js-user-store-image-button" ' +
                        'for="changeUserImage" style="position:absolute; top: 12px; left: 12px; z-index:1300;">' +
                            '<input type="file" id="changeUserImage" style="display:none;">' +
                        '</label>' +
                    '</div>'
                )
                var $input = $image.find('.js-user-store-image-button input')
                $input.on('change', function(e) {
                    var inputFile = e.target.files[0];
                    var fReader = new FileReader();
                    fReader.onloadend = function(e){
                        $image.find('img').attr('src', fReader.result);
                        this.storeUserData.image = inputFile;
                    }
                    // Проверяем введен ли какой-то файл
                    if (inputFile) {
                        fReader.readAsDataURL(inputFile);
                    } else {
                        $image.find('img').attr('src', '');
                    }
                });
            }, function(e){
                $tab.find('.js-image .js-user-store-image-button-container').remove();
            });
        }
        fillUserTabStoreRealValues(className, user) {
            var $tab = $('div.modal.fade.show.' + className + ' > div > div > div.modal-body > div');
            $tab.find('.js-image img').attr('src','/images/users/user_' + user['id'] + '.' + user['ext']);
            $tab.find('.js-login input').val(user['login']);
            $tab.find('.js-login input').data('startValue', user['login']);
            if (user['sex'] == false) {
                window.helper.changeBadge($tab.find('.js-sex div.badge'), 'Мужчина', 'Женщина');
            }
            if (user['isReady'] == false) {
                window.helper.changeBadge($tab.find('.js-is_ready.badge'), 'Готов', 'Не готов');
            }

            if (user['isReadyOnlyForPartnership'] == false) {
                window.helper.changeBadge($tab.find(
                    '.js-is_ready_only_for_partnership.badge'),
                    'Готов для задания',
                    'Только партнер'
                );
            }
            $tab.find('.js-login input').val(user['login']);
            $tab.find('.js-name input').val(user['name']);
            $tab.find('.js-surname input').val(user['surname']);
            $tab.find('.js-comment textarea').val(user['comment']);
            $tab.find('.js-phone input').val(user['phone']);
            $tab.find('.js-email input').val(user['email']);
            var self = this;
            var $footer = $('div.modal.fade.show.' + className + ' > div > div > div.modal-footer');
            if (self.permissions.users[user['id']['delete']] === true) {
                $footer.find('.js-modal-action-second').on('click', function (e) {
                    self.deleteUser(className, user['id']);
                });
            }
            $footer.find('.js-modal-action').on('click', function(e){
                self.storeUser(className, user['id']);
            });
        }
        createStoreModal(className, title, action, secondAction){
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
            if (typeof secondAction === 'string' && Boolean(secondAction)) {
                $modal.find('.modal-footer')
                    .prepend('<button type="button" class="btn btn-danger js-modal-action-second">' +
                        secondAction + '</button>');
            }
            $modal.find('.modal-header button').on('click', function(){
                $modal.remove();
            });
            $('body').append($modal);
        }
        getSortingFields() {
            var errorMessage = 'Ошибка получения полей для фильтрации: ';
            var self = this;
            $.ajax({
                url: document.location.origin + '/user/json',
                method: 'post',
                data: {intent:'Get sorting fields'},
                dataType: 'json',
                complete: function () {
                },
                error: function (xhr, status, error) {
                    $.fn.iNotify(errorMessage + status, 'warning');
                    $('.js-search-block-spinner').remove();
                    $('body').append(xhr.responseText);
                },
                success: function (json) {
                    if (json['error']) {
                        $.fn.iNotify(errorMessage + json['response'], 'warning');
                        $('body').append(errorMessage + json['response']);
                        return;
                    }

                    var fields = json['response'];
                    var $select = $('.js-users-sorting-select');

                    $.each(fields,
                        function(key, field) {
                            $.each(
                                field,
                                function(key, value) {
                                    if (self.usersSorting === undefined)
                                        self.setUsersObjectProperyAndSessionCookieByCookie('USERS_SORTING', key);
                                    self.createSelectOptions($select, key, value, self.usersSorting);
                                }
                            )
                        }
                    );

                    $select.on('change', function(e){
                        self.setUsersObjectProperyAndSessionCookieByCookie(
                            'USERS_SORTING',
                            $(e.target).find('option:selected').val()
                        );
                        self.reloadSearch();
                    });
                    self.createUsersBlock();
                    $('.js-search-block-spinner').remove();
                }
            });
        }
        getUsersBySearch() {
            var errorMessage = 'Ошибка получения пользователей: ';
            var self = this;
            if (self.usersSearchText === undefined)
                self.setUsersObjectProperyAndSessionCookieByCookie('USERS_SEARCH_TEXT', '');
            $.ajax({
                url: document.location.origin + '/user/json',
                method: 'post',
                data: {
                    intent:           'Get users by search',
                    sortingDirection: this.sortingDirection,
                    sortingField:     this.usersSorting,
                    search:           this.usersSearchText,
                    pageNumber:       this.pageNumber,
                    usersCountOnPage: this.usersCountOnPage,
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
                        if (json.response === 'Error! Row count is zero!') {
                            $('.js-users-block-spinner').remove();
                            $('.js-users-block table tbody tr').remove();
                            $('.js-users-block table tbody').append(
                                '<tr>' +
                                '<td class="text-secondary">Пользователей не найдено</td>' +
                                '</tr>'
                            );
                            return;
                        }
                        $.fn.iNotify(errorMessage + json.response, 'warning');
                        $('body').append(errorMessage + json.response);
                        return;
                    }

                    var response = json.response;
                    self.usersCount = parseInt(response.usersCount);
                    self.foundedUsersData = response.users;
                    self.permissions.create = response.permissions.create;
                    self.permissions.users = response.permissions.users;
                    $('.js-users-block-spinner').remove();
                    self.createUsers();
                    self.createCreateBlock();
                    self.createPagesBlock();
                }
            });
        }
        checkIsLoginPossible(className, login, newLogin) {
            var errorMessage = 'Ошибка проверки корректности логина: ';
            var self = this;
            var $indicator = $('div.modal.fade.show.' + className +' .modal-body .card-header div span');
            $indicator.addClass('fa-spinner').addClass('fa-spin');
            $.ajax({
                url: document.location.origin + '/user/json',
                method: 'post',
                data: {
                    intent: 'Get is login possible',
                    login: login,
                    newLogin: newLogin,
                },
                dataType: 'json',
                async: false,
                complete: function () {
                    $indicator.removeClass('fa-spinner').removeClass('fa-spin');
                },
                error: function (xhr, status, error) {
                    $.fn.iNotify(errorMessage + status, 'warning');
                },
                success: function (json) {
                    if (json['error']) {
                        $.fn.iNotify(errorMessage + json['response'], 'warning');
                    }
                    var $indicator = $('div.modal.fade.show.' + className +' .modal-body .card-header div span');
                    $indicator.removeClass('fa-exclamation-triangle');
                    if (json['response'] === false) {
                        $indicator.addClass('fa-exclamation-triangle');
                        return;
                    }
                }
            });
        }
        storeUser(className, id) {
            var errorMessage = 'Ошибка обновления пользователя: ';
            var self = this;

            self.setUserData(className, id);

            var $tab = $('div.modal.fade.show.' + className + ' .modal-body .card');
            $.ajax({
                url: document.location.origin + '/user/json',
                method: 'post',
                data: self.storeUserData,
                dataType: 'json',
                complete: function () {
                    /*$('div.modal.fade.show.' + className +' .modal-body .card-header div span')
                        .removeClass('fa-spinner').removeClass('fa-spin');*/
                },
                error: function (xhr, status, error) {
                    $.fn.iNotify(errorMessage + status, 'warning');
                },
                success: function (json) {
                    if (json['error']) {
                        $.fn.iNotify(errorMessage + json['response'], 'warning');
                    }
                    var $indicator = $('div.modal.fade.show.' + className +' .modal-body .card-header div span');
                    $indicator.removeClass('fa-exclamation-triangle');
                    if (json['response'] === false) {
                        $indicator.addClass('fa-exclamation-triangle');
                        return;
                    }
                }
            });
        }
        setUserData(className, id) {
            if (!Boolean(id)) id = 'null';

            this.storeUserData['intent'] = 'Store user';
            this.storeUserData['id'] = id;

            var $tab = $('div.modal.fade.show.' + className + ' .modal-body .card');
            this.storeUserData['name'] = $tab.find('.js-name input').val();
            this.storeUserData['surname'] = $tab.find('.js-surname input').val();
            this.storeUserData['email'] = $tab.find('.js-email input').val();
            this.storeUserData['login'] = $tab.find('.js-login input').data('startValue');
            this.storeUserData['newLogin'] = $tab.find('.js-login input').val();
            this.storeUserData['isReady'] = window.helper.isBadge($tab.find('.js-is_ready')).toString();
            this.storeUserData['isReadyOnlyForPartnership'] =
                window.helper.isBadge($tab.find('.js-is_ready_only_for_partnership')).toString();
            this.storeUserData['comment'] = $tab.find('.js-login textarea').text();
            this.storeUserData['sex'] = window.helper.isBadge($tab.find('.js-sex div')).toString();
            this.storeUserData['phone'] = $tab.find('.js-phone input').val();
        }
        createSelectOptions($select, value, text, selectedField) {
            var option = document.createElement('option');

            if (value === selectedField)
                option.setAttribute('selected',true);
            option.setAttribute('value', value);
            option.innerText = text;
            $select.append(option);
        }
        create() {
            this.createSearchBlock();
        }
    }

    document.users = new User();
});