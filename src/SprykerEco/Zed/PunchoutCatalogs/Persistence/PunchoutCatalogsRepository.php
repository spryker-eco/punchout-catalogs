<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\PunchoutCatalogs\Persistence;

use Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer;
use Generated\Shared\Transfer\PunchoutCatalogTransactionTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \SprykerEco\Zed\PunchoutCatalogs\Persistence\PunchoutCatalogsPersistenceFactory getFactory()
 */
class PunchoutCatalogsRepository extends AbstractRepository implements PunchoutCatalogsRepositoryInterface
{
    /**
     * @param int $connectionId
     *
     * @return \Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer|null
     */
    public function findConnectionById(int $connectionId): ?PunchoutCatalogConnectionTransfer
    {
        $punchoutCatalogConnectionEntity = $this->getFactory()
            ->getPunchoutCatalogConnectionPropelQuery()
            ->filterByIdPunchoutCatalogConnection($connectionId)
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
}
