<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\PunchoutCatalogs\Business\Checker;

use Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitResponseTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\PunchoutCatalogConnectionCollectionTransfer;
use Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer;
use Generated\Shared\Transfer\PunchoutCatalogResponseTransfer;
use Generated\Shared\Transfer\PunchoutCatalogSetupResponseTransfer;
use Generated\Shared\Transfer\ResponseMessageTransfer;
use SprykerEco\Zed\PunchoutCatalogs\Business\Reader\PunchoutCatalogsReaderInterface;

class CompanyBusinessUnitDeleteChecker implements CompanyBusinessUnitDeleteCheckerInterface
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
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitResponseTransfer
     */
    public function isCompanyBusinessUnitDeletable(
        PunchoutCatalogConnectionTransfer $punchoutCatalogConnectionTransfer
    ): CompanyBusinessUnitResponseTransfer {
        $punchoutCatalogConnectionTransfer->requireFkCompanyBusinessUnit()->getFkCompanyBusinessUnit();

        $punchoutCatalogCollectionTransfer = $this->punchoutCatalogsReader
            ->findConnectionByFkCompanyBusinessUnit($punchoutCatalogConnectionTransfer->getFkCompanyBusinessUnit());

        return $this->createCompanyBusinessUnitResponseTransfer($punchoutCatalogCollectionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PunchoutCatalogConnectionCollectionTransfer $punchoutCatalogConnectionCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitResponseTransfer
     */
    protected function createCompanyBusinessUnitResponseTransfer(
        PunchoutCatalogConnectionCollectionTransfer $punchoutCatalogConnectionCollectionTransfer
    ): CompanyBusinessUnitResponseTransfer
    {
        $companyBusinessUnitResponseTransfer = (new CompanyBusinessUnitResponseTransfer())->setIsSuccessful(true);

        if (!count($punchoutCatalogConnectionCollectionTransfer->getPunchoutCatalogConnection())) {
            return $companyBusinessUnitResponseTransfer;
        }

        $companyBusinessUnitResponseTransfer->setCompanyBusinessUnitTransfer(
            $punchoutCatalogConnectionCollectionTransfer
                ->getPunchoutCatalogConnection()[0]
                ->getCompanyBusinessUnit()
        );

        return $companyBusinessUnitResponseTransfer->setIsSuccessful(false);
    }
}
