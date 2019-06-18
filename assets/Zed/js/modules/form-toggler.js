/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

$(document).ready(function() {

    initToggleElements();

    function initToggleElements() {
        $('.toggle-inner-item').parent('.form-group').addClass('toggle-item');

        $('.toggle-trigger').each(function (index, selectItem) {
            var group = $(selectItem).data('toggle-group');
            var selectedValue = selectItem.value;
            var items = getItems(group);
            var activeItem = getActiveItem(group, selectedValue);

            toggleItems(items, activeItem);

            $(selectItem).on('change', function (event) {
                selectedValue = event.target.value;
                activeItem = getActiveItem(group, selectedValue);

                toggleItems(items, activeItem);
            });
        });
    }

    function getItems(group) {
        return $('.toggle-inner-item[data-toggle-group=' + group + ']').parent('.toggle-item');
    }

    function getActiveItem(group, selectedValue) {
        return $('.toggle-inner-item[data-toggle-group=' + group + '][data-toggle-type=' + selectedValue + ']').parent('.toggle-item');
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
