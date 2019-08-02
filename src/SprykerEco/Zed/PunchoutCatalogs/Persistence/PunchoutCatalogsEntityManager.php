<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\PunchoutCatalogs\Persistence;

use Generated\Shared\Transfer\PunchoutCatalogConnectionCartTransfer;
use Generated\Shared\Transfer\PunchoutCatalogConnectionSetupTransfer;
use Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer;
use Orm\Zed\PunchoutCatalog\Persistence\PgwPunchoutCatalogConnection;
use Orm\Zed\PunchoutCatalog\Persistence\PgwPunchoutCatalogConnectionCart;
use Orm\Zed\PunchoutCatalog\Persistence\PgwPunchoutCatalogConnectionSetup;
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
     * @return void
     */
    public function updatePunchoutCatalogConnection(PunchoutCatalogConnectionTransfer $punchoutCatalogConnectionTransfer): void
    {
        $punchoutCatalogConnectionEntity = $this->getFactory()
            ->getPunchoutCatalogConnectionPropelQuery()
            ->filterByIdPunchoutCatalogConnection($punchoutCatalogConnectionTransfer->getIdPunchoutCatalogConnection())
            ->findOne();

        $punchoutCatalogConnectionEntity = $this->getFactory()
            ->createPunchoutCatalogsConnectionMapper()
            ->mapPunchoutCatalogConnectionTransferToEntity(
                $punchoutCatalogConnectionTransfer,
                $punchoutCatalogConnectionEntity
            );

        $punchoutCatalogConnectionEntity->save();
    }

    /**
     * @param int $idPunchoutCatalogConnection
     * @param \Generated\Shared\Transfer\PunchoutCatalogConnectionCartTransfer $punchoutCatalogConnectionCartTransfer
     *
     * @return \Generated\Shared\Transfer\PunchoutCatalogConnectionCartTransfer
     */
    public function createPunchoutCatalogConnectionCart(int $idPunchoutCatalogConnection, PunchoutCatalogConnectionCartTransfer $punchoutCatalogConnectionCartTransfer): PunchoutCatalogConnectionCartTransfer
    {
        $punchoutCatalogsConnectionCartMapper = $this->getFactory()
            ->createPunchoutCatalogsConnectionCartMapper();

        $punchoutCatalogConnectionCartEntity = $punchoutCatalogsConnectionCartMapper->mapPunchoutCatalogConnectionCartTransferToEntity(
            $punchoutCatalogConnectionCartTransfer,
            new PgwPunchoutCatalogConnectionCart()
        );

        $punchoutCatalogConnectionCartEntity->setIdPunchoutCatalogConnectionCart($idPunchoutCatalogConnection);

        $punchoutCatalogConnectionCartEntity->save();

        return $punchoutCatalogsConnectionCartMapper->mapPunchoutCatalogConnectionCartEntityToTransfer(
            $punchoutCatalogConnectionCartEntity,
            $punchoutCatalogConnectionCartTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PunchoutCatalogConnectionCartTransfer $punchoutCatalogConnectionCartTransfer
     *
     * @return void
     */
    public function updatePunchoutCatalogConnectionCart(PunchoutCatalogConnectionCartTransfer $punchoutCatalogConnectionCartTransfer): void
    {
        $punchoutCatalogConnectionCartEntity = $this->getFactory()
            ->getPunchoutCatalogConnectionCartPropelQuery()
            ->filterByIdPunchoutCatalogConnectionCart($punchoutCatalogConnectionCartTransfer->getIdPunchoutCatalogCart())
            ->findOne();

        $punchoutCatalogConnectionCartEntity = $this->getFactory()
            ->createPunchoutCatalogsConnectionCartMapper()
            ->mapPunchoutCatalogConnectionCartTransferToEntity(
                $punchoutCatalogConnectionCartTransfer,
                $punchoutCatalogConnectionCartEntity
            );

        $punchoutCatalogConnectionCartEntity->save();
    }

    /**
     * @param int $idPunchoutCatalogConnection
     * @param \Generated\Shared\Transfer\PunchoutCatalogConnectionSetupTransfer $punchoutCatalogConnectionSetupTransfer
     *
     * @return \Generated\Shared\Transfer\PunchoutCatalogConnectionSetupTransfer
     */
    public function createPunchoutCatalogConnectionSetup(int $idPunchoutCatalogConnection, PunchoutCatalogConnectionSetupTransfer $punchoutCatalogConnectionSetupTransfer): PunchoutCatalogConnectionSetupTransfer
    {
        $punchoutCatalogsConnectionSetupMapper = $this->getFactory()
            ->createPunchoutCatalogsConnectionSetupMapper();

        $punchoutCatalogConnectionSetupEntity = $punchoutCatalogsConnectionSetupMapper->mapPunchoutCatalogConnectionSetupTransferToEntity(
            $punchoutCatalogConnectionSetupTransfer,
            new PgwPunchoutCatalogConnectionSetup()
        );

        $punchoutCatalogConnectionSetupEntity->setIdPunchoutCatalogConnectionSetup($idPunchoutCatalogConnection);

        $punchoutCatalogConnectionSetupEntity->save();

        return $punchoutCatalogsConnectionSetupMapper->mapPunchoutCatalogConnectionSetupEntityToTransfer(
            $punchoutCatalogConnectionSetupEntity,
            $punchoutCatalogConnectionSetupTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PunchoutCatalogConnectionSetupTransfer $punchoutCatalogConnectionSetupTransfer
     *
     * @return void
     */
    public function updatePunchoutCatalogConnectionSetup(PunchoutCatalogConnectionSetupTransfer $punchoutCatalogConnectionSetupTransfer): void
    {
        $punchoutCatalogConnectionSetupEntity = $this->getFactory()
            ->getPunchoutCatalogConnectionSetupPropelQuery()
            ->filterByIdPunchoutCatalogConnectionSetup($punchoutCatalogConnectionSetupTransfer->getIdPunchoutCatalogSetup())
            ->findOne();

        $punchoutCatalogConnectionSetupEntity = $this->getFactory()
            ->createPunchoutCatalogsConnectionSetupMapper()
            ->mapPunchoutCatalogConnectionSetupTransferToEntity(
                $punchoutCatalogConnectionSetupTransfer,
                $punchoutCatalogConnectionSetupEntity
            );

        $punchoutCatalogConnectionSetupEntity->save();
    }
}
