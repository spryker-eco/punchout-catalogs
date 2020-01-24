<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\PunchoutCatalogs\Business\Checker;

use Generated\Shared\Transfer\CompanyUserResponseTransfer;
use Generated\Shared\Transfer\PunchoutCatalogConnectionCollectionTransfer;
use Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer;
use SprykerEco\Zed\PunchoutCatalogs\Business\Reader\PunchoutCatalogsReaderInterface;

class CompanyUserDeleteChecker implements CompanyUserDeleteCheckerInterface
{
    /**
     * @var SprykerEco\Zed\PunchoutCatalogs\Business\Reader\PunchoutCatalogsReaderInterface
     */
    protected $punchoutCatalogsReader;

    /**
     * @param \SprykerEco\Zed\PunchoutCatalogs\Business\Reader\PunchoutCatalogsReaderInterface $punchoutCatalogsReader
     */
    public function __construct(PunchoutCatalogsReaderInterface $punchoutCatalogsReader)
    {
        $this->punchoutCatalogsReader = $punchoutCatalogsReader;
    }

    /**
     * @param \Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer $punchoutCatalogConnectionTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserResponseTransfer
     */
    public function isCompanyUserDeletable(
        PunchoutCatalogConnectionTransfer $punchoutCatalogConnectionTransfer
    ): CompanyUserResponseTransfer {
        $punchoutCatalogConnectionTransfer->requireSetup();

        $punchoutCatalogCollectionTransfer = $this->punchoutCatalogsReader
            ->findConnectionByFkCompanyUser($punchoutCatalogConnectionTransfer->getSetup()->getFkCompanyUser());

        return $this->createCompanyUserResponseTransfer($punchoutCatalogCollectionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PunchoutCatalogConnectionCollectionTransfer $punchoutCatalogConnectionCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserResponseTransfer
     */
    protected function createCompanyUserResponseTransfer(
        PunchoutCatalogConnectionCollectionTransfer $punchoutCatalogConnectionCollectionTransfer
    ): CompanyUserResponseTransfer {
        $companyUserResponseTransfer = (new CompanyUserResponseTransfer())->setIsSuccessful(true);

        if (!count($punchoutCatalogConnectionCollectionTransfer->getPunchoutCatalogConnection())) {
            return $companyUserResponseTransfer;
        }

        return $companyUserResponseTransfer->setIsSuccessful(false);
    }
}
