<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\PunchoutCatalogs\Communication\Plugin\CompanyBusinessUnit;

use Generated\Shared\Transfer\CompanyBusinessUnitResponseTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer;
use Generated\Shared\Transfer\PunchoutCatalogSetupRequestTransfer;
use Spryker\Zed\CompanyBusinessUnitExtension\Dependency\Plugin\CompanyBusinessUnitDeletePreCheckPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \SprykerEco\Zed\PunchoutCatalogs\Business\PunchoutCatalogsFacadeInterface getFacade()
 */
class PunchoutCatalogsCompanyBusinessUnitDeletePreCheckPlugin extends AbstractPlugin implements CompanyBusinessUnitDeletePreCheckPluginInterface
{
    /**
     * {@inheritDoc}
     * - Finds punchout catalogs which use given CompanyBusinessUnitTransfer.
     * - Returns CompanyBusinessUnitResponseTransfer with check results.
     * - CompanyBusinessUnitResponseTransfer::isSuccessful is equal to true when usage cases were not found, false otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitResponseTransfer
     */
    public function execute(CompanyBusinessUnitTransfer $companyBusinessUnitTransfer): CompanyBusinessUnitResponseTransfer
    {
        $punchoutCatalogConnectionTransfer = (new PunchoutCatalogConnectionTransfer())
            ->setFkCompanyBusinessUnit($companyBusinessUnitTransfer->getIdCompanyBusinessUnit());

        return $this->getFacade()->isCompanyBusinessUnitDeletable($punchoutCatalogConnectionTransfer);
    }
}
