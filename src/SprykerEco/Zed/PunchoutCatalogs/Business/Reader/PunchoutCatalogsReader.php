<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\PunchoutCatalogs\Business\Reader;

use Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer;
use SprykerEco\Zed\PunchoutCatalogs\Persistence\PunchoutCatalogsRepositoryInterface;

class PunchoutCatalogsReader implements PunchoutCatalogsReaderInterface
{
    /**
     * @var \SprykerEco\Zed\PunchoutCatalogs\Persistence\PunchoutCatalogsRepositoryInterface
     */
    protected $punchoutCatalogsRepository;

    /**
     * @param \SprykerEco\Zed\PunchoutCatalogs\Persistence\PunchoutCatalogsRepositoryInterface $punchoutCatalogsRepository
     */
    public function __construct(PunchoutCatalogsRepositoryInterface $punchoutCatalogsRepository)
    {
        $this->punchoutCatalogsRepository = $punchoutCatalogsRepository;
    }

    /**
     * @param int $connectionId
     *
     * @return \Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer|null
     */
    public function findConnectionById(int $connectionId): ?PunchoutCatalogConnectionTransfer
    {
        return $this->punchoutCatalogsRepository->findConnectionById($connectionId);
    }
}
