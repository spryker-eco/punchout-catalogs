<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\PunchoutCatalogs\Communication\Form\DataProvider;

use Generated\Shared\Transfer\CompanyBusinessUnitCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer;
use SprykerEco\Zed\PunchoutCatalogs\Communication\Form\PunchoutCatalogConnectionForm;
use SprykerEco\Zed\PunchoutCatalogs\Dependency\Facade\PunchoutCatalogsToCompanyBusinessUnitFacadeInterface;

class PunchoutCatalogConnectionFormDataProvider
{
    /**
     * @var \SprykerEco\Zed\PunchoutCatalogs\Dependency\Facade\PunchoutCatalogsToCompanyBusinessUnitFacadeInterface
     */
    protected $companyBusinessUnitFacade;

    /**
     * @var \SprykerEco\Zed\PunchoutCatalogs\Communication\Plugin\PunchoutCatalogConnectionFormatPluginInterface[]
     */
    protected $connectionFormatPlugins;

    /**
     * @var \SprykerEco\Zed\PunchoutCatalogs\Communication\Plugin\PunchoutCatalogConnectionTypePluginInterface[]
     */
    protected $connectionTypePlugins;

    /**
     * @param \SprykerEco\Zed\PunchoutCatalogs\Dependency\Facade\PunchoutCatalogsToCompanyBusinessUnitFacadeInterface $companyBusinessUnitFacade
     * @param \SprykerEco\Zed\PunchoutCatalogs\Communication\Plugin\PunchoutCatalogConnectionFormatPluginInterface[] $connectionFormatPlugins
     * @param \SprykerEco\Zed\PunchoutCatalogs\Communication\Plugin\PunchoutCatalogConnectionTypePluginInterface[] $connectionTypePlugins
     */
    public function __construct(PunchoutCatalogsToCompanyBusinessUnitFacadeInterface $companyBusinessUnitFacade, array $connectionFormatPlugins, array $connectionTypePlugins)
    {
        $this->companyBusinessUnitFacade = $companyBusinessUnitFacade;
        $this->connectionFormatPlugins = $connectionFormatPlugins;
        $this->connectionTypePlugins = $connectionTypePlugins;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return [
            PunchoutCatalogConnectionForm::OPTION_BUSINESS_UNIT_CHOICES => $this->prepareCompanyBusinessUnitChoices(),
            PunchoutCatalogConnectionForm::OPTION_CONNECTION_FORMAT_SUB_FORM_TYPES => $this->prepareConnectionFormatSubForms(),
            PunchoutCatalogConnectionForm::OPTION_CONNECTION_TYPE_SUB_FORM_TYPES => $this->prepareConnectionTypeSubForms(),
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

        return (new PunchoutCatalogConnectionTransfer())
            ->setIsActive(true);
    }

    /**
     * @return int[] [label => idBusinessUnit]
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
     * @return string[] [connectionFormat => FormTypePath]
     */
    protected function prepareConnectionFormatSubForms(): array
    {
        $connectionFormatSubForms = [];

        foreach ($this->connectionFormatPlugins as $connectionFormatPlugin) {
            $connectionFormatSubForms[$connectionFormatPlugin->getConnectionFormat()] = $connectionFormatPlugin->getType();
        }

        return $connectionFormatSubForms;
    }

    /**
     * @return string[] [connectionType => FormTypePath]
     */
    protected function prepareConnectionTypeSubForms(): array
    {
        $connectionTypeSubForms = [];

        foreach ($this->connectionTypePlugins as $connectionTypePlugin) {
            $connectionTypeSubForms[$connectionTypePlugin->getConnectionType()] = $connectionTypePlugin->getType();
        }

        return $connectionTypeSubForms;
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
