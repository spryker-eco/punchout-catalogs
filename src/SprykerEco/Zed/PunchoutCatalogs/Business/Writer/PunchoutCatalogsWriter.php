<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\PunchoutCatalogs\Business\Writer;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer;
use Generated\Shared\Transfer\PunchoutCatalogResponseTransfer;
use SprykerEco\Zed\PunchoutCatalogs\Dependency\Facade\PunchoutCatalogsToVaultFacadeInterface;
use SprykerEco\Zed\PunchoutCatalogs\Persistence\PunchoutCatalogsEntityManagerInterface;

class PunchoutCatalogsWriter implements PunchoutCatalogsWriterInterface
{
    protected const MESSAGE_ERROR_DURING_CONNECTION_UPDATE = 'Error during connection update';
    protected const MESSAGE_ERROR_DURING_CONNECTION_CREATION = 'Error during connection creation';
    protected const PASSWORD_VAULT_DATA_TYPE = 'pwg_punchout_catalog_connection.password';

    /**
     * @var \SprykerEco\Zed\PunchoutCatalogs\Persistence\PunchoutCatalogsEntityManagerInterface
     */
    protected $punchoutCatalogsEntityManager;

    /**
     * @var \SprykerEco\Zed\PunchoutCatalogs\Dependency\Facade\PunchoutCatalogsToVaultFacadeInterface
     */
    protected $vaultFacade;

    /**
     * @param \SprykerEco\Zed\PunchoutCatalogs\Persistence\PunchoutCatalogsEntityManagerInterface $punchoutCatalogsEntityManager
     * @param \SprykerEco\Zed\PunchoutCatalogs\Dependency\Facade\PunchoutCatalogsToVaultFacadeInterface $vaultFacade
     */
    public function __construct(
        PunchoutCatalogsEntityManagerInterface $punchoutCatalogsEntityManager,
        PunchoutCatalogsToVaultFacadeInterface $vaultFacade
    ) {
        $this->punchoutCatalogsEntityManager = $punchoutCatalogsEntityManager;
        $this->vaultFacade = $vaultFacade;
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
            return $this->createUnsuccessfulPunchoutCatalogResponseTransfer(static::MESSAGE_ERROR_DURING_CONNECTION_CREATION);
        }

        return $this->storePassword($punchoutCatalogConnectionTransfer, static::MESSAGE_ERROR_DURING_CONNECTION_UPDATE);
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

        if (!$isSuccessful) {
            return $this->createUnsuccessfulPunchoutCatalogResponseTransfer(static::MESSAGE_ERROR_DURING_CONNECTION_UPDATE);
        }

        return $this->storePassword($punchoutCatalogConnectionTransfer, static::MESSAGE_ERROR_DURING_CONNECTION_UPDATE);
    }

    /**
     * @param \Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer $punchoutCatalogConnectionTransfer
     * @param string $errorMessage
     *
     * @return \Generated\Shared\Transfer\PunchoutCatalogResponseTransfer
     */
    protected function storePassword(PunchoutCatalogConnectionTransfer $punchoutCatalogConnectionTransfer, string $errorMessage): PunchoutCatalogResponseTransfer
    {
        if (!$punchoutCatalogConnectionTransfer->getPassword()) {
            return $this->createSuccessfulResponseTransfer($punchoutCatalogConnectionTransfer);
        }

        $isSuccessful = $this->vaultFacade->store(
            static::PASSWORD_VAULT_DATA_TYPE,
            (string)$punchoutCatalogConnectionTransfer->getIdPunchoutCatalogConnection(),
            $punchoutCatalogConnectionTransfer->getPassword()
        );

        if ($isSuccessful) {
            return $this->createSuccessfulResponseTransfer($punchoutCatalogConnectionTransfer);
        }

        return $this->createUnsuccessfulPunchoutCatalogResponseTransfer($errorMessage);
    }

    /**
     * @param string $message
     *
     * @return \Generated\Shared\Transfer\PunchoutCatalogResponseTransfer
     */
    protected function createUnsuccessfulPunchoutCatalogResponseTransfer(string $message): PunchoutCatalogResponseTransfer
    {
        return (new PunchoutCatalogResponseTransfer())
            ->addMessage((new MessageTransfer())->setValue($message))
            ->setIsSuccessful(false);
    }

    /**
     * @param \Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer $punchoutCatalogConnectionTransfer
     *
     * @return \Generated\Shared\Transfer\PunchoutCatalogResponseTransfer
     */
    protected function createSuccessfulResponseTransfer(PunchoutCatalogConnectionTransfer $punchoutCatalogConnectionTransfer): PunchoutCatalogResponseTransfer
    {
        return (new PunchoutCatalogResponseTransfer())
            ->setPunchoutCatalogConnection($punchoutCatalogConnectionTransfer)
            ->setIsSuccessful(true);
    }
}
