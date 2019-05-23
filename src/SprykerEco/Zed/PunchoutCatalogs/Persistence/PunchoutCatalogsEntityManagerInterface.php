<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\PunchoutCatalogs\Persistence;

use Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer;

interface PunchoutCatalogsEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer $punchoutCatalogConnectionTransfer
     *
     * @return bool
     */
    public function createPunchoutCatalogConnection(PunchoutCatalogConnectionTransfer $punchoutCatalogConnectionTransfer): bool;

    /**
     * @param \Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer $punchoutCatalogConnectionTransfer
     *
     * @return bool
     */
    public function updatePunchoutCatalogConnection(PunchoutCatalogConnectionTransfer $punchoutCatalogConnectionTransfer): bool;
}
