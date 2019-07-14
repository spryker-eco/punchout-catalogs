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
use SprykerEco\Zed\PunchoutCatalogs\Persistence\PunchoutCatalogsRepositoryInterface;

class PunchoutCatalogSetupRequestConnectionTypeFormDataProvider
{
    protected const TOGGLE_GROUP_LOGIN_MODE = 'login-mode';

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
     * @var \SprykerEco\Zed\PunchoutCatalogs\Persistence\PunchoutCatalogsRepositoryInterface
     */
    protected $punchoutCatalogsRepository;

    /**
     * @param \SprykerEco\Zed\PunchoutCatalogs\Dependency\Facade\PunchoutCatalogsToCompanyBusinessUnitFacadeInterface $companyBusinessUnitFacade
     * @param \SprykerEco\Zed\PunchoutCatalogs\Dependency\Facade\PunchoutCatalogsToCompanyUserFacadeInterface $companyUserFacade
     * @param \SprykerEco\Zed\PunchoutCatalogs\Persistence\PunchoutCatalogsRepositoryInterface $punchoutCatalogsRepository
     */
    public function __construct(
        PunchoutCatalogsToCompanyBusinessUnitFacadeInterface $companyBusinessUnitFacade,
        PunchoutCatalogsToCompanyUserFacadeInterface $companyUserFacade,
        PunchoutCatalogsRepositoryInterface $punchoutCatalogsRepository
    ) {
        $this->companyBusinessUnitFacade = $companyBusinessUnitFacade;
        $this->companyUserFacade = $companyUserFacade;
        $this->punchoutCatalogsRepository = $punchoutCatalogsRepository;
    }

    /**
     * @param int $parentCompanyBusinessUnitId
     *
     * @return array
     */
    public function getFormattedCompanyBusinessUnitChoices(int $parentCompanyBusinessUnitId): array
    {
        $formattedCompanyBusinessUnitChoices = [];

        foreach ($this->getCompanyBusinessUnitChoices($parentCompanyBusinessUnitId) as $label => $idCompanyBusinessUnit) {
            $formattedCompanyBusinessUnitChoices[] = [
                static::KEY_ID => $idCompanyBusinessUnit,
                static::KEY_TEXT => $label,
            ];
        }

        return [
            static::KEY_RESULTS => $formattedCompanyBusinessUnitChoices,
        ];
    }

    /**
     * @param int $parentCompanyBusinessUnitId
     *
     * @return array
     */
    public function getFormattedCompanyUserChoices(int $parentCompanyBusinessUnitId): array
    {
        $formattedCompanyUserChoices = [];

        foreach ($this->getCompanyUserChoices($parentCompanyBusinessUnitId) as $label => $idCompanyUser) {
            $formattedCompanyUserChoices[] = [
                static::KEY_ID => $idCompanyUser,
                static::KEY_TEXT => $label,
            ];
        }

        return [
            static::KEY_RESULTS => $formattedCompanyUserChoices,
        ];
    }

    /**
     * @param int $parentCompanyBusinessUnitId
     *
     * @return int[]
     */
    public function getCompanyBusinessUnitChoices(int $parentCompanyBusinessUnitId): array
    {
        $companyBusinessUnitTransfers = $this->getCompanyBusinessUnits($parentCompanyBusinessUnitId);

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
        $companyUserIds = $this->punchoutCatalogsRepository->getActiveCompanyUserIdsByIdCompanyBusinessUnit($parentCompanyBusinessUnitId);

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
            'No Encoding' => 'no-encoding',
        ];
    }

    /**
     * @return string[]
     */
    public function getBundleModeChoices(): array
    {
        return [
            'Composite' => 'composite',
            'Single' => 'single',
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
            $companyBusinessUnitTransfer->getCompany()
                ->getName(),
            $companyBusinessUnitTransfer->getName()
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

    /**
     * @param int $parentCompanyBusinessUnitId
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitTransfer[]
     */
    protected function getCompanyBusinessUnits(int $parentCompanyBusinessUnitId): array
    {
        $parentCompanyBusinessUnitTransfer = $this->companyBusinessUnitFacade->findCompanyBusinessUnitById($parentCompanyBusinessUnitId);

        if (!$parentCompanyBusinessUnitTransfer) {
            return [];
        }

        $companyBusinessUnitIds = $this->punchoutCatalogsRepository->getActiveCompanyBusinessUnitIdsByParentCompanyBuesinessUnitId($parentCompanyBusinessUnitId);

        if (!$companyBusinessUnitIds) {
            return [$parentCompanyBusinessUnitTransfer];
        }

        $companyBusinessUnitCollectionTransfer = $this->companyBusinessUnitFacade->getCompanyBusinessUnitCollection(
            (new CompanyBusinessUnitCriteriaFilterTransfer())
                ->setCompanyBusinessUnitIds($companyBusinessUnitIds)
        );

        return $companyBusinessUnitCollectionTransfer->getCompanyBusinessUnits()
            ->getArrayCopy();
    }
}
