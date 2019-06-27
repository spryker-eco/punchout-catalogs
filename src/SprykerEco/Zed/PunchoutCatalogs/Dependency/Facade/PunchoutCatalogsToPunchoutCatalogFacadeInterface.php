<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\PunchoutCatalogs\Dependency\Facade;

use Generated\Shared\Transfer\PunchoutCatalogEntryPointFilterTransfer;

interface PunchoutCatalogsToPunchoutCatalogFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\PunchoutCatalogEntryPointFilterTransfer $entryPointFilter
     *
     * @return \Generated\Shared\Transfer\PunchoutCatalogEntryPointTransfer[]
     */
    public function getRequestEntryPointsByBusinessUnit(PunchoutCatalogEntryPointFilterTransfer $entryPointFilter): array;
}
