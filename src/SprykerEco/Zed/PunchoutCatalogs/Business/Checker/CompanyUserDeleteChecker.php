<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\PunchoutCatalogs\Business\Checker;

use Generated\Shared\Transfer\CompanyUserResponseTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\PunchoutCatalogConnectionFilterTransfer;
use Generated\Shared\Transfer\ResponseMessageTransfer;
use SprykerEco\Zed\PunchoutCatalogs\Persistence\PunchoutCatalogsRepositoryInterface;

class CompanyUserDeleteChecker implements CompanyUserDeleteCheckerInterface
{
    protected const GLOSSARY_KEY_COMPANY_USER_IS_USED = 'punchout_catalogs.error.company_user.is_used';

    /**
     * @var \SprykerEco\Zed\PunchoutCatalogs\Persistence\PunchoutCatalogsRepositoryInterface
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
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserResponseTransfer
     */
    public function isCompanyUserDeletable(CompanyUserTransfer $companyUserTransfer): CompanyUserResponseTransfer
    {
        $punchoutCatalogConnectionFilterTransfer = (new PunchoutCatalogConnectionFilterTransfer())
            ->setIdCompanyUser($companyUserTransfer->getIdCompanyUser());

        $isPunchoutCatalogConnectionExists = $this->punchoutCatalogsRepository
            ->isPunchoutCatalogConnectionExists($punchoutCatalogConnectionFilterTransfer);

        $companyUserResponseTransfer = new CompanyUserResponseTransfer();
        if (!$isPunchoutCatalogConnectionExists) {
            return $companyUserResponseTransfer->setIsSuccessful(true);
        }

        return $companyUserResponseTransfer
            ->addMessage($this->getResponseMessageTransfer($companyUserTransfer))
            ->setIsSuccessful(false);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\ResponseMessageTransfer
     */
    protected function getResponseMessageTransfer(CompanyUserTransfer $companyUserTransfer): ResponseMessageTransfer
    {
        return (new ResponseMessageTransfer())
            ->setText(static::GLOSSARY_KEY_COMPANY_USER_IS_USED)
            ->setParameters(
                [
                    '%customer_name%' => $this->getCustomerFullName($companyUserTransfer->getCustomer())
                ]
            );
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return string
     */
    protected function getCustomerFullName(CustomerTransfer $customerTransfer): string
    {
        return sprintf(
            '%s %s',
            $customerTransfer->getFirstName(),
            $customerTransfer->getLastName()
        );
    }
}
