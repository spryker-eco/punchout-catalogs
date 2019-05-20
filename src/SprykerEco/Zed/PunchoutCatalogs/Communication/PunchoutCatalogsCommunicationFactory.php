<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\PunchoutCatalogs\Communication;

use Orm\Zed\PunchoutCatalog\Persistence\PgwPunchoutCatalogConnectionQuery;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use SprykerEco\Zed\PunchoutCatalogs\Communication\Table\PunchoutCatalogsConnectionsTable;
use SprykerEco\Zed\PunchoutCatalogs\Dependency\Service\PunchoutCatalogsToUtilDateTimeServiceInterface;
use SprykerEco\Zed\PunchoutCatalogs\PunchoutCatalogsDependencyProvider;

/**
 * @method \SprykerEco\Zed\PunchoutCatalogs\PunchoutCatalogsConfig getConfig()
 * @method \SprykerEco\Zed\PunchoutCatalogs\Persistence\PunchoutCatalogsRepositoryInterface getRepository()
 */
class PunchoutCatalogsCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \SprykerEco\Zed\PunchoutCatalogs\Communication\Table\PunchoutCatalogsConnectionsTable
     */
    public function createPunchoutCatalogsConnectionsTable()
    {
        return new PunchoutCatalogsConnectionsTable(
            $this->getPunchoutCatalogConnectionPropelQuery(),
            $this->getUtilDateTimeService()
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
}
