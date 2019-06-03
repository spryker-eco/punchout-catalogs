<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\PunchoutCatalogs\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\PunchoutCatalogConnectionBuilder;
use Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class PunchoutCatalogsHelper extends Module
{
    use LocatorHelperTrait;

    /**
     * @param array $seed
     *
     * @return \Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer
     */
    public function havePunchoutCatalogConnection(array $seed = []): PunchoutCatalogConnectionTransfer
    {
        $punchoutCatalogConnectionTransfer = (new PunchoutCatalogConnectionBuilder($seed))->build();

        return $this->getLocator()
            ->punchoutCatalogs()
            ->facade()
            ->createConnection($punchoutCatalogConnectionTransfer)
            ->getPunchoutCatalogConnection();
    }
}
