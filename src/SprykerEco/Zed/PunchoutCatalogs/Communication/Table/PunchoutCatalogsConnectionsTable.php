<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\PunchoutCatalogs\Communication\Table;

use Orm\Zed\Company\Persistence\Map\SpyCompanyTableMap;
use Orm\Zed\PunchoutCatalog\Persistence\Map\PgwPunchoutCatalogConnectionTableMap;
use Orm\Zed\PunchoutCatalog\Persistence\PgwPunchoutCatalogConnection;
use Orm\Zed\PunchoutCatalog\Persistence\PgwPunchoutCatalogConnectionQuery;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use SprykerEco\Zed\PunchoutCatalogs\Dependency\Service\PunchoutCatalogsToUtilDateTimeServiceInterface;

class PunchoutCatalogsConnectionsTable extends AbstractTable
{
    protected const STATUS_ACTIVE = 'Active';
    protected const STATUS_INACTIVE = 'Inactive';

    protected const COL_ID_PUNCHOUT_CATALOG_CONNECTION = 'id_punchout_catalog_connection';
    protected const COL_NAME = 'name';
    protected const COL_COMPANY = 'company';
    protected const COL_TYPE = 'type';
    protected const COL_FORMAT = 'format';
    protected const COL_CREATED_AT = 'createdAt';
    protected const COL_STATUS = 'status';
    protected const COL_ACTIONS = 'actions';

    /**
     * @var \Orm\Zed\PunchoutCatalog\Persistence\PgwPunchoutCatalogConnectionQuery
     */
    protected $connectionPropelQuery;

    /**
     * @var \SprykerEco\Zed\PunchoutCatalogs\Dependency\Service\PunchoutCatalogsToUtilDateTimeServiceInterface
     */
    protected $utilDateTimeService;

    /**
     * @param \Orm\Zed\PunchoutCatalog\Persistence\PgwPunchoutCatalogConnectionQuery $punchoutCatalogConnectionPropelQuery
     * @param \SprykerEco\Zed\PunchoutCatalogs\Dependency\Service\PunchoutCatalogsToUtilDateTimeServiceInterface $utilDateTimeService
     */
    public function __construct(PgwPunchoutCatalogConnectionQuery $punchoutCatalogConnectionPropelQuery, PunchoutCatalogsToUtilDateTimeServiceInterface $utilDateTimeService)
    {
        $this->connectionPropelQuery = $punchoutCatalogConnectionPropelQuery;
        $this->utilDateTimeService = $utilDateTimeService;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $config->setHeader([
            static::COL_ID_PUNCHOUT_CATALOG_CONNECTION => '#',
            static::COL_NAME => 'Name',
            static::COL_TYPE => 'Type',
            static::COL_COMPANY => 'Company',
            static::COL_STATUS => 'Status',
            static::COL_FORMAT => 'Format',
            static::COL_CREATED_AT => 'Created At',
            static::COL_ACTIONS => 'Actions'
        ]);

        $config->setSortable([
            static::COL_ID_PUNCHOUT_CATALOG_CONNECTION,
            static::COL_NAME,
            static::COL_TYPE,
            static::COL_COMPANY,
            static::COL_STATUS,
            static::COL_FORMAT,
            static::COL_CREATED_AT,
        ]);

        $config->setSearchable([
            PgwPunchoutCatalogConnectionTableMap::COL_ID_PUNCHOUT_CATALOG_CONNECTION,
            PgwPunchoutCatalogConnectionTableMap::COL_NAME,
            SpyCompanyTableMap::COL_NAME,
        ]);

        $config->setRawColumns([
            static::COL_STATUS,
            static::COL_FORMAT,
            static::COL_ACTIONS,
        ]);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config): array
    {
        /** @var \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\PunchoutCatalog\Persistence\PgwPunchoutCatalogConnection[] $punchoutCatalogConnections */
        $punchoutCatalogConnectionCollection = $this->runQuery(
            $this->prepareQuery($this->connectionPropelQuery),
            $config,
            true
        );

        if ($punchoutCatalogConnectionCollection->count() === 0) {
            return [];
        }

        return $this->mapPunchoutCatalogConnections($punchoutCatalogConnectionCollection);
    }

