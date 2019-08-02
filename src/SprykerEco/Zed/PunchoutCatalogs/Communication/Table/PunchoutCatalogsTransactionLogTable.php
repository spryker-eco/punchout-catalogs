<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\PunchoutCatalogs\Communication\Table;

use Orm\Zed\PunchoutCatalog\Persistence\Map\PgwPunchoutCatalogConnectionTableMap;
use Orm\Zed\PunchoutCatalog\Persistence\Map\PgwPunchoutCatalogTransactionTableMap;
use Orm\Zed\PunchoutCatalog\Persistence\PgwPunchoutCatalogTransaction;
use Orm\Zed\PunchoutCatalog\Persistence\PgwPunchoutCatalogTransactionQuery;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use SprykerEco\Zed\PunchoutCatalogs\Dependency\Service\PunchoutCatalogsToUtilDateTimeServiceInterface;

class PunchoutCatalogsTransactionLogTable extends AbstractTable
{
    protected const STATUS_SUCCESSFUL = 'Successful';
    protected const STATUS_UNSUCCESSFUL = 'Unsuccessful';

    protected const COL_ID_PUNCHOUT_CATALOG_TRANSACTION = 'id_punchout_catalog_transaction';
    protected const COL_TYPE = 'type';
    protected const COL_BUSINESS_UNIT = PgwPunchoutCatalogTransactionTableMap::COL_FK_COMPANY_BUSINESS_UNIT;
    protected const COL_CONNECTION_NAME = PgwPunchoutCatalogConnectionTableMap::COL_NAME;
    protected const COL_STATUS = 'status';
    protected const COL_CREATED_AT = 'created_at';
    protected const COL_SESSION_ID = 'connection_session_id';
    protected const COL_ACTIONS = 'actions';

    protected const URL_PARAM_ID_PUNCHOUT_CATALOG_TRANSACTION = 'id-punchout-catalog-transaction';

    /**
     * @see \SprykerEco\Zed\PunchoutCatalogs\Communication\Controller\TransactionController::viewAction()
     */
    protected const URL_PUNCHOUT_CATALOG_TRANSACTION_VIEW = '/punchout-catalogs/transaction/view';

    /**
     * @var \Orm\Zed\PunchoutCatalog\Persistence\PgwPunchoutCatalogTransactionQuery
     */
    protected $punchoutCatalogTransactionPropelQuery;

    /**
     * @var \SprykerEco\Zed\PunchoutCatalogs\Dependency\Service\PunchoutCatalogsToUtilDateTimeServiceInterface
     */
    protected $utilDateTimeService;

