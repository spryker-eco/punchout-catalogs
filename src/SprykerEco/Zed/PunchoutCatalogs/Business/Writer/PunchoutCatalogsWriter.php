<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\PunchoutCatalogs\Business\Writer;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer;
use Generated\Shared\Transfer\PunchoutCatalogResponseTransfer;
use SprykerEco\Zed\PunchoutCatalogs\Persistence\PunchoutCatalogsEntityManagerInterface;

class PunchoutCatalogsWriter implements PunchoutCatalogsWriterInterface
{
    protected const MESSAGE_ERROR_DURING_CONNECTION_UPDATE = 'Error during connection update';

    /**
     * @var \SprykerEco\Zed\PunchoutCatalogs\Persistence\PunchoutCatalogsEntityManagerInterface
     */
    protected $punchoutCatalogsEntityManager;

    /**
     * @param \SprykerEco\Zed\PunchoutCatalogs\Persistence\PunchoutCatalogsEntityManagerInterface $punchoutCatalogsEntityManager
     */
    public function __construct(
        PunchoutCatalogsEntityManagerInterface $punchoutCatalogsEntityManager
    ) {
        $this->punchoutCatalogsEntityManager = $punchoutCatalogsEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer $punchoutCatalogConnectionTransfer
     *
     * @return \Generated\Shared\Transfer\PunchoutCatalogResponseTransfer
     */
    public function createConnection(PunchoutCatalogConnectionTransfer $punchoutCatalogConnectionTransfer): PunchoutCatalogResponseTransfer
    {
        $punchoutCatalogConnectionTransfer = $this->punchoutCatalogsEntityManager->createPunchoutCatalogConnection($punchoutCatalogConnectionTransfer);

        if (!$punchoutCatalogConnectionTransfer->getIdPunchoutCatalogConnection()) {
            return (new PunchoutCatalogResponseTransfer())
                ->addMessage((new MessageTransfer())->setValue(static::MESSAGE_ERROR_DURING_CONNECTION_UPDATE))
                ->setIsSuccessful(false);
        }

        return (new PunchoutCatalogResponseTransfer())
            ->setPunchoutCatalogConnection($punchoutCatalogConnectionTransfer)
            ->setIsSuccessful(true);
    }

    /**
     * @param \Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer $punchoutCatalogConnectionTransfer
     *
     * @return \Generated\Shared\Transfer\PunchoutCatalogResponseTransfer
     */
    public function updateConnection(PunchoutCatalogConnectionTransfer $punchoutCatalogConnectionTransfer): PunchoutCatalogResponseTransfer
    {
        $punchoutCatalogConnectionTransfer->requireIdPunchoutCatalogConnection();

        $isSuccessful = $this->punchoutCatalogsEntityManager->updatePunchoutCatalogConnection($punchoutCatalogConnectionTransfer);

        if ($isSuccessful) {
            return (new PunchoutCatalogResponseTransfer())
                ->setPunchoutCatalogConnection($punchoutCatalogConnectionTransfer)
                ->setIsSuccessful(true);
        }

        return (new PunchoutCatalogResponseTransfer())
            ->addMessage((new MessageTransfer())->setValue(static::MESSAGE_ERROR_DURING_CONNECTION_UPDATE))
            ->setIsSuccessful(false);
    }
}