    /**
     * @module Company
     *
     * @param \Orm\Zed\PunchoutCatalog\Persistence\PgwPunchoutCatalogConnectionQuery
     *
     * @return \Orm\Zed\PunchoutCatalog\Persistence\PgwPunchoutCatalogConnectionQuery
     */
    protected function prepareQuery(PgwPunchoutCatalogConnectionQuery $connectionPropelQuery): PgwPunchoutCatalogConnectionQuery
    {
        return $connectionPropelQuery->joinWithCompanyBusinessUnit()
            ->useCompanyBusinessUnitQuery()
            ->joinCompany()
                ->withColumn(SpyCompanyTableMap::COL_NAME, static::COL_COMPANY)
            ->endUse();
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\PunchoutCatalog\Persistence\PgwPunchoutCatalogConnection[] $punchoutCatalogConnectionCollection
     *
     * @return array
     */
    protected function mapPunchoutCatalogConnections(ObjectCollection $punchoutCatalogConnectionCollection): array
    {
        $punchoutCatalogConnectionRows = [];

        foreach ($punchoutCatalogConnectionCollection as $punchoutCatalogConnection) {
            $punchoutCatalogConnectionRows[] = $this->mapPunchoutCatalogConnection($punchoutCatalogConnection);
        }

        return $punchoutCatalogConnectionRows;
    }

    /**
     * @param \Orm\Zed\PunchoutCatalog\Persistence\PgwPunchoutCatalogConnection $punchoutCatalogConnection
     *
     * @return array
     */
    protected function mapPunchoutCatalogConnection(PgwPunchoutCatalogConnection $punchoutCatalogConnection): array
    {
        $punchoutCatalogConnectionRow = $punchoutCatalogConnection->toArray();

        $punchoutCatalogConnectionRow[static::COL_ACTIONS] = $this->buildLinks($punchoutCatalogConnection);
        $punchoutCatalogConnectionRow[static::COL_STATUS] = $this->getStatusLabel($punchoutCatalogConnection);
        $punchoutCatalogConnectionRow[static::COL_FORMAT] = $this->generateLabel(
            $punchoutCatalogConnection->getFormat(),
            'label-secondary'
        );
        $punchoutCatalogConnectionRow[static::COL_CREATED_AT] = $this->utilDateTimeService->formatDateTime(
            $punchoutCatalogConnection->getCreatedAt()
        );
        $punchoutCatalogConnectionRow[static::COL_COMPANY] = $punchoutCatalogConnection->getCompanyBusinessUnit()
            ->getCompany()
            ->getName();

        return $punchoutCatalogConnectionRow;
    }

    /**
     * @param \Orm\Zed\PunchoutCatalog\Persistence\PgwPunchoutCatalogConnection $punchoutCatalogConnection
     *
     * @return string
     */
    protected function buildLinks(PgwPunchoutCatalogConnection $punchoutCatalogConnection): string
    {
        $buttons = [];

        if ($punchoutCatalogConnection->getIsActive()) {
            $buttons[] = $this->generateRemoveButton('NOT_IMPLEMENTED', 'Deactivate');
        }

        if (!$punchoutCatalogConnection->getIsActive()) {
            $buttons[] = $this->generateButton('NOT_IMPLEMENTED', 'Activate', ['btn-success']);
        }

        return implode(' ', $buttons);
    }

    /**
     * @param \Orm\Zed\PunchoutCatalog\Persistence\PgwPunchoutCatalogConnection $punchoutCatalogConnection
     *
     * @return string
     */
    protected function getStatusLabel(PgwPunchoutCatalogConnection $punchoutCatalogConnection): string
    {
        if (!$punchoutCatalogConnection->getIsActive()) {
            return $this->generateLabel(static::STATUS_INACTIVE, 'label-danger');
        }

        return $this->generateLabel(static::STATUS_ACTIVE, 'label-info');
    }
}
