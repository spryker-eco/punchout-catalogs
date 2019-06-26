<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\PunchoutCatalogs\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\PunchoutCatalogConnectionCartTransfer;
use Orm\Zed\PunchoutCatalog\Persistence\PgwPunchoutCatalogConnectionCart;

class PunchoutCatalogsConnectionCartMapper
{
    /**
     * @param \Orm\Zed\PunchoutCatalog\Persistence\PgwPunchoutCatalogConnectionCart $punchoutCatalogConnectionCartEntity
     * @param \Generated\Shared\Transfer\PunchoutCatalogConnectionCartTransfer $punchoutCatalogConnectionCartTransfer
     *
     * @return \Generated\Shared\Transfer\PunchoutCatalogConnectionCartTransfer
     */
    public function mapPunchoutCatalogConnectionCartEntityToTransfer(
        PgwPunchoutCatalogConnectionCart $punchoutCatalogConnectionCartEntity,
        PunchoutCatalogConnectionCartTransfer $punchoutCatalogConnectionCartTransfer
    ): PunchoutCatalogConnectionCartTransfer {
        $punchoutCatalogConnectionCartTransfer->fromArray($punchoutCatalogConnectionCartEntity->toArray(), true);
        $punchoutCatalogConnectionCartTransfer->setIdPunchoutCatalogCart($punchoutCatalogConnectionCartEntity->getIdPunchoutCatalogConnectionCart());

        return $punchoutCatalogConnectionCartTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PunchoutCatalogConnectionCartTransfer $punchoutCatalogConnectionCartTransfer
     * @param \Orm\Zed\PunchoutCatalog\Persistence\PgwPunchoutCatalogConnectionCart $punchoutCatalogConnectionCartEntity
     *
     * @return \Orm\Zed\PunchoutCatalog\Persistence\PgwPunchoutCatalogConnectionCart
     */
    public function mapPunchoutCatalogConnectionCartTransferToEntity(
        PunchoutCatalogConnectionCartTransfer $punchoutCatalogConnectionCartTransfer,
        PgwPunchoutCatalogConnectionCart $punchoutCatalogConnectionCartEntity
    ): PgwPunchoutCatalogConnectionCart {
        $punchoutCatalogConnectionCartEntity->fromArray($punchoutCatalogConnectionCartTransfer->toArray());
        $punchoutCatalogConnectionCartEntity->setIdPunchoutCatalogConnectionCart($punchoutCatalogConnectionCartTransfer->getIdPunchoutCatalogCart());

        return $punchoutCatalogConnectionCartEntity;
    }
}