    /**
     * @param \Orm\Zed\PunchoutCatalog\Persistence\PgwPunchoutCatalogTransactionQuery $punchoutCatalogTransactionPropelQuery
     * @param \SprykerEco\Zed\PunchoutCatalogs\Dependency\Service\PunchoutCatalogsToUtilDateTimeServiceInterface $utilDateTimeService
     */
    public function __construct(PgwPunchoutCatalogTransactionQuery $punchoutCatalogTransactionPropelQuery, PunchoutCatalogsToUtilDateTimeServiceInterface $utilDateTimeService)
    {
        $this->punchoutCatalogTransactionPropelQuery = $punchoutCatalogTransactionPropelQuery;
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
            static::COL_ID_PUNCHOUT_CATALOG_TRANSACTION => 'ID',
            static::COL_TYPE => 'Message Type',
            static::COL_BUSINESS_UNIT => 'Business Unit',
            static::COL_CONNECTION_NAME => 'Connection Name',
            static::COL_STATUS => 'Status',
            static::COL_SESSION_ID => 'Session ID',
            static::COL_CREATED_AT => 'Created At',
            static::COL_ACTIONS => 'Actions',
        ]);

        $config->setSortable([
            static::COL_ID_PUNCHOUT_CATALOG_TRANSACTION,
            static::COL_TYPE,
            static::COL_BUSINESS_UNIT,
            static::COL_CONNECTION_NAME,
            static::COL_STATUS,
            static::COL_SESSION_ID,
            static::COL_CREATED_AT,
        ]);

        $config->setSearchable([
            PgwPunchoutCatalogTransactionTableMap::COL_ID_PUNCHOUT_CATALOG_TRANSACTION,
            PgwPunchoutCatalogTransactionTableMap::COL_TYPE,
            PgwPunchoutCatalogTransactionTableMap::COL_CONNECTION_SESSION_ID,
            PgwPunchoutCatalogConnectionTableMap::COL_NAME,
        ]);

        $config->setRawColumns([
            static::COL_STATUS,
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
        /** @var \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\PunchoutCatalog\Persistence\PgwPunchoutCatalogTransaction[] $punchoutCatalogTransactionCollection */
        $punchoutCatalogTransactionCollection = $this->runQuery(
            $this->prepareQuery($this->punchoutCatalogTransactionPropelQuery),
            $config,
            true
        );

        if ($punchoutCatalogTransactionCollection->count() === 0) {
            return [];
        }

        return $this->mapPunchoutCatalogTransactions($punchoutCatalogTransactionCollection);
    }

    /**
     * @module Company
     * @module CompanyBusinessUnit
     *
     * @param \Orm\Zed\PunchoutCatalog\Persistence\PgwPunchoutCatalogTransactionQuery $punchoutCatalogTransactionPropelQuery
     *
     * @return \Orm\Zed\PunchoutCatalog\Persistence\PgwPunchoutCatalogTransactionQuery
     */
    protected function prepareQuery(PgwPunchoutCatalogTransactionQuery $punchoutCatalogTransactionPropelQuery): PgwPunchoutCatalogTransactionQuery
    {
        return $punchoutCatalogTransactionPropelQuery->leftJoinWithPunchoutCatalogConnection();
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\PunchoutCatalog\Persistence\PgwPunchoutCatalogTransaction[] $punchoutCatalogTransactions
     *
     * @return array
     */
    protected function mapPunchoutCatalogTransactions(ObjectCollection $punchoutCatalogTransactions): array
    {
        $punchoutCatalogConnectionRows = [];

        foreach ($punchoutCatalogTransactions as $punchoutCatalogTransaction) {
            $punchoutCatalogConnectionRows[] = $this->mapPunchoutCatalogTransaction($punchoutCatalogTransaction);
        }

        return $punchoutCatalogConnectionRows;
    }

    /**
     * @param \Orm\Zed\PunchoutCatalog\Persistence\PgwPunchoutCatalogTransaction $punchoutCatalogTransaction
     *
     * @return array
     */
    protected function mapPunchoutCatalogTransaction(PgwPunchoutCatalogTransaction $punchoutCatalogTransaction): array
    {
        $punchoutCatalogTransactionRow = $punchoutCatalogTransaction->toArray();

        $punchoutCatalogTransactionRow[static::COL_STATUS] = $this->getStatusLabel($punchoutCatalogTransaction);
        $punchoutCatalogTransactionRow[static::COL_CREATED_AT] = $this->utilDateTimeService->formatDateTime(
            $punchoutCatalogTransaction->getCreatedAt()
        );
        $punchoutCatalogTransactionRow = $this->addCompanyBusinessUnitColumn($punchoutCatalogTransaction, $punchoutCatalogTransactionRow);
        $punchoutCatalogTransactionRow = $this->addConnectionNameColumn($punchoutCatalogTransaction, $punchoutCatalogTransactionRow);

        $punchoutCatalogTransactionRow[static::COL_ACTIONS] = $this->buildLinks($punchoutCatalogTransaction);

        return $punchoutCatalogTransactionRow;
    }

    /**
     * @param \Orm\Zed\PunchoutCatalog\Persistence\PgwPunchoutCatalogTransaction $punchoutCatalogTransaction
     *
     * @return string
     */
    protected function buildLinks(PgwPunchoutCatalogTransaction $punchoutCatalogTransaction): string
    {
        return $this->generateViewButton(
            Url::generate(static::URL_PUNCHOUT_CATALOG_TRANSACTION_VIEW, [
                static::URL_PARAM_ID_PUNCHOUT_CATALOG_TRANSACTION => $punchoutCatalogTransaction->getIdPunchoutCatalogTransaction(),
            ]),
            'View'
        );
    }

    /**
     * @param \Orm\Zed\PunchoutCatalog\Persistence\PgwPunchoutCatalogTransaction $punchoutCatalogTransaction
     *
     * @return string
     */
    protected function getStatusLabel(PgwPunchoutCatalogTransaction $punchoutCatalogTransaction): string
    {
        if (!$punchoutCatalogTransaction->getStatus()) {
            return $this->generateLabel(static::STATUS_UNSUCCESSFUL, 'label-danger');
        }

        return $this->generateLabel(static::STATUS_SUCCESSFUL, 'label-info');
    }

    /**
     * @param \Orm\Zed\PunchoutCatalog\Persistence\PgwPunchoutCatalogTransaction $punchoutCatalogTransaction
     * @param array $punchoutCatalogTransactionRow
     *
     * @return array
     */
    protected function addCompanyBusinessUnitColumn(PgwPunchoutCatalogTransaction $punchoutCatalogTransaction, array $punchoutCatalogTransactionRow): array
    {
        $punchoutCatalogTransactionRow[static::COL_BUSINESS_UNIT] = $punchoutCatalogTransaction->getFkCompanyBusinessUnit() ?: 'NULL';

        return $punchoutCatalogTransactionRow;
    }

    /**
     * @param \Orm\Zed\PunchoutCatalog\Persistence\PgwPunchoutCatalogTransaction $punchoutCatalogTransaction
     * @param array $punchoutCatalogTransactionRow
     *
     * @return array
     */
    protected function addConnectionNameColumn(PgwPunchoutCatalogTransaction $punchoutCatalogTransaction, array $punchoutCatalogTransactionRow): array
    {
        $punchoutCatalogTransactionRow[static::COL_CONNECTION_NAME] = '';
        if ($punchoutCatalogTransaction->getPunchoutCatalogConnection()) {
            $punchoutCatalogTransactionRow[static::COL_CONNECTION_NAME] = $punchoutCatalogTransaction->getPunchoutCatalogConnection()
                ->getName();
        }

        return $punchoutCatalogTransactionRow;
    }
}
