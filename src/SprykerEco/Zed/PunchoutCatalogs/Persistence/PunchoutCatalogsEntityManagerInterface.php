<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\PunchoutCatalogs\Persistence;

use Generated\Shared\Transfer\PunchoutCatalogConnectionCartTransfer;
use Generated\Shared\Transfer\PunchoutCatalogConnectionSetupTransfer;
use Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer;

interface PunchoutCatalogsEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer $punchoutCatalogConnectionTransfer
     *
     * @return \Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer
     */
    public function createPunchoutCatalogConnection(PunchoutCatalogConnectionTransfer $punchoutCatalogConnectionTransfer): PunchoutCatalogConnectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer $punchoutCatalogConnectionTransfer
     *
     * @return void
     */
    public function updatePunchoutCatalogConnection(PunchoutCatalogConnectionTransfer $punchoutCatalogConnectionTransfer): void;

    /**
     * @param int $idPunchoutCatalogConnection
     * @param \Generated\Shared\Transfer\PunchoutCatalogConnectionCartTransfer $punchoutCatalogConnectionCartTransfer
     *
     * @return \Generated\Shared\Transfer\PunchoutCatalogConnectionCartTransfer
     */
    public function createPunchoutCatalogConnectionCart(int $idPunchoutCatalogConnection, PunchoutCatalogConnectionCartTransfer $punchoutCatalogConnectionCartTransfer): PunchoutCatalogConnectionCartTransfer;

    /**
     * @param \Generated\Shared\Transfer\PunchoutCatalogConnectionCartTransfer $punchoutCatalogConnectionCartTransfer
     *
     * @return void
     */
    public function updatePunchoutCatalogConnectionCart(PunchoutCatalogConnectionCartTransfer $punchoutCatalogConnectionCartTransfer): void;

    /**
     * @param int $idPunchoutCatalogConnection
     * @param \Generated\Shared\Transfer\PunchoutCatalogConnectionSetupTransfer $punchoutCatalogConnectionSetupTransfer
     *
     * @return \Generated\Shared\Transfer\PunchoutCatalogConnectionSetupTransfer
     */
    public function createPunchoutCatalogConnectionSetup(int $idPunchoutCatalogConnection, PunchoutCatalogConnectionSetupTransfer $punchoutCatalogConnectionSetupTransfer): PunchoutCatalogConnectionSetupTransfer;

    /**
     * @param \Generated\Shared\Transfer\PunchoutCatalogConnectionSetupTransfer $punchoutCatalogConnectionSetupTransfer
     *
     * @return void
     */
    public function updatePunchoutCatalogConnectionSetup(PunchoutCatalogConnectionSetupTransfer $punchoutCatalogConnectionSetupTransfer): void;
}
