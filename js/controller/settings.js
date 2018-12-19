/**
 * Created by Skoroid on 26.11.2018.
 */

$(document).ready(function() {
    class Settings {
        constructor() {
            this.entities            = window['DEF_CONST_SETTINGS'];
            this.entitiesNames       = window['DEF_CONST_SETTINGS_NAMES'];
            this.entitiesFieldsNames = window['DEF_CONST_SETTINGS_FIELDS_NAMES'];
            this.permissions         = {};
            this.isNeedReloadSetting = {};

            this.createSettingsBlocks();
            this.setPermissions();
        }
        requireSetting(setting) {
            var $settingTab = this.getSettingTab(this.getSettingNames(setting));
            var self = this;
            this.getSettingData(
                {
                    intent: 'Get ' +window.helper.getVariableUnderlineNameFromCamelCase(setting, ' ')
                },
                function(json) {
                    self.createSetting(setting, $settingTab, json);
                    if (self.isAllSettingsLoaded())
                        self.resetAllEntitiesFieldsRelative()

                    return true;
                },
                window.helper.ucfirst(this.getSettingNames(setting)) + '. Ошибка получения',
                undefined,
                undefined,
                '.js-setting-' + setting
            );
        }
        isAllSettingsLoaded() {
            var result = true;
            $.each(this.entities, function(field, value) {
                var $settingTab = $('.js-setting-' + value + ' .js-setting-tab');
                if ($settingTab.length === 0) {
                    result = false;
                    return;
                }
            });
            return result;
        }
        resetAllEntitiesFieldsRelative() {
            var self = this;
            $.each(this.entities, function(field, value) {
                self.resetEntitiesFieldsRelative(value);
            });
        }
        resetEntitiesFieldsRelative(setting) {

            var $settingTab = $('.js-setting-' + setting + ' .js-setting-tab');
            if ($settingTab.length === 0)
                return;
            var self = this;
            $.each(this.getSettingFieldsNames(setting), function(field, value) {
                if (!self.isRelatedEntityFieldId(field))
                    return;
                var relatedEntity           = self.getRelatedEntityByIdField(field);
                var relatedEntityFieldsById = self.getRelatedFieldsByIds(relatedEntity);

                if (!window.helper.checkNotEmptyObject(relatedEntityFieldsById, 'Related settings by ids'))
                    return;

                self.setRelatedEntitiesFieldsByIds(setting, relatedEntity, relatedEntityFieldsById);
            });
        }
        setRelatedEntitiesFieldsByIds(setting, relatedEntityName, relatedEntityFieldsById) {
            var $settingTab = $('.js-setting-' + setting + ' .js-setting-tab');
            if ($settingTab.length === 0) {
                console.log('Wrong setting when trying to set related entity ' + relatedEntity);
                return;
            }
            var self = this;
            $settingTab.find('.js-tab-body .js-table table tbody tr').each(function(id, row) {
                $(row).find('td').each(function(id, cell) {
                    var field = $(cell).data('field');
                    if (field === undefined)
                        return;

                    if (self.getRelatedEntityByIdField(field) !== relatedEntityName)
                        return;

                    var cellId = $(cell).data('id');
                    if (!window.helper.checkIntPositiveString(cellId))
                        console.log('If of field to set related name is not int positive');

                    if (!window.helper.checkNotEmptyValue(relatedEntityFieldsById[cellId])) {
                        $(cell).text('null');
                        return;
                    }

                    $(cell).text(self.getNameOfEnyEntity(relatedEntityFieldsById[cellId]));
                });
            });
        }
        getNameOfEnyEntity(entity) {
            if (!window.helper.checkNotEmptyObject(entity, 'Related entity'))
                return;

            if (window.helper.checkNotEmptyValue(entity['name']))
                return entity['name'];

            if (window.helper.checkNotEmptyValue(entity['date']))
                return entity['date'];

            if (window.helper.checkNotEmptyValue(entity['startDate']) &&
                    window.helper.checkNotEmptyValue(entity['endDate']))
                return entity['startDate'] + ' - ' + entity['endDate'];

            console.log('Could not select name fore this entity');
        }
        getRelatedFieldsByIds(relatedEntity) {
            var $settingTab = $('.js-setting-' + relatedEntity + ' .js-setting-tab');
            if ($settingTab.length === 0)
                return;
            var result = {};
            var self = this;
            $settingTab.find('.js-tab-body .js-table table tbody tr').each(function(id, row) {
                var relatedEntityid = $(row).data('id');
                if (relatedEntityid === undefined) {
                    console.log('Id not defined when trying to find related values of ' + relatedEntity);
                    return;
                }

                result[relatedEntityid] = {};
                $(row).find('td').each(function(id, cell) {
                    var field = $(cell).data('field');
                    if (field === undefined)
                        return;

                    if (window.helper.checkDomElementTextContent(cell))
                        result[relatedEntityid][field] = $(cell).text();
                });
            });
            return result;
        }
        renderTableByJson(json, topFields) {
            if (!window.ajax.checkJson(json))
                return;

            if (json.response.length === 0)
                return;
            
            var settingEntities = json.response;

            if (!window.helper.checkNotEmptyObject(
                    topFields,
                    'Table top fields'
                )
            )
                return;

            var $table = $('<table class="table"></table>');

            var $top = $('<thead><tr></tr></thead>');
            $.each(topFields, function(id, field) {
                if (id === 'id')
                    return;
                $top.find('tr').append('<td>' + field + '</td>');
            });
            $table.append($top);

            $table.append('<tbody></tbody>');
            var $tbody = $table.find('tbody');
            var self = this;
            $.each(settingEntities, function(id, settingEntity) {
                var $row = $('<tr></tr>');
                $.each(settingEntity, function(settingField, settingValue) {
                    if (self.isIdField(settingField)) {
                        $row.data('id', settingValue);
                        $row.addClass('js-entity-item-id-' + settingValue);
                        return;
                    }

                    var $td = $('<td></td>');
                    $td.addClass('js-field-' + settingField).data('field', settingField);

                    if (self.isBoolField(settingField))
                        $td.html(Boolean(parseInt(settingValue)) ? '&#10004' : '');
                    else if (self.isRelatedEntityFieldId(settingField))
                        $td.data('id', settingValue);
                    else
                         $td.text(settingValue);

                    $row.append($td);
                });

                $tbody.append($row);
            });
            $table.append($tbody);
            return $table;
        }
        getSettingTab(title) {
            if (title === undefined)
                title = '';
            return $(
                '<div class="js-setting-tab">' +
                    '<div class="js-tab-header">' +
                        '<h3>' + title + '</h3>' +
                    '</div>' +
                    '<div class="js-tab-body">' +
                        '<div class="js-table my-pre-scrollable">' +
                        '</div>' +
                    '</div>' +
                '</div>'
            );
        }
        createSettingsBlocks() {
            var $result = $('.js-content');
            $.each(this.entities, function(id, entity) {
                $result.append(
                    '<div class="js-setting-' + entity + '"></div>'
                );
            });
        }
        createSetting(setting, $settingTab, json) {
            var $table = this.renderTableByJson(json, this.getSettingFieldsNames(setting));
            $settingTab.find('.js-table').append($table);
            this.setSettingTabCheckboxes($settingTab, setting);
            this.setHoverControls($settingTab, setting)
            $('body .js-content .js-setting-' + setting).empty().append($settingTab);
        }
        setSettingTabCheckboxes($settingTab, setting) {
            if (this.canCrudSetting(['update', 'delete'], setting)) {
                $settingTab.find('table thead tr')
                    .prepend('<td><input class="js-checkbox-head" type="checkbox" value=""></td>');
                $settingTab.find('table tbody tr').each(function(id, element) {
                    var $td = $('<td></td>').append(
                        '<input class="js-checkbox" type="checkbox" value="' + $(element).data('id') + '">'
                    );
                    $(element).prepend($td);
                });
                window.pageBuilder.handleCheckboxCheckedByParentClick(
                    $settingTab.find('table .js-checkbox-head'),
                    $settingTab.find('table .js-checkbox')
                );
                $settingTab.find('table .js-checkbox').each(function(id, checkbox) {
                    window.pageBuilder.handleCheckboxCheckedByParentClick($(checkbox));
                });
            }
        }
        setHoverControls($settingTab, setting) {
            var $settingTabHeader = $settingTab.find('.js-tab-header');
            var $settingTableHead = $settingTab.find('.js-table thead');

            var removeButtonCreate = function() {
                $settingTabHeader
                    .css('position', '')
                    .find('button')
                    .remove();
            }

            var removeButtonDelete = function() {
                $settingTabHeader
                    .css('position', '')
                    .find('button')
                    .remove();
            }

            if (this.canCrudSetting(['update'], setting)) {
                var self = this;
                $settingTab.find('.js-table tbody tr').each(function() {
                    var $settingTableRow = $(this);

                    var removeButtonUpdate = function() {
                        $settingTableRow.find('td')
                            .first()
                            .css('position', '')
                            .find('button')
                            .remove();
                    }

                    $settingTableRow.hover(function(e) {
                        $settingTableRow.find('td')
                            .first()
                            .css('position', 'relative')
                            .append(
                                '<button class="btn btn-xs btn-success fas fa-pencil-alt js-' + setting + '-update" ' +
                                'style="position:absolute; top: 12px; left: 35px; z-index:1300;"></button>'
                            )
                            .find('button')
                            .on('click', function (e) {
                                e.preventDefault();
                                removeButtonUpdate();
                                removeButtonCreate();
                                removeButtonDelete();

                                var settingEntityId = $settingTableRow.data('id');
                                if (settingEntityId === undefined) {
                                    console.log(window.helper.ucfirst(setting) + ' entity id not found');
                                    return;
                                }
                                var className = 'js-' + setting + '-store-modal';
                                self.createModalSetting(
                                    setting,
                                    className,
                                    'Изменить ' + self.getSettingNames(setting).toLowerCase(),
                                    'Изменить',
                                    function (e) {
                                        $(e.target).attr('disabled', 'disabled');
                                        self.getSettingData(
                                            self.getSettingStoreData(className, setting),
                                            undefined,
                                            "Ошибка изменения настройки '" + self.getSettingNames(setting) + "'",
                                            function () {
                                                $(e.target).attr('disabled', false);
                                                self.isNeedReloadSetting[setting] = true;
                                            },
                                            '.' + className + ' .js-response');
                                    },
                                    settingEntityId
                                );
                                $('.' + className + ' .modal-body').append(self.getSettingStoreModalBody(setting));
                                self.fillSettingUpdateModalBody(className, setting, settingEntityId);
                                return false;
                            });
                    }, function(e){
                        removeButtonUpdate();
                    });
                });
            }
            if (this.canCrudSetting(['create'], setting)) {
                var self = this;

                $settingTab.hover(function(e) {
                    $settingTabHeader
                        .css('position', 'relative')
                        .append(
                            '<button class="btn btn-xs btn-primary fas fa-plus js-' + setting + '-create" ' +
                            'style="position:absolute; top: 5px; left: 35px; z-index:1300;"></button>'
                        )
                        .find('button.fa-plus')
                        .on('click', function (e) {
                            e.preventDefault();
                            removeButtonCreate();
                            removeButtonDelete();

                            var className = 'js-' + setting + '-store-modal';
                            self.createModalSetting(
                                setting,
                                className,
                                'Создать ' + self.getSettingNames(setting).toLowerCase(),
                                'Создать',
                                function (e) {
                                    $(e.target).attr('disabled', 'disabled');
                                    self.getSettingData(
                                        self.getSettingStoreData(className, setting),
                                        undefined,
                                        "Ошибка создания настройки '" + self.getSettingNames(setting) + "'",
                                        function () {
                                            $(e.target).attr('disabled', false);
                                            self.isNeedReloadSetting[setting] = true;
                                        },
                                        '.' + className + ' .js-response');
                                }
                            );
                            $('.' + className + ' .modal-body').append(self.getSettingStoreModalBody(setting));
                            return false;
                        });
                }, function(e){
                    removeButtonCreate();
                    removeButtonDelete();
                });
            }
            if (this.canCrudSetting(['delete'], setting)) {
                var self = this;

                $settingTab.hover(function(e) {
                    $settingTabHeader
                        .css('position', 'relative')
                        .append(
                            '<button class="btn btn-xs btn-danger fas fa-trash-alt js-' + setting + '-delete" ' +
                            'style="position:absolute; top: 5px; right: 35px; z-index:1300;"></button>'
                        )
                        .find('button.fa-trash-alt')
                        .on('click', function (e) {
                            e.preventDefault();
                            removeButtonCreate();
                            removeButtonDelete();

                            var className = 'js-' + setting + '-delete-modal';
                            self.createModalSetting(
                                setting,
                                className,
                                'Вы действительно хотите удалить эти настройки?',
                                'Да',
                                function (e) {
                                    $(e.target).attr('disabled', 'disabled');
                                    self.getSettingData(
                                        self.getSettingDeleteData(className, setting),
                                        undefined,
                                        "Ошибка удаления настроек '" + self.getSettingNames(setting) + "'",
                                        function () {
                                            $(e.target).attr('disabled', false);
                                            self.isNeedReloadSetting[setting] = true;
                                        },
                                        '.' + className + ' .js-response');
                                }
                            );
                            $('.' + className + ' .modal-body').append(self.getSettingDeleteModalBody(setting));
                            return false;
                        });
                }, function(e){
                    removeButtonCreate();
                    removeButtonDelete();
                });
            }
        }
        createModalSetting(setting, className, title, action, actionCallback, settingEntityId = undefined) {
            var $modal = $(
                '<div class="modal fade show ' + className + '" tabindex="-1" role="dialog"' +
                    'aria-labelledby="exampleModalLongTitle" aria-hidden="true" ' +
                    'style="display: block; padding-right: 17px;">' +
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
                            '<div class="modal-response form-group">' +
                                '<div class="col-sm-12 text-center js-response"></div>' +
                                '</div>' +
                                '<div class="modal-footer">' +
                                    '<button type="button" class="btn btn-primary js-modal-action">' +
                                        action + '</button>' +
                                '</div>' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                '<div class="modal-backdrop fade show"></div>'
            );
            if (settingEntityId !== undefined) {
                $modal.data('id', settingEntityId);
            }
            $modal.find('.modal-footer button.js-modal-action').on('click', function(e) {
                if (actionCallback === undefined)
                    return;

                if (typeof actionCallback !== 'function')
                    console.log('Wrong callback');

                actionCallback(e);
            });
            var self = this;
            $modal.find('.modal-header button').on('click', function(e) {
                self.reloadSettingTabIfNeeded(setting)
                $modal.remove();
            });
            $('body').append($modal);
        }
        getSettingStoreModalBody(setting) {
            var $setting = $('<div class="form-group"></div>');

            var settingFieldsNames = this.getSettingFieldsNames(setting);
            var self = this;
            $.each(settingFieldsNames, function(field, fieldName) {

                if (self.isIdField(field))
                    return;

                var $inputBlock = $(
                    '<div class="js-field js-field-' + field +'">' +
                        '<label class="control-label col-sm-12">' + fieldName + '</label>' +
                    '</div>'
                ).data('field', field);

                if (self.isDateField(field)) {
                    var id = 'datapicker_' + field + '_id';
                    $inputBlock
                        .append(
                            '<div class="input-group date" id="' + id + '" data-target-input="nearest">' +
                                '<input type="text" class="form-control datetimepicker-input col-sm-9" data-target="#' + id + '"/>' +
                                    '<div class="input-group-append" data-target="#' + id + '" data-toggle="datetimepicker">' +
                                    '<div class="input-group-text"><i class="fa fa-calendar"></i></div>' +
                                '</div>' +
                            '</div>'
                        )
                        .find('#' + id)
                        .datetimepicker({
                            format: 'YYYY-MM-DD',
                            locale: 'ru'
                        });
                } else if (self.isRelatedEntityFieldId(field)) {
                    $inputBlock.append(
                        '<select class="form-control"></select>'
                    );
                } else if (self.isBoolField(field)) {
                    $inputBlock.append(
                        '<input type="checkbox" class="form-control"></input>'
                    );
                } else {
                    $inputBlock.append(
                        '<input class="form-control" type="text">'
                    );
                }

                $setting.append($inputBlock);
            });
            return $setting;
        }
        getSettingDeleteModalBody(setting) {
            var $setting = $('<div class="js-settings-delete-ids-data"></div>');

            var ids = [];
            $('.js-setting-' + setting +
                ' .js-setting-tab .js-tab-body .js-table table tbody tr td input[type="checkbox"]:checked')
                .each(function(id, inputCheckedCheckbox) {

                    var $tableRow = $(inputCheckedCheckbox).closest('tr');
                    var id = parseInt($tableRow.data('id'));
                    if ('number' === typeof id)
                    ids.push(id);
                });
            $setting.data('ids', ids);
            return $setting;
        }
        fillSettingUpdateModalBody(className, setting, settingEntityId) {
            var $settingTable = $('.js-setting-' + setting +' .js-setting-tab .js-tab-body table');
            var self = this;
            $settingTable.find('tbody tr.js-entity-item-id-' + settingEntityId + ' td').each(function(id, row) {

                var fieldName = $(row).data('field');
                if (fieldName === undefined)
                    return;

                var $modalBodyItem = $('.' + className + ' .modal-body .js-field-' + fieldName);

                var $select = $modalBodyItem.find(' select');
                if ($select.length !== 0) {
                    var field                   = $modalBodyItem.data('field');
                    var relatedEntity           = self.getRelatedEntityByIdField(field);
                    var relatedEntityFieldsById = self.getRelatedFieldsByIds(relatedEntity);

                    $.each(relatedEntityFieldsById, function(id, value) {
                        var $option = $('<option></option>');
                        $option.val(id);
                        $option.text(self.getNameOfEnyEntity(value));
                        if ($option.text() === $(row).text())
                            $option.attr('selected', true);
                        $select.append($option);
                    });
                    return;
                }

                var $text = $modalBodyItem.find(' input[type="text"]');
                if ($text.length !== 0) {
                    $text.val($(row).text());
                    return;
                }

                var $checkbox = $modalBodyItem.find(' input[type="checkbox"]');
                if ($checkbox.length !== 0)
                    $checkbox.attr(
                        'checked',
                        $(row).text() === '' ? false : 'checked'
                    );
            });
        }
        getSettingStoreData(className, setting) {
            var data = {
                intent: 'Store ' + window.helper.getVariableUnderlineNameFromCamelCase(setting, ' ')
            };
            data.id = $('.' + className).data('id') === undefined ? '' : $('.' + className).data('id');
            var $modalBody = $('.' + className + ' .modal-body');
            var self = this;
            $modalBody.find('.js-field').each(function(id, settingFieldInputBlock) {
                var $modalBodyItem = $(settingFieldInputBlock);
                var field = $modalBodyItem.data('field');
                if (field === undefined)
                    return;

                if (self.isIdField(field))
                    return;

                if (self.isRelatedEntityFieldId(field)) {
                    data[field] = $modalBodyItem.find('select option:selected').val();
                    return;
                }

                if (self.isBoolField(field)) {
                    data[field] = +$modalBodyItem.find('input[type="checkbox"]')[0].checked;
                    return;
                }

                data[field] = $modalBodyItem.find('input[type="text"]').val();
            });
            return data;
        }
        getSettingDeleteData(className, setting) {
            var data = {
                intent: 'Delete ' + window.helper.getVariableUnderlineNameFromCamelCase(setting, ' '),
                ids: ''
            };
            var ids = $('.' + className + ' .js-settings-delete-ids-data').data('ids');
            if (ids !== undefined && 'object' === typeof ids && Array.isArray(ids) && ids.length > 0)
                data.ids = ids.join();

            return data;
        }
        isDateField(settingField) {
            return settingField.toLowerCase().indexOf('date') !== -1 &&
                settingField.toLowerCase().indexOf('date') === settingField.toLowerCase().length - 'date'.length;
        }
        isRelatedEntityFieldId(settingField) {
            return settingField.toLowerCase() !== 'id' &&
                settingField.indexOf('Id') === settingField.toLowerCase().length - 'id'.length;
        }
        getRelatedEntityByIdField(settingField) {
            return settingField.slice(0, settingField.toLowerCase().indexOf('id'));
        }
        isBoolField(settingField) {
            return settingField.toLowerCase().indexOf('is') === 0;
        }
        isIdField(settingField) {
            return settingField.toLowerCase().indexOf('id') === 0;
        }
        reloadSettingTabIfNeeded(setting) {
            if (!window.helper.checkNotEmptyString(setting))
                return;

            if (window.helper.checkNotEmptyObject(this.isNeedReloadSetting) === 0)
                return;

            if (this.isNeedReloadSetting[setting] === undefined)
                return;

            if (this.isNeedReloadSetting[setting] !== true)
                return;

            this.requireSetting(setting);
        }
        canCrudSetting(permissionsCrud, setting) {
            if (!window.helper.checkNotEmptyArray(permissionsCrud, 'Permissions CRUD'))
                return;

            if (!window.helper.checkNotEmptyString(setting))
                return;

            var self = this;
            var result = false;
            $.each(permissionsCrud, function(id, permissionCrud) {
                result = self.getSettingPermissionTo(permissionCrud, setting);
                if (!result)
                    return;
            });
            return result;
        }
        getCrudPermissions(permissionCrud) {
            if (!window.helper.checkPermissionCrud(permissionCrud))
                return;

            if (!window.helper.checkNotEmptyObject(
                    this.permissions,
                    'Permissions'
                )
            )
                return;

            if (!window.helper.checkNotEmptyObject(
                    this.permissions[permissionCrud],
                    window.helper.ucfirst(permissionCrud) + ' permission'
                )
            )
                return;
            return this.permissions[permissionCrud];
        }
        getSettingPermissionTo(permissionCrud, setting) {
            if (!window.helper.checkNotEmptyString(setting))
                return;

            var permissionsCrud = this.getCrudPermissions(permissionCrud);

            var settingPermission = window.helper.getVariableUnderlineNameFromCamelCase(setting);
            if (!window.helper.checkNotEmptyValue(permissionsCrud[settingPermission], 'Setting'))
                return false;

            return permissionsCrud[settingPermission];
        }
        getSettingNames(setting) {
            return this.getNames(setting, this.entitiesNames);
        }
        getSettingFieldsNames(setting) {
            return this.getNames(setting, this.entitiesFieldsNames, true);
        }
        getNames(setting, object, isFields = false) {
            var subName = isFields ? ' fields' : '';
            if (this.entities.indexOf(setting) === -1) {
                console.log('No such setting');
                return;
            }

            if (!window.helper.checkNotEmptyObject(
                    object,
                    'Settings' + subName + ' names'
                )
            )
                return;

            if (!window.helper.checkNotEmptyValue(
                    object[setting],
                    window.helper.ucfirst(setting) + subName + ' names'
                )
            )
                return;

            return object[setting];
        }
        getSettingData(data, callback, error, completeCallback, responseTextSelector, spinnerSelector) {
            window.ajax.run(
                '/settings/json',
                data,
                callback,
                completeCallback,
                error,
                responseTextSelector,
                spinnerSelector
            );
        }
        setPermissions() {
            var self = this;
            this.getSettingData(
                {
                    intent: 'Get settings permissions'
                },
                function(json) {
                    if (!window.ajax.checkJson(json))
                        return;
                    self.permissions = json.response;
                    self.create();
                },
                'Ошибка получения разрешений'
            );
        }
        create() {
            var self = this;
            $.each(this.entities, function(id, entity) {
                self.requireSetting(entity);
            });
        }
    }

    document.settings = new Settings();
});