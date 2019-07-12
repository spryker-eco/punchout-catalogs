<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\PunchoutCatalogs\Communication;

use Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer;
use Orm\Zed\PunchoutCatalog\Persistence\PgwPunchoutCatalogConnectionCartQuery;
use Orm\Zed\PunchoutCatalog\Persistence\PgwPunchoutCatalogConnectionQuery;
use Orm\Zed\PunchoutCatalog\Persistence\PgwPunchoutCatalogConnectionSetupQuery;
use Orm\Zed\PunchoutCatalog\Persistence\PgwPunchoutCatalogTransactionQuery;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use SprykerEco\Zed\PunchoutCatalogs\Communication\Form\DataProvider\PunchoutCatalogConnectionFormDataProvider;
use SprykerEco\Zed\PunchoutCatalogs\Communication\Form\DataProvider\PunchoutCatalogSetupRequestConnectionTypeFormDataProvider;
use SprykerEco\Zed\PunchoutCatalogs\Communication\Form\PunchoutCatalogConnectionForm;
use SprykerEco\Zed\PunchoutCatalogs\Communication\Table\PunchoutCatalogsConnectionsTable;
use SprykerEco\Zed\PunchoutCatalogs\Communication\Table\PunchoutCatalogsTransactionLogTable;
use SprykerEco\Zed\PunchoutCatalogs\Dependency\Facade\PunchoutCatalogsToCompanyBusinessUnitFacadeInterface;
use SprykerEco\Zed\PunchoutCatalogs\Dependency\Facade\PunchoutCatalogsToCompanyUserFacadeInterface;
use SprykerEco\Zed\PunchoutCatalogs\Dependency\Facade\PunchoutCatalogsToPunchoutCatalogFacadeInterface;
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
     * @return \SprykerEco\Zed\PunchoutCatalogs\Communication\Table\PunchoutCatalogsTransactionLogTable
     */
    public function createPunchoutCatalogsTransactionLogTable(): PunchoutCatalogsTransactionLogTable
    {
        return new PunchoutCatalogsTransactionLogTable(
            $this->getPunchoutCatalogTransactionPropelQuery(),
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
     * @return \SprykerEco\Zed\PunchoutCatalogs\Communication\Form\DataProvider\PunchoutCatalogConnectionFormDataProvider
     */
    public function createPunchoutCatalogConnectionFormDataProvider(): PunchoutCatalogConnectionFormDataProvider
    {
        return new PunchoutCatalogConnectionFormDataProvider(
            $this->getCompanyBusinessUnitFacade(),
            $this->getPunchoutCatalogConnectionFormatPlugins(),
            $this->getPunchoutCatalogConnectionTypePlugins()
        );
    }

    /**
     * @return \SprykerEco\Zed\PunchoutCatalogs\Communication\Form\DataProvider\PunchoutCatalogSetupRequestConnectionTypeFormDataProvider
     */
    public function createPunchoutCatalogSetupRequestConnectionTypeFormDataProvider(): PunchoutCatalogSetupRequestConnectionTypeFormDataProvider
    {
        return new PunchoutCatalogSetupRequestConnectionTypeFormDataProvider(
            $this->getCompanyBusinessUnitFacade(),
            $this->getCompanyUserFacade()
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
     * @return \Orm\Zed\PunchoutCatalog\Persistence\PgwPunchoutCatalogTransactionQuery
     */
    public function getPunchoutCatalogTransactionPropelQuery(): PgwPunchoutCatalogTransactionQuery
    {
        return $this->getProvidedDependency(PunchoutCatalogsDependencyProvider::PROPEL_QUERY_PUNCHOUT_CATALOG_TRANSACTION);
    }

    /**
     * @return \Orm\Zed\PunchoutCatalog\Persistence\PgwPunchoutCatalogConnectionSetupQuery
     */
    public function getPunchoutCatalogConnectionSetupPropelQuery(): PgwPunchoutCatalogConnectionSetupQuery
    {
        return $this->getProvidedDependency(PunchoutCatalogsDependencyProvider::PROPEL_QUERY_PUNCHOUT_CATALOG_CONNECTION_SETUP);
    }

    /**
     * @return \Orm\Zed\PunchoutCatalog\Persistence\PgwPunchoutCatalogConnectionCartQuery
     */
    public function getPunchoutCatalogConnectionCartPropelQuery(): PgwPunchoutCatalogConnectionCartQuery
    {
        return $this->getProvidedDependency(PunchoutCatalogsDependencyProvider::PROPEL_QUERY_PUNCHOUT_CATALOG_CONNECTION_CART);
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

    /**
     * @return \SprykerEco\Zed\PunchoutCatalogs\Dependency\Facade\PunchoutCatalogsToCompanyUserFacadeInterface
     */
    public function getCompanyUserFacade(): PunchoutCatalogsToCompanyUserFacadeInterface
    {
        return $this->getProvidedDependency(PunchoutCatalogsDependencyProvider::FACADE_COMPANY_USER);
    }

    /**
     * @return \SprykerEco\Zed\PunchoutCatalogs\Dependency\Facade\PunchoutCatalogsToPunchoutCatalogFacadeInterface
     */
    public function getPunchoutCatalogFacade(): PunchoutCatalogsToPunchoutCatalogFacadeInterface
    {
        return $this->getProvidedDependency(PunchoutCatalogsDependencyProvider::FACADE_PUNCHOUT_CATALOG);
    }

    /**
     * @return \SprykerEco\Zed\PunchoutCatalogs\Dependency\Plugin\PunchoutCatalogConnectionFormatPluginInterface[]
     */
    public function getPunchoutCatalogConnectionFormatPlugins(): array
    {
        return $this->getProvidedDependency(PunchoutCatalogsDependencyProvider::PLUGINS_PUNCHOUT_CATALOG_CONNECTION_FORMAT);
    }

    /**
     * @return \SprykerEco\Zed\PunchoutCatalogs\Dependency\Plugin\PunchoutCatalogConnectionTypePluginInterface[]
     */
    public function getPunchoutCatalogConnectionTypePlugins(): array
    {
        return $this->getProvidedDependency(PunchoutCatalogsDependencyProvider::PLUGINS_PUNCHOUT_CATALOG_CONNECTION_TYPE);
    }

    /**
     * @return \SprykerEco\Zed\PunchoutCatalogs\Dependency\Plugin\PunchoutCatalogSetupRequestFormExtensionPluginInterface[]
     */
    public function getPunchoutCatalogSetupRequestFormExtensionPlugins(): array
    {
        return $this->getProvidedDependency(PunchoutCatalogsDependencyProvider::PLUGINS_PUNCHOUT_CATALOG_SETUP_REQUEST_FORM_EXTENSION);
    }
}
