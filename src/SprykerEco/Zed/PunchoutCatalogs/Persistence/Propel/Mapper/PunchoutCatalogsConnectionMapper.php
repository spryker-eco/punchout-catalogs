<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\PunchoutCatalogs\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer;
use Generated\Shared\Transfer\PunchoutCatalogTransactionTransfer;
use Orm\Zed\PunchoutCatalog\Persistence\PgwPunchoutCatalogConnection;
use Orm\Zed\PunchoutCatalog\Persistence\PgwPunchoutCatalogTransaction;

class PunchoutCatalogsConnectionMapper
{
    /**
     * @param \Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer $punchoutCatalogConnectionTransfer
     * @param \Orm\Zed\PunchoutCatalog\Persistence\PgwPunchoutCatalogConnection $punchoutCatalogConnectionEntity
     *
     * @return \Orm\Zed\PunchoutCatalog\Persistence\PgwPunchoutCatalogConnection
     */
    public function mapPunchoutCatalogConnectionTransferToEntity(
        PunchoutCatalogConnectionTransfer $punchoutCatalogConnectionTransfer,
        PgwPunchoutCatalogConnection $punchoutCatalogConnectionEntity
    ): PgwPunchoutCatalogConnection {
        $punchoutCatalogConnectionEntity->fromArray($punchoutCatalogConnectionTransfer->toArray());

        return $punchoutCatalogConnectionEntity;
    }

    /**
     * @param \Orm\Zed\PunchoutCatalog\Persistence\PgwPunchoutCatalogConnection $punchoutCatalogConnectionEntity
     * @param \Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer $punchoutCatalogConnectionTransfer
     *
     * @return \Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer
     */
    public function mapPunchoutCatalogConnectionEntityToTransfer(
        PgwPunchoutCatalogConnection $punchoutCatalogConnectionEntity,
        PunchoutCatalogConnectionTransfer $punchoutCatalogConnectionTransfer
    ): PunchoutCatalogConnectionTransfer {
        $punchoutCatalogConnectionTransfer->fromArray($punchoutCatalogConnectionEntity->toArray(), true);

        return $punchoutCatalogConnectionTransfer;
    }

    /**
     * @param \Orm\Zed\PunchoutCatalog\Persistence\PgwPunchoutCatalogTransaction $punchoutCatalogTransaction
     * @param \Generated\Shared\Transfer\PunchoutCatalogTransactionTransfer $punchoutCatalogTransactionTransfer
     *
     * @return \Generated\Shared\Transfer\PunchoutCatalogTransactionTransfer
     */
    public function mapPunchoutCatalogTransactionEntityToTransfer(
        PgwPunchoutCatalogTransaction $punchoutCatalogTransaction,
        PunchoutCatalogTransactionTransfer $punchoutCatalogTransactionTransfer
    ): PunchoutCatalogTransactionTransfer {
        $punchoutCatalogTransactionTransfer->fromArray($punchoutCatalogTransaction->toArray(), true);

        if ($punchoutCatalogTransaction->getPunchoutCatalogConnection()) {
            $punchoutCatalogTransactionTransfer->setConnection(
                $this->mapPunchoutCatalogConnectionEntityToTransfer(
                    $punchoutCatalogTransaction->getPunchoutCatalogConnection(),
                    new PunchoutCatalogConnectionTransfer()
                )
            );
        }

        return $punchoutCatalogTransactionTransfer;
    }
}
