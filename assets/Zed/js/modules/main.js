/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved. 
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file. 
 */

'use strict';

$(document).ready(function() {
    $('#punchoutCatalogConnection_fkCompanyBusinessUnit').on('change', function () {
        var parentCompanyBusinessUnitId = $(this).val(),
            companyBusinessUnitField = $('#punchoutCatalogConnection_setup_request_setup_fkCompanyBusinessUnit'),
            companyUserField = $('#punchoutCatalogConnection_setup_request_setup_fkCompanyUser');

        if (!parentCompanyBusinessUnitId) {
            companyBusinessUnitField.select2();
            companyUserField.select2();

            return;
        }

        companyBusinessUnitField.select2({
            ajax: {
                url: function () {

                    return '/punchout-catalogs/company-business-unit?id-parent-company-business-unit=' + parentCompanyBusinessUnitId;
                },
                delay: 250,
                dataType: 'json',
                cache: true,
            },
        });

        companyUserField.select2({
            ajax: {
                url: function () {
                    return '/punchout-catalogs/company-user?id-company-business-unit=' + parentCompanyBusinessUnitId;
                },
                delay: 250,
                dataType: 'json',
                cache: true,
            },
        });
    });

    $('#punchoutCatalogConnection_fkCompanyBusinessUnit')
        .select2()
        .change();
});
