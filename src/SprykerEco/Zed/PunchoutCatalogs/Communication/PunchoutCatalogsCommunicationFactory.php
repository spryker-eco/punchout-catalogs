<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\PunchoutCatalogs\Communication;

use Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer;
use Orm\Zed\PunchoutCatalog\Persistence\PgwPunchoutCatalogConnectionQuery;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use SprykerEco\Zed\PunchoutCatalogs\Communication\Form\PunchoutCatalogConnectionForm;
use SprykerEco\Zed\PunchoutCatalogs\Communication\Form\PunchoutCatalogConnectionFormDataProvider;
use SprykerEco\Zed\PunchoutCatalogs\Communication\Table\PunchoutCatalogsConnectionsTable;
use SprykerEco\Zed\PunchoutCatalogs\Dependency\Facade\PunchoutCatalogsToCompanyBusinessUnitFacadeInterface;
use SprykerEco\Zed\PunchoutCatalogs\Dependency\Service\PunchoutCatalogsToUtilDateTimeServiceInterface;
use SprykerEco\Zed\PunchoutCatalogs\PunchoutCatalogsDependencyProvider;
use Symfony\Component\Form\FormInterface;

/**
 * @method \SprykerEco\Zed\PunchoutCatalogs\Persistence\PunchoutCatalogsRepositoryInterface getRepository()
 * @method \SprykerEco\Zed\PunchoutCatalogs\Persistence\PunchoutCatalogsEntityManagerInterface getEntityManager()
 * @method \SprykerEco\Zed\PunchoutCatalogs\Business\PunchoutCatalogsFacadeInterface getFacade()
 * @method \SprykerEco\Zed\PunchoutCatalogs\PunchoutCatalogsConfig getConfig()
 */
class PunchoutCatalogsCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \SprykerEco\Zed\PunchoutCatalogs\Communication\Table\PunchoutCatalogsConnectionsTable
     */
    public function createPunchoutCatalogsConnectionsTable(): PunchoutCatalogsConnectionsTable
    {
        return new PunchoutCatalogsConnectionsTable(
            $this->getPunchoutCatalogConnectionPropelQuery(),
            $this->getUtilDateTimeService()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer|null $punchoutCatalogConnectionTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getPunchoutCatalogConnectionForm(?PunchoutCatalogConnectionTransfer $punchoutCatalogConnectionTransfer = null): FormInterface
    {
        $idPunchoutCatalogConnectionFormDataProvider = $this->createPunchoutCatalogConnectionFormDataProvider();

        return $this->getFormFactory()
            ->create(
                PunchoutCatalogConnectionForm::class,
                $idPunchoutCatalogConnectionFormDataProvider->getData($punchoutCatalogConnectionTransfer),
                $idPunchoutCatalogConnectionFormDataProvider->getOptions()
            );
    }

    /**
     * @return \SprykerEco\Zed\PunchoutCatalogs\Communication\Form\PunchoutCatalogConnectionFormDataProvider
     */
    public function createPunchoutCatalogConnectionFormDataProvider(): PunchoutCatalogConnectionFormDataProvider
    {
        return new PunchoutCatalogConnectionFormDataProvider(
            $this->getCompanyBusinessUnitFacade()
        );
    }

    /**
     * @return \Orm\Zed\PunchoutCatalog\Persistence\PgwPunchoutCatalogConnectionQuery
     */
    public function getPunchoutCatalogConnectionPropelQuery(): PgwPunchoutCatalogConnectionQuery
    {
        return $this->getProvidedDependency(PunchoutCatalogsDependencyProvider::PROPEL_QUERY_PUNCHOUT_CATALOG_CONNECTION);
    }

    /**
     * @return \SprykerEco\Zed\PunchoutCatalogs\Dependency\Service\PunchoutCatalogsToUtilDateTimeServiceInterface
     */
    public function getUtilDateTimeService(): PunchoutCatalogsToUtilDateTimeServiceInterface
    {
        return $this->getProvidedDependency(PunchoutCatalogsDependencyProvider::SERVICE_UTIL_DATE_TIME);
    }

    /**
     * @return \SprykerEco\Zed\PunchoutCatalogs\Dependency\Facade\PunchoutCatalogsToCompanyBusinessUnitFacadeInterface
     */
    public function getCompanyBusinessUnitFacade(): PunchoutCatalogsToCompanyBusinessUnitFacadeInterface
    {
        return $this->getProvidedDependency(PunchoutCatalogsDependencyProvider::FACADE_COMPANY_BUSINESS_UNIT);
    }
}
