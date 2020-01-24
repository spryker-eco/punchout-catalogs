<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\PunchoutCatalogs\Persistence;

use Generated\Shared\Transfer\PunchoutCatalogConnectionCollectionTransfer;
use Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer;
use Generated\Shared\Transfer\PunchoutCatalogTransactionTransfer;

interface PunchoutCatalogsRepositoryInterface
{
    /**
     * @param int $idConnection
     *
     * @return \Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer|null
     */
    public function findConnectionById(int $idConnection): ?PunchoutCatalogConnectionTransfer;

    /**
     * @param int $fkCompanyBusinessUnit
     *
     * @return \Generated\Shared\Transfer\PunchoutCatalogConnectionCollectionTransfer|null
     */
    public function findConnectionByFkCompanyBusinessUnit(
        int $fkCompanyBusinessUnit
    ): ?PunchoutCatalogConnectionCollectionTransfer;

    /**
     * @param int $fkCompanyUser
     *
     * @return \Generated\Shared\Transfer\PunchoutCatalogConnectionCollectionTransfer|null
     */
    public function findConnectionByFkCompanyUser(int $fkCompanyUser): ?PunchoutCatalogConnectionCollectionTransfer;

    /**
     * @param int $idPunchoutCatalogTransaction
     *
     * @return \Generated\Shared\Transfer\PunchoutCatalogTransactionTransfer|null
     */
    public function findTransactionById(int $idPunchoutCatalogTransaction): ?PunchoutCatalogTransactionTransfer;

    /**
     * @param int $idCompanyBusinessUnit
     *
     * @return int[]
     */
    public function getActiveCompanyUserIdsByIdCompanyBusinessUnit(int $idCompanyBusinessUnit): array;

    /**
     * @param int $parentCompanyBusinessUnitId
     *
     * @return int[]
     */
    public function getActiveCompanyBusinessUnitIdsByParentCompanyBusinessUnitId(int $parentCompanyBusinessUnitId): array;
}
