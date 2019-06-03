<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\PunchoutCatalogs\Communication\Form;

use Generated\Shared\Transfer\CompanyBusinessUnitCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer;
use SprykerEco\Zed\PunchoutCatalogs\Dependency\Facade\PunchoutCatalogsToCompanyBusinessUnitFacadeInterface;

class PunchoutCatalogConnectionFormDataProvider
{
    /**
     * @var \SprykerEco\Zed\PunchoutCatalogs\Dependency\Facade\PunchoutCatalogsToCompanyBusinessUnitFacadeInterface
     */
    protected $companyBusinessUnitFacade;

    /**
     * @param \SprykerEco\Zed\PunchoutCatalogs\Dependency\Facade\PunchoutCatalogsToCompanyBusinessUnitFacadeInterface $companyBusinessUnitFacade
     */
    public function __construct(PunchoutCatalogsToCompanyBusinessUnitFacadeInterface $companyBusinessUnitFacade)
    {
        $this->companyBusinessUnitFacade = $companyBusinessUnitFacade;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return [
            PunchoutCatalogConnectionForm::OPTION_BUSINESS_UNIT_CHOICES => $this->prepareCompanyBusinessUnitChoices(),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer|null $punchoutCatalogConnectionTransfer
     *
     * @return \Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer
     */
    public function getData(?PunchoutCatalogConnectionTransfer $punchoutCatalogConnectionTransfer = null): PunchoutCatalogConnectionTransfer
    {
        if ($punchoutCatalogConnectionTransfer) {
            return $punchoutCatalogConnectionTransfer;
        }

        /**
         * @todo Remove those fields when functionality for it's saving will be implemented.
         */
        return (new PunchoutCatalogConnectionTransfer())
            ->setIsActive(true)
            ->setType('NOT_IMPLEMENTED')
            ->setFormat('NOT_IMPLEMENTED')
            ->setUsername('NOT_IMPLEMENTED');
    }

    /**
     * @return string[] [idBusinessUnit => label]
     */
    protected function prepareCompanyBusinessUnitChoices(): array
    {
        $companyBusinessUnitCollection = $this->companyBusinessUnitFacade->getCompanyBusinessUnitCollection(
            (new CompanyBusinessUnitCriteriaFilterTransfer())
        );

        $companyBusinessUnitChoices = [];

        foreach ($companyBusinessUnitCollection->getCompanyBusinessUnits() as $companyBusinessUnitTransfer) {
            $companyBusinessUnitChoices[$this->buildCompanyBusinessUnitLabel($companyBusinessUnitTransfer)]
                = $companyBusinessUnitTransfer->getIdCompanyBusinessUnit();
        }

        return $companyBusinessUnitChoices;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return string
     */
    protected function buildCompanyBusinessUnitLabel(CompanyBusinessUnitTransfer $companyBusinessUnitTransfer): string
    {
        return sprintf(
            '%s-%s',
            $companyBusinessUnitTransfer->getCompany()
                ->getName(),
            $companyBusinessUnitTransfer->getName()
        );
    }
}
