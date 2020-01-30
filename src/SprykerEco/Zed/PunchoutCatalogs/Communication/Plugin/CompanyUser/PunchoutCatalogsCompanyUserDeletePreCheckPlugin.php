<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\PunchoutCatalogs\Communication\Plugin\CompanyUser;

use Generated\Shared\Transfer\CompanyUserResponseTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\PunchoutCatalogConnectionSetupTransfer;
use Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer;
use Spryker\Zed\CompanyUserExtension\Dependency\Plugin\CompanyUserDeletePreCheckPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \SprykerEco\Zed\PunchoutCatalogs\Business\PunchoutCatalogsFacadeInterface getFacade()
 */
class PunchoutCatalogsCompanyUserDeletePreCheckPlugin extends AbstractPlugin implements CompanyUserDeletePreCheckPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserResponseTransfer
     */
    public function execute(CompanyUserTransfer $companyUserTransfer): CompanyUserResponseTransfer
    {
        $fkCompanyUser = $companyUserTransfer->getIdCompanyUser();
        $punchoutCatalogConnectionSetupTransfer = (new PunchoutCatalogConnectionSetupTransfer())
            ->setFkCompanyUser($fkCompanyUser);

        $punchoutCatalogsTransfer = (new PunchoutCatalogConnectionTransfer())
            ->setSetup($punchoutCatalogConnectionSetupTransfer);

        return $this->getFacade()->isCompanyUserDeletable($punchoutCatalogsTransfer);
    }
}