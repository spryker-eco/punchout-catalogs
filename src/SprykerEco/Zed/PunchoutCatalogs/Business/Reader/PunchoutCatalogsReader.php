<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\PunchoutCatalogs\Business\Reader;

use Generated\Shared\Transfer\PunchoutCatalogConnectionCollectionTransfer;
use Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer;
use SprykerEco\Zed\PunchoutCatalogs\Dependency\Facade\PunchoutCatalogsToVaultFacadeInterface;
use SprykerEco\Zed\PunchoutCatalogs\Persistence\PunchoutCatalogsRepositoryInterface;

class PunchoutCatalogsReader implements PunchoutCatalogsReaderInterface
{
    protected const CATALOG_CONNECTION_PASSWORD_VAULT_DATA_TYPE = 'pwg_punchout_catalog_connection.password';

    /**
     * @var \SprykerEco\Zed\PunchoutCatalogs\Persistence\PunchoutCatalogsRepositoryInterface
     */
    protected $punchoutCatalogsRepository;

    /**
     * @var \SprykerEco\Zed\PunchoutCatalogs\Dependency\Facade\PunchoutCatalogsToVaultFacadeInterface
     */
    protected $vaultFacade;

    /**
     * @param \SprykerEco\Zed\PunchoutCatalogs\Persistence\PunchoutCatalogsRepositoryInterface $punchoutCatalogsRepository
     * @param \SprykerEco\Zed\PunchoutCatalogs\Dependency\Facade\PunchoutCatalogsToVaultFacadeInterface $vaultFacade
     */
    public function __construct(PunchoutCatalogsRepositoryInterface $punchoutCatalogsRepository, PunchoutCatalogsToVaultFacadeInterface $vaultFacade)
    {
        $this->punchoutCatalogsRepository = $punchoutCatalogsRepository;
        $this->vaultFacade = $vaultFacade;
    }

    /**
     * @param int $idConnection
     *
     * @return \Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer|null
     */
    public function findConnectionById(int $idConnection): ?PunchoutCatalogConnectionTransfer
    {
        return $this->punchoutCatalogsRepository->findConnectionById($idConnection);
    }

    /**
     * @param int $idConnection
     *
     * @return \Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer|null
     */
    public function findConnectionByIdWithPassword(int $idConnection): ?PunchoutCatalogConnectionTransfer
    {
        $punchoutCatalogConnectionTransfer = $this->punchoutCatalogsRepository->findConnectionById($idConnection);

        if (!$punchoutCatalogConnectionTransfer) {
            return $punchoutCatalogConnectionTransfer;
        }

        $password = $this->vaultFacade->retrieve(
            static::CATALOG_CONNECTION_PASSWORD_VAULT_DATA_TYPE,
            (string)$punchoutCatalogConnectionTransfer->getIdPunchoutCatalogConnection()
        );

        return $punchoutCatalogConnectionTransfer->setPassword($password);
    }

    /**
     * @param int $fkCompanyBusinessUnit
     *
     * @return \Generated\Shared\Transfer\PunchoutCatalogConnectionCollectionTransfer|null
     */
    public function findConnectionByFkCompanyBusinessUnit(
        int $fkCompanyBusinessUnit
    ): ?PunchoutCatalogConnectionCollectionTransfer {
        return $this->punchoutCatalogsRepository->findConnectionByFkCompanyBusinessUnit($fkCompanyBusinessUnit);
    }

    /**
     * @param int $fkCompanyUser
     *
     * @return \Generated\Shared\Transfer\PunchoutCatalogConnectionCollectionTransfer|null
     */
    public function findConnectionByFkCompanyUser(int $fkCompanyUser): ?PunchoutCatalogConnectionCollectionTransfer {
        return $this->punchoutCatalogsRepository->findConnectionByFkCompanyUser($fkCompanyUser);
    }
}
