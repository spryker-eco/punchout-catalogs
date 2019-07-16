<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\PunchoutCatalogs;

use Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnitQuery;
use Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery;
use Orm\Zed\PunchoutCatalog\Persistence\PgwPunchoutCatalogConnectionCartQuery;
use Orm\Zed\PunchoutCatalog\Persistence\PgwPunchoutCatalogConnectionQuery;
use Orm\Zed\PunchoutCatalog\Persistence\PgwPunchoutCatalogConnectionSetupQuery;
use Orm\Zed\PunchoutCatalog\Persistence\PgwPunchoutCatalogTransactionQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use SprykerEco\Zed\PunchoutCatalogs\Dependency\Facade\PunchoutCatalogsToCompanyBusinessUnitFacadeBridge;
use SprykerEco\Zed\PunchoutCatalogs\Dependency\Facade\PunchoutCatalogsToCompanyUserFacadeBridge;
use SprykerEco\Zed\PunchoutCatalogs\Dependency\Facade\PunchoutCatalogsToPunchoutCatalogBridge;
use SprykerEco\Zed\PunchoutCatalogs\Dependency\Facade\PunchoutCatalogsToVaultFacadeBridge;
use SprykerEco\Zed\PunchoutCatalogs\Dependency\Service\PunchoutCatalogsToUtilDateTimeServiceBridge;

/**
 * @method \SprykerEco\Zed\PunchoutCatalogs\PunchoutCatalogsConfig getConfig()
 */
class PunchoutCatalogsDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PROPEL_QUERY_PUNCHOUT_CATALOG_CONNECTION = 'PROPEL_QUERY_PUNCHOUT_CATALOG_CONNECTION';
    public const PROPEL_QUERY_PUNCHOUT_CATALOG_CONNECTION_SETUP = 'PROPEL_QUERY_PUNCHOUT_CATALOG_CONNECTION_SETUP';
    public const PROPEL_QUERY_PUNCHOUT_CATALOG_CONNECTION_CART = 'PROPEL_QUERY_PUNCHOUT_CATALOG_CONNECTION_CART';
    public const PROPEL_QUERY_PUNCHOUT_CATALOG_TRANSACTION = 'PROPEL_QUERY_PUNCHOUT_CATALOG_TRANSACTION';
    public const PROPEL_QUERY_COMPANY_BUSINESS_UNIT = 'PROPEL_QUERY_COMPANY_BUSINESS_UNIT';
    public const PROPEL_QUERY_COMPANY_USER = 'PROPEL_QUERY_COMPANY_USER';

    public const SERVICE_UTIL_DATE_TIME = 'SERVICE_UTIL_DATE_TIME';

    public const FACADE_COMPANY_BUSINESS_UNIT = 'FACADE_COMPANY_BUSINESS_UNIT';
    public const FACADE_COMPANY_USER = 'FACADE_COMPANY_USER';
    public const FACADE_VAULT = 'FACADE_VAULT';
    public const FACADE_PUNCHOUT_CATALOG = 'FACADE_PUNCHOUT_CATALOG';

    public const PLUGINS_PUNCHOUT_CATALOG_CONNECTION_FORMAT = 'PLUGINS_PUNCHOUT_CATALOG_CONNECTION_FORMAT';
    public const PLUGINS_PUNCHOUT_CATALOG_CONNECTION_TYPE = 'PLUGINS_PUNCHOUT_CATALOG_CONNECTION_TYPE';
    public const PLUGINS_PUNCHOUT_CATALOG_SETUP_REQUEST_FORM_EXTENSION = 'PLUGINS_PUNCHOUT_CATALOG_SETUP_REQUEST_FORM_EXTENSION';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);
        $container = $this->addUtilDateTimeService($container);
        $container = $this->addPunchoutCatalogConnectionPropelQuery($container);
        $container = $this->addPunchoutCatalogConnectionSetupPropelQuery($container);
        $container = $this->addPunchoutCatalogConnectionCartPropelQuery($container);
        $container = $this->addPunchoutCatalogTransactionPropelQuery($container);
        $container = $this->addCompanyBusinessUnitFacade($container);
        $container = $this->addCompanyUserFacade($container);
        $container = $this->addPunchoutCatalogFacade($container);
        $container = $this->addPunchoutCatalogConnectionFormatPlugins($container);
        $container = $this->addPunchoutCatalogConnectionTypePlugins($container);
        $container = $this->addPunchoutCatalogSetupRequestFormExtensionPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container): Container
    {
        $container = parent::providePersistenceLayerDependencies($container);
        $container = $this->addPunchoutCatalogConnectionPropelQuery($container);
        $container = $this->addPunchoutCatalogConnectionSetupPropelQuery($container);
        $container = $this->addPunchoutCatalogConnectionCartPropelQuery($container);
        $container = $this->addPunchoutCatalogTransactionPropelQuery($container);
        $container = $this->addCompanyBusinessUnitPropelQuery($container);
        $container = $this->addCompanyUserPropelQuery($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addVaultFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilDateTimeService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_DATE_TIME, function (Container $container) {
            return new PunchoutCatalogsToUtilDateTimeServiceBridge(
                $container->getLocator()->utilDateTime()->service()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPunchoutCatalogConnectionPropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_PUNCHOUT_CATALOG_CONNECTION, function () {
            return PgwPunchoutCatalogConnectionQuery::create();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPunchoutCatalogConnectionSetupPropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_PUNCHOUT_CATALOG_CONNECTION_SETUP, function () {
            return PgwPunchoutCatalogConnectionSetupQuery::create();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPunchoutCatalogConnectionCartPropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_PUNCHOUT_CATALOG_CONNECTION_CART, function () {
            return PgwPunchoutCatalogConnectionCartQuery::create();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPunchoutCatalogTransactionPropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_PUNCHOUT_CATALOG_TRANSACTION, function () {
            return PgwPunchoutCatalogTransactionQuery::create();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCompanyBusinessUnitFacade(Container $container): Container
    {
        $container->set(static::FACADE_COMPANY_BUSINESS_UNIT, function (Container $container) {
            return new PunchoutCatalogsToCompanyBusinessUnitFacadeBridge(
                $container->getLocator()->companyBusinessUnit()->facade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCompanyUserFacade(Container $container): Container
    {
        $container->set(static::FACADE_COMPANY_USER, function (Container $container) {
            return new PunchoutCatalogsToCompanyUserFacadeBridge(
                $container->getLocator()->companyUser()->facade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addVaultFacade(Container $container): Container
    {
        $container->set(static::FACADE_VAULT, function (Container $container) {
            return new PunchoutCatalogsToVaultFacadeBridge(
                $container->getLocator()->vault()->facade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPunchoutCatalogFacade(Container $container): Container
    {
        $container->set(static::FACADE_PUNCHOUT_CATALOG, function (Container $container) {
            return new PunchoutCatalogsToPunchoutCatalogBridge(
                $container->getLocator()->punchoutCatalog()->facade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPunchoutCatalogConnectionFormatPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_PUNCHOUT_CATALOG_CONNECTION_FORMAT, function (Container $container) {
            return $this->getPunchoutCatalogConnectionFormatPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPunchoutCatalogConnectionTypePlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_PUNCHOUT_CATALOG_CONNECTION_TYPE, function (Container $container) {
            return $this->getPunchoutCatalogConnectionTypePlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPunchoutCatalogSetupRequestFormExtensionPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_PUNCHOUT_CATALOG_SETUP_REQUEST_FORM_EXTENSION, function (Container $container) {
            return $this->getPunchoutCatalogSetupRequestFormExtensionPlugins();
        });

        return $container;
    }

    /**
     * @return \SprykerEco\Zed\PunchoutCatalogs\Dependency\Plugin\PunchoutCatalogConnectionFormatPluginInterface[]
     */
    protected function getPunchoutCatalogConnectionFormatPlugins(): array
    {
        return [];
    }

    /**
     * @return \SprykerEco\Zed\PunchoutCatalogs\Dependency\Plugin\PunchoutCatalogConnectionTypePluginInterface[]
     */
    protected function getPunchoutCatalogConnectionTypePlugins(): array
    {
        return [];
    }

    /**
     * @return \SprykerEco\Zed\PunchoutCatalogs\Dependency\Plugin\PunchoutCatalogSetupRequestFormExtensionPluginInterface[]
     */
    protected function getPunchoutCatalogSetupRequestFormExtensionPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCompanyBusinessUnitPropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_COMPANY_BUSINESS_UNIT, function (Container $container) {
            return SpyCompanyBusinessUnitQuery::create();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCompanyUserPropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_COMPANY_USER, function (Container $container) {
            return SpyCompanyUserQuery::create();
        });

        return $container;
    }
}
