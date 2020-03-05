<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\PunchoutCatalogs\Business\Checker;

use Generated\Shared\Transfer\CompanyBusinessUnitResponseTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\PunchoutCatalogConnectionFilterTransfer;
use Generated\Shared\Transfer\ResponseMessageTransfer;
use SprykerEco\Zed\PunchoutCatalogs\Persistence\PunchoutCatalogsRepositoryInterface;

class CompanyBusinessUnitDeleteChecker implements CompanyBusinessUnitDeleteCheckerInterface
{
    protected const GLOSSARY_KEY_COMPANY_BUSINESS_UNIT_IS_USED = 'punchout_catalogs.error.company_business_unit.is_used';

    protected const ERROR_MESSAGE_PARAM_UNIT = '%unit%';

    /**
     * @var SprykerEco\Zed\PunchoutCatalogs\Persistence\PunchoutCatalogsRepositoryInterface
     */
    protected $punchoutCatalogsRepository;

    /**
     * @param \SprykerEco\Zed\PunchoutCatalogs\Persistence\PunchoutCatalogsRepositoryInterface $punchoutCatalogsRepository
     */
    public function __construct(PunchoutCatalogsRepositoryInterface $punchoutCatalogsRepository)
    {
        $this->punchoutCatalogsRepository = $punchoutCatalogsRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitResponseTransfer
     */
    public function isCompanyBusinessUnitDeletable(CompanyBusinessUnitTransfer $companyBusinessUnitTransfer): CompanyBusinessUnitResponseTransfer
    {
        $punchoutCatalogConnectionFilterTransfer = (new PunchoutCatalogConnectionFilterTransfer())
            ->setIdCompanyBusinessUnit($companyBusinessUnitTransfer->getIdCompanyBusinessUnit());

        $isPunchoutCatalogConnectionExists = $this->punchoutCatalogsRepository
            ->isPunchoutCatalogConnectionExists($punchoutCatalogConnectionFilterTransfer);

        $companyBusinessUnitResponseTransfer = new CompanyBusinessUnitResponseTransfer();
        if (!$isPunchoutCatalogConnectionExists) {
            return $companyBusinessUnitResponseTransfer->setIsSuccessful(true);
        }

        return $companyBusinessUnitResponseTransfer
            ->addMessage($this->getResponseMessageTransfer($companyBusinessUnitTransfer))
            ->setIsSuccessful(false);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return \Generated\Shared\Transfer\ResponseMessageTransfer
     */
    protected function getResponseMessageTransfer(CompanyBusinessUnitTransfer $companyBusinessUnitTransfer): ResponseMessageTransfer
    {
        return (new ResponseMessageTransfer())
            ->setText(static::GLOSSARY_KEY_COMPANY_BUSINESS_UNIT_IS_USED)
            ->setParameters(
                [
                    static::ERROR_MESSAGE_PARAM_UNIT => $companyBusinessUnitTransfer->getName()
                ]
            );
    }
}
