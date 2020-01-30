<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\PunchoutCatalogs\Business\Checker;

use Generated\Shared\Transfer\CompanyUserResponseTransfer;
use Generated\Shared\Transfer\PunchoutCatalogConnectionFilterTransfer;
use Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer;
use Generated\Shared\Transfer\ResponseMessageTransfer;
use SprykerEco\Zed\PunchoutCatalogs\Business\Reader\PunchoutCatalogsReaderInterface;

class CompanyUserDeleteChecker implements CompanyUserDeleteCheckerInterface
{
    protected const GLOSSARY_KEY_HAS_PUNCHOUT_CATALOG = 'company.account.company_user.delete.error.has_punchout_catalog';

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

        $punchoutCatalogConnectionFilter = (new PunchoutCatalogConnectionFilterTransfer())
            ->setIdCompanyUser($punchoutCatalogConnectionTransfer->getSetup()->getFkCompanyUser());

        $hasCompanyUserPunchoutCatalogConnection = $this->punchoutCatalogsReader
            ->hasPunchoutCatalogConnection($punchoutCatalogConnectionFilter);

        $companyUserResponseTransfer = new CompanyUserResponseTransfer();
        if (!$hasCompanyUserPunchoutCatalogConnection) {
            return $companyUserResponseTransfer->setIsSuccessful(true);
        }

        return $companyUserResponseTransfer
            ->addMessage((new ResponseMessageTransfer())->setText(static::GLOSSARY_KEY_HAS_PUNCHOUT_CATALOG))
            ->setIsSuccessful(false);
    }
}