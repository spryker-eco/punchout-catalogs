/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved. 
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file. 
 */

'use strict';

$(document).ready(function() {
    $('#punchoutCatalogConnection_setup_request_setup_fkCompanyBusinessUnit').select2({
      ajax: {
        url: function () {
            var idCompanyBusinessUnit = $('#punchoutCatalogConnection_fkCompanyBusinessUnit').val();

            if (!idCompanyBusinessUnit) {
                return 'javascript:void(0)';
            }

            return '/punchout-catalogs/company-business-unit?id-parent-company-business-unit=' + idCompanyBusinessUnit;
        },
        delay: 250,
        dataType: 'json',
        cache: true,
      },
    });

    $('#punchoutCatalogConnection_setup_request_setup_fkCompanyUser').select2({
        ajax: {
            url: function () {
                var idCompanyBusinessUnit = $('#punchoutCatalogConnection_fkCompanyBusinessUnit').val();

                if (!idCompanyBusinessUnit) {
                    return 'javascript:void(0)';
                }

                return '/punchout-catalogs/company-user?id-company-business-unit=' + idCompanyBusinessUnit;
            },
            delay: 250,
            dataType: 'json',
            cache: true,
        },
    });

    $('#punchoutCatalogConnection_fkCompanyBusinessUnit').select2();
});
