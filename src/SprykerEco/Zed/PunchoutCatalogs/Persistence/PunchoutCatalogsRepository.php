<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\PunchoutCatalogs\Persistence;

use Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer;
use Generated\Shared\Transfer\PunchoutCatalogTransactionTransfer;
use Orm\Zed\CompanyBusinessUnit\Persistence\Map\SpyCompanyBusinessUnitTableMap;
use Orm\Zed\CompanyUser\Persistence\Map\SpyCompanyUserTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \SprykerEco\Zed\PunchoutCatalogs\Persistence\PunchoutCatalogsPersistenceFactory getFactory()
 */
class PunchoutCatalogsRepository extends AbstractRepository implements PunchoutCatalogsRepositoryInterface
{
    /**
     * @param int $idConnection
     *
     * @return \Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer|null
     */
    public function findConnectionById(int $idConnection): ?PunchoutCatalogConnectionTransfer
    {
        $punchoutCatalogConnectionEntity = $this->getFactory()
            ->getPunchoutCatalogConnectionPropelQuery()
            ->filterByIdPunchoutCatalogConnection($idConnection)
            ->leftJoinWithPgwPunchoutCatalogConnectionCart()
            ->leftJoinWithPgwPunchoutCatalogConnectionSetup()
            ->findOne();

        if ($punchoutCatalogConnectionEntity === null) {
            return null;
        }

        return $this->getFactory()
            ->createPunchoutCatalogsConnectionMapper()
            ->mapPunchoutCatalogConnectionEntityToTransfer(
                $punchoutCatalogConnectionEntity,
                new PunchoutCatalogConnectionTransfer()
            );
    }

    /**
     * @param int $idPunchoutCatalogTransaction
     *
     * @return \Generated\Shared\Transfer\PunchoutCatalogTransactionTransfer|null
     */
    public function findTransactionById(int $idPunchoutCatalogTransaction): ?PunchoutCatalogTransactionTransfer
    {
        $punchoutCatalogTransactionEntity = $this->getFactory()
            ->getPunchoutCatalogTransactionPropelQuery()
            ->leftJoinWithPunchoutCatalogConnection()
            ->filterByIdPunchoutCatalogTransaction($idPunchoutCatalogTransaction)
            ->findOne();

        if (!$punchoutCatalogTransactionEntity) {
            return null;
        }

        return $this->getFactory()
            ->createPunchoutCatalogsConnectionMapper()
            ->mapPunchoutCatalogTransactionEntityToTransfer(
                $punchoutCatalogTransactionEntity,
                new PunchoutCatalogTransactionTransfer()
            );
    }

    /**
     * @module CompanyBusinessUnit
     * @module Company
     * @module Customer
     *
     * @param int $idCompanyBusinessUnit
     *
     * @return int[]
     */
    public function getActiveCompanyUserIdsByIdCompanyBusinessUnit(int $idCompanyBusinessUnit): array
    {
        return $this->getFactory()
            ->getCompanyUserPropelQuery()
            ->useCompanyQuery(null, Criteria::INNER_JOIN)
                ->filterByIsActive(true)
            ->endUse()
            ->useCustomerQuery(null, Criteria::INNER_JOIN)
                ->filterByAnonymizedAt(null)
            ->endUse()
            ->filterByIsActive(true)
            ->filterByFkCompanyBusinessUnit($idCompanyBusinessUnit)
            ->select(SpyCompanyUserTableMap::COL_ID_COMPANY_USER)
            ->find()
            ->toArray();
    }

    /**
     * @module Company
     *
     * @param int $parentCompanyBusinessUnitId
     *
     * @return int[]
     */
    public function getActiveCompanyBusinessUnitIdsByParentCompanyBusinessUnitId(int $parentCompanyBusinessUnitId): array
    {
        return $this->getFactory()
            ->getCompanyBusinessUnitPropelQuery()
            ->useCompanyQuery(null, Criteria::INNER_JOIN)
                ->filterByIsActive(true)
            ->endUse()
            ->filterByFkParentCompanyBusinessUnit($parentCompanyBusinessUnitId)
            ->select(SpyCompanyBusinessUnitTableMap::COL_ID_COMPANY_BUSINESS_UNIT)
            ->find()
            ->toArray();
    }
}
