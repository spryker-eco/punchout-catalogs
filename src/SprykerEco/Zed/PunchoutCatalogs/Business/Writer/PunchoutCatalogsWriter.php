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
    /**
     * @var \SprykerEco\Zed\PunchoutCatalogs\Persistence\PunchoutCatalogsEntityManagerInterface
     */
    protected $punchoutCatalogEntityManager;

    /**
     * @param \SprykerEco\Zed\PunchoutCatalogs\Persistence\PunchoutCatalogsEntityManagerInterface $punchoutCatalogEntityManager
     */
    public function __construct(PunchoutCatalogsEntityManagerInterface $punchoutCatalogEntityManager)
    {
        $this->punchoutCatalogEntityManager = $punchoutCatalogEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer $punchoutCatalogConnectionTransfer
     *
     * @return \Generated\Shared\Transfer\PunchoutCatalogResponseTransfer
     */
    public function createConnection(PunchoutCatalogConnectionTransfer $punchoutCatalogConnectionTransfer): PunchoutCatalogResponseTransfer
    {
        $isSuccessful = $this->punchoutCatalogEntityManager->createPunchoutCatalogConnection($punchoutCatalogConnectionTransfer);

        if ($isSuccessful) {
            return (new PunchoutCatalogResponseTransfer())
                ->setIsSuccessful(true);
        }

        return (new PunchoutCatalogResponseTransfer())
            ->addMessage((new MessageTransfer())->setValue('Error during connection creation'))
            ->setIsSuccessful(false);
    }

    /**
     * @param \Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer $punchoutCatalogConnectionTransfer
     *
     * @return \Generated\Shared\Transfer\PunchoutCatalogResponseTransfer
     */
    public function updateConnection(PunchoutCatalogConnectionTransfer $punchoutCatalogConnectionTransfer): PunchoutCatalogResponseTransfer
    {
        $punchoutCatalogConnectionTransfer->requireIdPunchoutCatalogConnection();

        $isSuccessful = $this->punchoutCatalogEntityManager->updatePunchoutCatalogConnection($punchoutCatalogConnectionTransfer);

        if ($isSuccessful) {
            return (new PunchoutCatalogResponseTransfer())
                ->setIsSuccessful(true);
        }

        return (new PunchoutCatalogResponseTransfer())
            ->addMessage((new MessageTransfer())->setValue('Error during connection update'))
            ->setIsSuccessful(false);
    }
}
