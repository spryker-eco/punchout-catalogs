<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\PunchoutCatalogs\Persistence;

use Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnitQuery;
use Orm\Zed\PunchoutCatalog\Persistence\PgwPunchoutCatalogConnectionCartQuery;
use Orm\Zed\PunchoutCatalog\Persistence\PgwPunchoutCatalogConnectionQuery;
use Orm\Zed\PunchoutCatalog\Persistence\PgwPunchoutCatalogConnectionSetupQuery;
use Orm\Zed\PunchoutCatalog\Persistence\PgwPunchoutCatalogTransactionQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use SprykerEco\Zed\PunchoutCatalogs\Persistence\Propel\Mapper\PunchoutCatalogsConnectionCartMapper;
use SprykerEco\Zed\PunchoutCatalogs\Persistence\Propel\Mapper\PunchoutCatalogsConnectionMapper;
use SprykerEco\Zed\PunchoutCatalogs\Persistence\Propel\Mapper\PunchoutCatalogsConnectionSetupMapper;
use SprykerEco\Zed\PunchoutCatalogs\PunchoutCatalogsDependencyProvider;

/**
 * @method \SprykerEco\Zed\PunchoutCatalogs\Persistence\PunchoutCatalogsEntityManagerInterface getEntityManager()
 * @method \SprykerEco\Zed\PunchoutCatalogs\Persistence\PunchoutCatalogsRepositoryInterface getRepository()
 * @method \SprykerEco\Zed\PunchoutCatalogs\PunchoutCatalogsConfig getConfig()
 */
class PunchoutCatalogsPersistenceFactory extends AbstractPersistenceFactory
{
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
     * @return \SprykerEco\Zed\PunchoutCatalogs\Persistence\Propel\Mapper\PunchoutCatalogsConnectionMapper
     */
    public function createPunchoutCatalogsConnectionMapper(): PunchoutCatalogsConnectionMapper
    {
        return new PunchoutCatalogsConnectionMapper(
            $this->createPunchoutCatalogsConnectionSetupMapper(),
            $this->createPunchoutCatalogsConnectionCartMapper()
        );
    }

    /**
     * @return \SprykerEco\Zed\PunchoutCatalogs\Persistence\Propel\Mapper\PunchoutCatalogsConnectionSetupMapper
     */
    public function createPunchoutCatalogsConnectionSetupMapper(): PunchoutCatalogsConnectionSetupMapper
    {
        return new PunchoutCatalogsConnectionSetupMapper();
    }

    /**
     * @return \SprykerEco\Zed\PunchoutCatalogs\Persistence\Propel\Mapper\PunchoutCatalogsConnectionCartMapper
     */
    public function createPunchoutCatalogsConnectionCartMapper(): PunchoutCatalogsConnectionCartMapper
    {
        return new PunchoutCatalogsConnectionCartMapper();
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
     * @return \Orm\Zed\PunchoutCatalog\Persistence\PgwPunchoutCatalogConnectionCartQuery
     */
    public function getCompanyBusinessUnitPropelQuery(): SpyCompanyBusinessUnitQuery
    {
        return $this->getProvidedDependency(PunchoutCatalogsDependencyProvider::PROPEL_QUERY_COMPANY_BUSINESS_UNIT);
    }
}
