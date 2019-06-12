/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved. 
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file. 
 */

'use strict';

$(document).ready(function() {
    $('#punchoutCatalogConnection_single_request_setup_fkCompanyBusinessUnit').select2({
      ajax: {
        url: function () {
            return'/punchout-catalogs/company-business-unit?id-parent-company-business-unit=' + $('#punchoutCatalogConnection_fkCompanyBusinessUnit').val();
        },
        delay: 250,
        dataType: 'json',
        cache: true,
      },
    });

    $('#punchoutCatalogConnection_single_request_setup_fkCompanyUser').select2({
        ajax: {
            url: function () {
                return'/punchout-catalogs/company-user?id-company-business-unit=' + $('#punchoutCatalogConnection_fkCompanyBusinessUnit').val();
            },
            delay: 250,
            dataType: 'json',
            cache: true,
        },
    });

    $('#punchoutCatalogConnection_fkCompanyBusinessUnit').select2();
});
