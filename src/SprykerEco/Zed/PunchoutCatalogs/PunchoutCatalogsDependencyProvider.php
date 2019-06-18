<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\PunchoutCatalogs;

use Orm\Zed\PunchoutCatalog\Persistence\PgwPunchoutCatalogConnectionCartQuery;
use Orm\Zed\PunchoutCatalog\Persistence\PgwPunchoutCatalogConnectionQuery;
use Orm\Zed\PunchoutCatalog\Persistence\PgwPunchoutCatalogConnectionSetupQuery;
use Orm\Zed\PunchoutCatalog\Persistence\PgwPunchoutCatalogTransactionQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use SprykerEco\Zed\PunchoutCatalogs\Dependency\Facade\PunchoutCatalogsToCompanyBusinessUnitFacadeBridge;
use SprykerEco\Zed\PunchoutCatalogs\Dependency\Facade\PunchoutCatalogsToCompanyUserFacadeBridge;
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

    public const SERVICE_UTIL_DATE_TIME = 'SERVICE_UTIL_DATE_TIME';

    public const FACADE_COMPANY_BUSINESS_UNIT = 'FACADE_COMPANY_BUSINESS_UNIT';
    public const FACADE_COMPANY_USER = 'FACADE_COMPANY_USER';
    public const FACADE_VAULT = 'FACADE_VAULT';

    public const PLUGINS_CONNECTION_FORMAT = 'PLUGINS_CONNECTION_FORMAT';
    public const PLUGINS_CONNECTION_TYPE = 'PLUGINS_CONNECTION_TYPE';
    public const PLUGINS_SETUP_REQUEST_FORM_EXTENSION = 'PLUGINS_SETUP_REQUEST_FORM_EXTENSION';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        parent::provideCommunicationLayerDependencies($container);
        $container = $this->addUtilDateTimeService($container);
        $container = $this->addPunchoutCatalogConnectionPropelQuery($container);
        $container = $this->addPunchoutCatalogConnectionSetupPropelQuery($container);
        $container = $this->addPunchoutCatalogConnectionCartPropelQuery($container);
        $container = $this->addPunchoutCatalogTransactionPropelQuery($container);
        $container = $this->addCompanyBusinessUnitFacade($container);
        $container = $this->addCompanyUserFacade($container);
        $container = $this->addConnectionFormatPlugins($container);
        $container = $this->addConnectionTypePlugins($container);
        $container = $this->addSetupRequestFormExtensionPlugins($container);

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

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        parent::provideBusinessLayerDependencies($container);
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
    protected function addConnectionFormatPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_CONNECTION_FORMAT, function (Container $container) {
            return $this->getConnectionFormatPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addConnectionTypePlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_CONNECTION_TYPE, function (Container $container) {
            return $this->getConnectionTypePlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSetupRequestFormExtensionPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_SETUP_REQUEST_FORM_EXTENSION, function (Container $container) {
            return $this->getSetupRequestFormExtensionPlugins();
        });

        return $container;
    }

    /**
     * @return \SprykerEco\Zed\PunchoutCatalogs\Communication\Plugin\PunchoutCatalogConnectionFormatPluginInterface[]
     */
    protected function getConnectionFormatPlugins(): array
    {
        return [];
    }

    /**
     * @return \SprykerEco\Zed\PunchoutCatalogs\Communication\Plugin\PunchoutCatalogSetupRequestConnectionTypePlugin[]
     */
    protected function getConnectionTypePlugins(): array
    {
        return [];
    }

    /**
     * @return \SprykerEco\Zed\PunchoutCatalogs\Communication\Plugin\PunchoutCatalogSetupRequestFormExtensionPluginInterface[]
     */
    protected function getSetupRequestFormExtensionPlugins(): array
    {
        return [];
    }
}
