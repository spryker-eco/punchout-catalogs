<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\PunchoutCatalogs\Persistence;

use Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer;
use Orm\Zed\PunchoutCatalog\Persistence\PgwPunchoutCatalogConnection;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \SprykerEco\Zed\PunchoutCatalogs\Persistence\PunchoutCatalogsPersistenceFactory getFactory()
 */
class PunchoutCatalogsEntityManager extends AbstractEntityManager implements PunchoutCatalogsEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer $punchoutCatalogConnectionTransfer
     *
     * @return \Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer
     */
    public function createPunchoutCatalogConnection(PunchoutCatalogConnectionTransfer $punchoutCatalogConnectionTransfer): PunchoutCatalogConnectionTransfer
    {
        $punchoutCatalogsConnectionMapper = $this->getFactory()
            ->createPunchoutCatalogsConnectionMapper();

        $punchoutCatalogConnectionEntity = $punchoutCatalogsConnectionMapper->mapPunchoutCatalogConnectionTransferToEntity(
            $punchoutCatalogConnectionTransfer,
            new PgwPunchoutCatalogConnection()
        );

        $punchoutCatalogConnectionEntity->save();

        return $punchoutCatalogsConnectionMapper->mapPunchoutCatalogConnectionEntityToTransfer(
            $punchoutCatalogConnectionEntity,
            $punchoutCatalogConnectionTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer $punchoutCatalogConnectionTransfer
     *
     * @return bool
     */
    public function updatePunchoutCatalogConnection(PunchoutCatalogConnectionTransfer $punchoutCatalogConnectionTransfer): bool
    {
        $punchoutCatalogConnectionEntity = $this->getFactory()
            ->getPunchoutCatalogConnectionPropelQuery()
            ->filterByIdPunchoutCatalogConnection($punchoutCatalogConnectionTransfer->getIdPunchoutCatalogConnection())
            ->findOne();

        if (!$punchoutCatalogConnectionEntity) {
            return false;
        }

        $punchoutCatalogConnectionEntity = $this->getFactory()
            ->createPunchoutCatalogsConnectionMapper()
            ->mapPunchoutCatalogConnectionTransferToEntity(
                $punchoutCatalogConnectionTransfer,
                $punchoutCatalogConnectionEntity
            );

        $punchoutCatalogConnectionEntity->save();

        return true;
    }
}
