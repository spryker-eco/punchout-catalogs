<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\PunchoutCatalogs\Business;

use Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer;
use Generated\Shared\Transfer\PunchoutCatalogResponseTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \SprykerEco\Zed\PunchoutCatalogs\Business\PunchoutCatalogsBusinessFactory getFactory()
 * @method \SprykerEco\Zed\PunchoutCatalogs\Persistence\PunchoutCatalogsEntityManagerInterface getEntityManager()
 * @method \SprykerEco\Zed\PunchoutCatalogs\Persistence\PunchoutCatalogsRepositoryInterface getRepository()
 */
class PunchoutCatalogsFacade extends AbstractFacade implements PunchoutCatalogsFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $connectionId
     *
     * @return \Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer|null
     */
    public function findConnectionById(int $connectionId): ?PunchoutCatalogConnectionTransfer
    {
        return $this->getFactory()
            ->createPunchoutCatalogsReader()
            ->findConnectionById($connectionId);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer $punchoutCatalogConnectionTransfer
     *
     * @return \Generated\Shared\Transfer\PunchoutCatalogResponseTransfer
     */
    public function createConnection(PunchoutCatalogConnectionTransfer $punchoutCatalogConnectionTransfer): PunchoutCatalogResponseTransfer
    {
        return $this->getFactory()
            ->createPunchoutCatalogsWriter()
            ->createConnection($punchoutCatalogConnectionTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer $punchoutCatalogConnectionTransfer
     *
     * @return \Generated\Shared\Transfer\PunchoutCatalogResponseTransfer
     */
    public function updateConnection(PunchoutCatalogConnectionTransfer $punchoutCatalogConnectionTransfer): PunchoutCatalogResponseTransfer
    {
        return $this->getFactory()
            ->createPunchoutCatalogsWriter()
            ->updateConnection($punchoutCatalogConnectionTransfer);
    }
}
