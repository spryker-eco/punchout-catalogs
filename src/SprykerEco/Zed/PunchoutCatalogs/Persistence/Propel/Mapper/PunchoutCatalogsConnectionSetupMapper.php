<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\PunchoutCatalogs\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\PunchoutCatalogConnectionSetupTransfer;
use Orm\Zed\PunchoutCatalog\Persistence\PgwPunchoutCatalogConnectionSetup;

class PunchoutCatalogsConnectionSetupMapper
{
    /**
     * @param \Orm\Zed\PunchoutCatalog\Persistence\PgwPunchoutCatalogConnectionSetup $punchoutCatalogConnectionSetupEntity
     * @param \Generated\Shared\Transfer\PunchoutCatalogConnectionSetupTransfer $punchoutCatalogConnectionSetupTransfer
     *
     * @return \Generated\Shared\Transfer\PunchoutCatalogConnectionSetupTransfer
     */
    public function mapPunchoutCatalogConnectionSetupEntityToTransfer(
        PgwPunchoutCatalogConnectionSetup $punchoutCatalogConnectionSetupEntity,
        PunchoutCatalogConnectionSetupTransfer $punchoutCatalogConnectionSetupTransfer
    ): PunchoutCatalogConnectionSetupTransfer {
        $punchoutCatalogConnectionSetupTransfer->fromArray($punchoutCatalogConnectionSetupEntity->toArray(), true);
        $punchoutCatalogConnectionSetupTransfer->setIdPunchoutCatalogSetup($punchoutCatalogConnectionSetupEntity->getIdPunchoutCatalogConnectionSetup());

        return $punchoutCatalogConnectionSetupTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PunchoutCatalogConnectionSetupTransfer $punchoutCatalogConnectionSetupTransfer
     * @param \Orm\Zed\PunchoutCatalog\Persistence\PgwPunchoutCatalogConnectionSetup $punchoutCatalogConnectionSetupEntity
     *
     * @return \Orm\Zed\PunchoutCatalog\Persistence\PgwPunchoutCatalogConnectionSetup
     */
    public function mapPunchoutCatalogConnectionSetupTransferToEntity(
        PunchoutCatalogConnectionSetupTransfer $punchoutCatalogConnectionSetupTransfer,
        PgwPunchoutCatalogConnectionSetup $punchoutCatalogConnectionSetupEntity
    ): PgwPunchoutCatalogConnectionSetup {
        $punchoutCatalogConnectionSetupEntity->fromArray($punchoutCatalogConnectionSetupTransfer->toArray());
        $punchoutCatalogConnectionSetupEntity->setIdPunchoutCatalogConnectionSetup($punchoutCatalogConnectionSetupTransfer->getIdPunchoutCatalogSetup());

        return $punchoutCatalogConnectionSetupEntity;
    }
}
