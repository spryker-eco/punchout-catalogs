<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\PunchoutCatalogs\Business\Checker;

use Generated\Shared\Transfer\CompanyUserResponseTransfer;
use Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer;

interface CompanyUserDeleteCheckerInterface
{
    /**
     * @param \Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer $punchoutCatalogConnectionTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserResponseTransfer
     */
    public function isCompanyUserDeletable(
        PunchoutCatalogConnectionTransfer $punchoutCatalogConnectionTransfer
    ): CompanyUserResponseTransfer;
}
