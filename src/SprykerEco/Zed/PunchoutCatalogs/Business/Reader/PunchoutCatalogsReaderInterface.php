<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\PunchoutCatalogs\Business\Reader;

use Generated\Shared\Transfer\PunchoutCatalogConnectionCollectionTransfer;
use Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer;
use Generated\Shared\Transfer\PunchoutCatalogResponseTransfer;

interface PunchoutCatalogsReaderInterface
{
    /**
     * @param int $idConnection
     *
     * @return \Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer|null
     */
    public function findConnectionById(int $idConnection): ?PunchoutCatalogConnectionTransfer;

    /**
     * @param int $idConnection
     *
     * @return \Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer|null
     */
    public function findConnectionByIdWithPassword(int $idConnection): ?PunchoutCatalogConnectionTransfer;

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
}
