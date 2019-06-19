/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var TOGGLE_TRIGGER_SELECTOR = '.toggle-trigger';
var TOGGLE_HOOK_ITEM_SELECTOR = '.toggle-inner-item';
var TOGGLE_ITEM_CLASS_NAME = 'toggle-item';
var TOGGLE_GROUP_NAME = 'toggle-group';
var TOGGLE_TARGET_TYPE = 'toggle-type';
var VISIBLE_CLASS_NAME = 'active';

$(document).ready(function() {

    initToggleElements();

    function initToggleElements() {
        $(TOGGLE_HOOK_ITEM_SELECTOR).parent('.form-group').addClass(TOGGLE_ITEM_CLASS_NAME);

        $(TOGGLE_TRIGGER_SELECTOR).each(function (index, selectItem) {
            var groupName = $(selectItem).data(TOGGLE_GROUP_NAME);
            var currentSelectValue = selectItem.value;

            toggleItemsVisibility(groupName, currentSelectValue);
            $(selectItem).on('change', { group: groupName }, onChangeHandler);
        });
    }

    function getItems(group) {
        return $(TOGGLE_HOOK_ITEM_SELECTOR + '[data-' + TOGGLE_GROUP_NAME + '=' + group + ']').parent('.' + TOGGLE_ITEM_CLASS_NAME);
    }

    function getActiveItem(group, currentSelectValue) {
        return $(TOGGLE_HOOK_ITEM_SELECTOR + '[data-' + TOGGLE_GROUP_NAME + '=' + group + '][data-' + TOGGLE_TARGET_TYPE + '=' + currentSelectValue + ']').parent('.' + TOGGLE_ITEM_CLASS_NAME);
    }
    
    function onChangeHandler(event) {
        var currentSelectValue = event.target.value;
        var groupName = event.data.group;

        toggleItemsVisibility(groupName, currentSelectValue);
    }

    function toggleItemsVisibility(groupName, currentSelectValue) {
        var items = getItems(groupName);
        var activeItem = getActiveItem(groupName, currentSelectValue);

        items.removeClass(VISIBLE_CLASS_NAME).find(':input').attr('disabled', true);
        activeItem.addClass(VISIBLE_CLASS_NAME).find(':input').attr('disabled', false);
    }
});
