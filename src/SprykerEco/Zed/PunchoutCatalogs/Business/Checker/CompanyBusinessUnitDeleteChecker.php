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
use Generated\Shared\Transfer\PunchoutCatalogConnectionFilterTransfer;
use Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer;
use Generated\Shared\Transfer\PunchoutCatalogResponseTransfer;
use Generated\Shared\Transfer\PunchoutCatalogSetupResponseTransfer;
use Generated\Shared\Transfer\ResponseMessageTransfer;
use SprykerEco\Zed\PunchoutCatalogs\Business\Reader\PunchoutCatalogsReaderInterface;

class CompanyBusinessUnitDeleteChecker implements CompanyBusinessUnitDeleteCheckerInterface
{
    protected const GLOSSARY_KEY_HAS_PUNCHOUT_CATALOG = 'company.company_business_unit.delete.error.has_punchout_catalog';

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
        $punchoutCatalogConnectionTransfer->requireFkCompanyBusinessUnit();

        $punchoutCatalogConnectionFilter = (new PunchoutCatalogConnectionFilterTransfer())
            ->setIdCompanyBusinessUnit($punchoutCatalogConnectionTransfer->getFkCompanyBusinessUnit());

        $hasCompanyBusinessUnitPunchoutCatalogConnection = $this->punchoutCatalogsReader
            ->hasPunchoutCatalogConnection($punchoutCatalogConnectionFilter);

        $companyBusinessUnitResponseTransfer = new CompanyBusinessUnitResponseTransfer();
        if (!$hasCompanyBusinessUnitPunchoutCatalogConnection) {
            return $companyBusinessUnitResponseTransfer->setIsSuccessful(true);
        }

        return $companyBusinessUnitResponseTransfer
            ->addMessage((new ResponseMessageTransfer())->setText(static::GLOSSARY_KEY_HAS_PUNCHOUT_CATALOG))
            ->setIsSuccessful(false);
    }
}
