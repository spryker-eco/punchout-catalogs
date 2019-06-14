/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

$(document).ready(function() {

    createToggleElements();

    $('.toggle-trigger').each(function (index, selectItem) {
        var group = $(selectItem).data('toggle-group'),
            type = selectItem.value,
            items = getItems(group),
            activeItem = getActiveItem(group, type);

        toggleItems(items, activeItem);

        $(selectItem).on('change', function (event) {
            type = event.target.value;
            activeItem = getActiveItem(group, type);

            toggleItems(items, activeItem);
        });
    });

    function createToggleElements () {
        $('.toggle-inner-item').parent('.form-group').addClass('toggle-item');
    }

    function getItems(group) {
        return $('.toggle-inner-item[data-toggle-group=' + group + ']').parent('.toggle-item');
    }

    function getActiveItem(group, type) {
        return $('.toggle-inner-item[data-toggle-group=' + group + '][data-toggle-type=' + type + ']').parent('.toggle-item');
    }

    function showItems(activeItem) {
        activeItem.addClass('active').find(':input').attr('disabled', false);
    }

    function hideItems(toggleItems) {
        toggleItems.removeClass('active').find(':input').attr('disabled', true);
    }

    function toggleItems(items, activeItem) {
        hideItems(items);
        showItems(activeItem);
    }
});
