<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\PunchoutCatalogs\Business;

use Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer;
use Generated\Shared\Transfer\PunchoutCatalogResponseTransfer;
use Generated\Shared\Transfer\PunchoutCatalogTransactionTransfer;

interface PunchoutCatalogsFacadeInterface
{
    /**
     * Specifications:
     * - Retrieves punchout catalog connection transfer by ID, returns null if nothing found.
     *
     * @api
     *
     * @param int $connectionId
     *
     * @return \Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer|null
     */
    public function findConnectionById(int $connectionId): ?PunchoutCatalogConnectionTransfer;

    /**
     * Specification:
     * - Creates punchout catalog connection in Persistence.
     * - Returns unsuccessful response with corresponding message if no connection was created.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer $punchoutCatalogConnectionTransfer
     *
     * @return \Generated\Shared\Transfer\PunchoutCatalogResponseTransfer
     */
    public function createConnection(PunchoutCatalogConnectionTransfer $punchoutCatalogConnectionTransfer): PunchoutCatalogResponseTransfer;

    /**
     * Specification:
     * - Expects idPunchoutCatalogConnection to be provided.
     * - Returns unsuccessful response with corresponding message if no connection found by idPunchoutCatalogConnection.
     * - Updates punchout catalog connection in Persistence.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer $punchoutCatalogConnectionTransfer
     *
     * @return \Generated\Shared\Transfer\PunchoutCatalogResponseTransfer
     */
    public function updateConnection(PunchoutCatalogConnectionTransfer $punchoutCatalogConnectionTransfer): PunchoutCatalogResponseTransfer;

    /**
     * Specification:
     * - Retrieves punchout catalog transaction by ID, returns null if nothing found.
     *
     * @api
     *
     * @param int $idPunchoutCatalogTransaction
     *
     * @return \Generated\Shared\Transfer\PunchoutCatalogTransactionTransfer|null
     */
    public function findTransactionById(int $idPunchoutCatalogTransaction): ?PunchoutCatalogTransactionTransfer;
}
