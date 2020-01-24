<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\PunchoutCatalogs\Business\Checker;

use Generated\Shared\Transfer\CompanyBusinessUnitResponseTransfer;
use Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer;

interface CompanyBusinessUnitDeleteCheckerInterface
{
    /**
     * @param \Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer $punchoutCatalogConnectionTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitResponseTransfer
     */
    public function isCompanyBusinessUnitDeletable(
        PunchoutCatalogConnectionTransfer $punchoutCatalogConnectionTransfer
    ): CompanyBusinessUnitResponseTransfer;
}
