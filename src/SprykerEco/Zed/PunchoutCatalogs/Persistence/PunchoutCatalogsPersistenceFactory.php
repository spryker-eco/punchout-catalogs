<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\PunchoutCatalogs\Persistence;

use Orm\Zed\PunchoutCatalog\Persistence\PgwPunchoutCatalogConnectionQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use SprykerEco\Zed\PunchoutCatalogs\Persistence\Propel\Mapper\PunchoutCatalogsConnectionMapper;
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
     * @return \SprykerEco\Zed\PunchoutCatalogs\Persistence\Propel\Mapper\PunchoutCatalogsConnectionMapper
     */
    public function createPunchoutCatalogsConnectionMapper(): PunchoutCatalogsConnectionMapper
    {
        return new PunchoutCatalogsConnectionMapper();
    }
}
