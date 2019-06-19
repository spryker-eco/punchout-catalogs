<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\PunchoutCatalogs\Communication\Form\DataProvider;

use Generated\Shared\Transfer\CompanyBusinessUnitCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyUserCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use SprykerEco\Zed\PunchoutCatalogs\Dependency\Facade\PunchoutCatalogsToCompanyBusinessUnitFacadeInterface;
use SprykerEco\Zed\PunchoutCatalogs\Dependency\Facade\PunchoutCatalogsToCompanyUserFacadeInterface;

class PunchoutCatalogSetupRequestConnectionTypeFormDataProvider
{
    protected const LOGIN_MODE_SINGLE_USER = 'single_user';
    protected const LOGIN_MODE_DYNAMIC_USER = 'dynamic_user';

    protected const DEPENDENT_GROUP_LOGIN_MODE = 'login-mode';

    protected const KEY_RESULTS = 'results';
    protected const KEY_ID = 'id';
    protected const KEY_TEXT = 'text';

    /**
     * @var \SprykerEco\Zed\PunchoutCatalogs\Dependency\Facade\PunchoutCatalogsToCompanyBusinessUnitFacadeInterface
     */
    protected $companyBusinessUnitFacade;

    /**
     * @var \SprykerEco\Zed\PunchoutCatalogs\Dependency\Facade\PunchoutCatalogsToCompanyUserFacadeInterface
     */
    protected $companyUserFacade;

    /**
     * @param \SprykerEco\Zed\PunchoutCatalogs\Dependency\Facade\PunchoutCatalogsToCompanyBusinessUnitFacadeInterface $companyBusinessUnitFacade
     * @param \SprykerEco\Zed\PunchoutCatalogs\Dependency\Facade\PunchoutCatalogsToCompanyUserFacadeInterface $companyUserFacade
     */
    public function __construct(
        PunchoutCatalogsToCompanyBusinessUnitFacadeInterface $companyBusinessUnitFacade,
        PunchoutCatalogsToCompanyUserFacadeInterface $companyUserFacade
    ) {
        $this->companyBusinessUnitFacade = $companyBusinessUnitFacade;
        $this->companyUserFacade = $companyUserFacade;
    }

    /**
     * @param int $parentCompanyBusinessUnitId
     *
     * @return array
     */
    public function getCompanyBusinessUnitChoicesForSelect2(int $parentCompanyBusinessUnitId): array
    {
        $companyBusinessUnitChoicesForSelect2 = [];

        foreach ($this->getCompanyBusinessUnitChoices($parentCompanyBusinessUnitId) as $label => $idCompanyBusinessUnit) {
            $companyBusinessUnitChoicesForSelect2[] = [
                static::KEY_ID => $idCompanyBusinessUnit,
                static::KEY_TEXT => $label,
            ];
        }

        return [
            static::KEY_RESULTS => $companyBusinessUnitChoicesForSelect2,
        ];
    }

    /**
     * @param int $parentCompanyBusinessUnitId
     *
     * @return array
     */
    public function getCompanyUserChoicesForSelect2(int $parentCompanyBusinessUnitId): array
    {
        $companyUserChoicesForSelect2 = [];

        foreach ($this->getCompanyUserChoices($parentCompanyBusinessUnitId) as $label => $idCompanyUser) {
            $companyUserChoicesForSelect2[] = [
                static::KEY_ID => $idCompanyUser,
                static::KEY_TEXT => $label,
            ];
        }

        return [
            static::KEY_RESULTS => $companyUserChoicesForSelect2,
        ];
    }

    /**
     * @param int $parentCompanyBusinessUnitId
     *
     * @return int[]
     */
    public function getCompanyBusinessUnitChoices(int $parentCompanyBusinessUnitId): array
    {
        $parentCompanyBusinessUnitTransfer = $this->companyBusinessUnitFacade->findCompanyBusinessUnitById($parentCompanyBusinessUnitId);

        if (!$parentCompanyBusinessUnitTransfer) {
            return [];
        }

        $companyBusinessUnitCollectionTransfer = $this->companyBusinessUnitFacade->getCompanyBusinessUnitCollection(
            (new CompanyBusinessUnitCriteriaFilterTransfer())
                ->setParentCompanyBusinessUnitId($parentCompanyBusinessUnitId)
        );

        $companyBusinessUnitTransfers = $companyBusinessUnitCollectionTransfer->getCompanyBusinessUnits()
            ?: [$parentCompanyBusinessUnitTransfer];

        $companyBusinessUnitChoices = [];

        foreach ($companyBusinessUnitTransfers as $companyBusinessUnitTransfer) {
            $companyBusinessUnitChoices[$this->generateCompanyBusinessUnitChoiceLabel($companyBusinessUnitTransfer)]
                = $companyBusinessUnitTransfer->getIdCompanyBusinessUnit();
        }

        return $companyBusinessUnitChoices;
    }

    /**
     * @param int $parentCompanyBusinessUnitId
     *
     * @return int[]
     */
    public function getCompanyUserChoices(int $parentCompanyBusinessUnitId): array
    {
        $companyUserIds = $this->companyBusinessUnitFacade->getCompanyUserIdsByIdCompanyBusinessUnit($parentCompanyBusinessUnitId);

        if (!$companyUserIds) {
            return $companyUserIds;
        }

        $companyUserCollectionTransfer = $this->companyUserFacade->getCompanyUserCollection(
            (new CompanyUserCriteriaFilterTransfer())
                    ->setCompanyUserIds($companyUserIds)
        );

        $companyUsers = [];

        foreach ($companyUserCollectionTransfer->getCompanyUsers() as $companyUserTransfer) {
            $companyUsers[$this->generateCompanyUserChoiceLabel($companyUserTransfer)] = $companyUserTransfer->getIdCompanyUser();
        }

        return $companyUsers;
    }

    /**
     * @return string[]
     */
    public function getCartEncodingChoices(): array
    {
        return [
            'base64' => 'base64',
            'url-encoded' => 'url-encoded',
            'no-encoding' => 'no-encoding',
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return string
     */
    protected function generateCompanyBusinessUnitChoiceLabel(CompanyBusinessUnitTransfer $companyBusinessUnitTransfer): string
    {
        return sprintf(
            '%s - %s',
            $companyBusinessUnitTransfer->getName(),
            $companyBusinessUnitTransfer->getCompany()
                ->getName()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return string
     */
    protected function generateCompanyUserChoiceLabel(CompanyUserTransfer $companyUserTransfer): string
    {
        $customerTransfer = $companyUserTransfer->getCustomer();

        return sprintf(
            '%s %s (%s)',
            $customerTransfer->getFirstName(),
            $customerTransfer->getLastName(),
            $customerTransfer->getEmail()
        );
    }
}
