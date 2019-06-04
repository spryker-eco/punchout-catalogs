<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\PunchoutCatalogs\Business\Writer;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer;
use Generated\Shared\Transfer\PunchoutCatalogResponseTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use SprykerEco\Zed\PunchoutCatalogs\Business\Exception\InvalidPunchoutCatalogConnectionCreationException;
use SprykerEco\Zed\PunchoutCatalogs\Business\Exception\InvalidPunchoutCatalogConnectionUpdateException;
use SprykerEco\Zed\PunchoutCatalogs\Dependency\Facade\PunchoutCatalogsToVaultFacadeInterface;
use SprykerEco\Zed\PunchoutCatalogs\Persistence\PunchoutCatalogsEntityManagerInterface;
use SprykerEco\Zed\PunchoutCatalogs\Persistence\PunchoutCatalogsRepositoryInterface;

class PunchoutCatalogsWriter implements PunchoutCatalogsWriterInterface
{
    use TransactionTrait;

    protected const MESSAGE_ERROR_DURING_CONNECTION_UPDATE = 'Error during connection update';
    protected const MESSAGE_ERROR_DURING_CONNECTION_CREATION = 'Error during connection creation';
    protected const PASSWORD_VAULT_DATA_TYPE = 'pwg_punchout_catalog_connection.password';

    /**
     * @var \SprykerEco\Zed\PunchoutCatalogs\Persistence\PunchoutCatalogsEntityManagerInterface
     */
    protected $punchoutCatalogsEntityManager;

    /**
     * @var \SprykerEco\Zed\PunchoutCatalogs\Persistence\PunchoutCatalogsRepositoryInterface
     */
    protected $punchoutCatalogsRepository;

    /**
     * @var \SprykerEco\Zed\PunchoutCatalogs\Dependency\Facade\PunchoutCatalogsToVaultFacadeInterface
     */
    protected $vaultFacade;

    /**
     * @param \SprykerEco\Zed\PunchoutCatalogs\Persistence\PunchoutCatalogsEntityManagerInterface $punchoutCatalogsEntityManager
     * @param \SprykerEco\Zed\PunchoutCatalogs\Dependency\Facade\PunchoutCatalogsToVaultFacadeInterface $vaultFacade
     * @param \SprykerEco\Zed\PunchoutCatalogs\Persistence\PunchoutCatalogsRepositoryInterface $punchoutCatalogsRepository
     */
    public function __construct(
        PunchoutCatalogsEntityManagerInterface $punchoutCatalogsEntityManager,
        PunchoutCatalogsToVaultFacadeInterface $vaultFacade,
        PunchoutCatalogsRepositoryInterface $punchoutCatalogsRepository
    ) {
        $this->punchoutCatalogsEntityManager = $punchoutCatalogsEntityManager;
        $this->vaultFacade = $vaultFacade;
        $this->punchoutCatalogsRepository = $punchoutCatalogsRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer $punchoutCatalogConnectionTransfer
     *
     * @return \Generated\Shared\Transfer\PunchoutCatalogResponseTransfer
     */
    public function createConnection(PunchoutCatalogConnectionTransfer $punchoutCatalogConnectionTransfer): PunchoutCatalogResponseTransfer
    {
        try {
            return $this->getTransactionHandler()->handleTransaction(function () use ($punchoutCatalogConnectionTransfer) {
                return $this->executeCreateConnectionTransaction($punchoutCatalogConnectionTransfer);
            });
        } catch (InvalidPunchoutCatalogConnectionCreationException $exception) {
            return (new PunchoutCatalogResponseTransfer())
                ->addMessage((new MessageTransfer())->setValue($exception->getMessage()))
                ->setIsSuccessful(false);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer $punchoutCatalogConnectionTransfer
     *
     * @throws \SprykerEco\Zed\PunchoutCatalogs\Business\Exception\InvalidPunchoutCatalogConnectionCreationException
     *
     * @return \Generated\Shared\Transfer\PunchoutCatalogResponseTransfer
     */
    protected function executeCreateConnectionTransaction(PunchoutCatalogConnectionTransfer $punchoutCatalogConnectionTransfer): PunchoutCatalogResponseTransfer
    {
        $punchoutCatalogConnectionTransfer = $this->punchoutCatalogsEntityManager->createPunchoutCatalogConnection($punchoutCatalogConnectionTransfer);

        if (!$punchoutCatalogConnectionTransfer->getIdPunchoutCatalogConnection()) {
            throw new InvalidPunchoutCatalogConnectionCreationException(static::MESSAGE_ERROR_DURING_CONNECTION_CREATION);
        }

        $isPasswordStored = $this->storePassword($punchoutCatalogConnectionTransfer);

        if (!$isPasswordStored) {
            throw new InvalidPunchoutCatalogConnectionCreationException(static::MESSAGE_ERROR_DURING_CONNECTION_CREATION);
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
        try {
            return $this->getTransactionHandler()->handleTransaction(function () use ($punchoutCatalogConnectionTransfer) {
                return $this->executeUpdateConnectionTransaction($punchoutCatalogConnectionTransfer);
            });
        } catch (InvalidPunchoutCatalogConnectionUpdateException $exception) {
            return (new PunchoutCatalogResponseTransfer())
                ->addMessage((new MessageTransfer())->setValue($exception->getMessage()))
                ->setIsSuccessful(false);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer $punchoutCatalogConnectionTransfer
     *
     * @throws \SprykerEco\Zed\PunchoutCatalogs\Business\Exception\InvalidPunchoutCatalogConnectionUpdateException
     *
     * @return \Generated\Shared\Transfer\PunchoutCatalogResponseTransfer
     */
    protected function executeUpdateConnectionTransaction(PunchoutCatalogConnectionTransfer $punchoutCatalogConnectionTransfer): PunchoutCatalogResponseTransfer
    {
        $punchoutCatalogConnectionTransfer->requireIdPunchoutCatalogConnection();

        if (!$this->punchoutCatalogsRepository->findConnectionById($punchoutCatalogConnectionTransfer->getIdPunchoutCatalogConnection())) {
            throw new InvalidPunchoutCatalogConnectionUpdateException(static::MESSAGE_ERROR_DURING_CONNECTION_UPDATE);
        }

        $this->punchoutCatalogsEntityManager->updatePunchoutCatalogConnection($punchoutCatalogConnectionTransfer);
        $isPasswordStored = $this->storePassword($punchoutCatalogConnectionTransfer);

        if (!$isPasswordStored) {
            throw new InvalidPunchoutCatalogConnectionUpdateException(static::MESSAGE_ERROR_DURING_CONNECTION_UPDATE);
        }

        return (new PunchoutCatalogResponseTransfer())
            ->setPunchoutCatalogConnection($punchoutCatalogConnectionTransfer)
            ->setIsSuccessful(true);
    }

    /**
     * @param \Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer $punchoutCatalogConnectionTransfer
     *
     * @return bool
     */
    protected function storePassword(PunchoutCatalogConnectionTransfer $punchoutCatalogConnectionTransfer): bool
    {
        if (!$punchoutCatalogConnectionTransfer->getPassword()) {
            return true;
        }

        return $this->vaultFacade->store(
            static::PASSWORD_VAULT_DATA_TYPE,
            (string)$punchoutCatalogConnectionTransfer->getIdPunchoutCatalogConnection(),
            $punchoutCatalogConnectionTransfer->getPassword()
        );
    }
}
