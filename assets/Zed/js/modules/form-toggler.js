/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

$(document).ready(function() {
    $('.dependent-trigger').each(function (index, selectItem) {
        $('.dependent-child').parent('.form-group').addClass('dependent-parent');
        var groupName = $(selectItem).attr('data-dependent-group');
        var visibleTarget = getTargetElements(groupName, selectItem.value);
        visibleTarget.addClass('active').find(':input').attr('disabled', false);
        visibleTarget.siblings('.dependent-parent').find(':input').attr('disabled', true);
        $(selectItem).on('change', function (event) {
            $('.dependent-child[data-dependent-group=' + groupName + ']')
                .parent('.dependent-parent')
                .removeClass('active')
                .find(':input')
                .attr('disabled', true);
            visibleTarget = getTargetElements(groupName, event.target.value);
            visibleTarget.addClass('active').find(':input').attr('disabled', false);
        })
    })
    function getTargetElements(group, type) {
        return $('.dependent-child[data-dependent-group=' + group + '][data-dependent-type=' + type + ']').parent('.dependent-parent');
    }
});
