<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\PunchoutCatalogs\Business;

use Generated\Shared\Transfer\CompanyBusinessUnitResponseTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyUserResponseTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer;
use Generated\Shared\Transfer\PunchoutCatalogResponseTransfer;
use Generated\Shared\Transfer\PunchoutCatalogTransactionTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \SprykerEco\Zed\PunchoutCatalogs\Business\PunchoutCatalogsBusinessFactory getFactory()
 * @method \SprykerEco\Zed\PunchoutCatalogs\Persistence\PunchoutCatalogsEntityManagerInterface getEntityManager()
 * @method \SprykerEco\Zed\PunchoutCatalogs\Persistence\PunchoutCatalogsRepositoryInterface getRepository()
 */
class PunchoutCatalogsFacade extends AbstractFacade implements PunchoutCatalogsFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idConnection
     *
     * @return \Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer|null
     */
    public function findConnectionById(int $idConnection): ?PunchoutCatalogConnectionTransfer
    {
        return $this->getFactory()
            ->createPunchoutCatalogsReader()
            ->findConnectionById($idConnection);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idConnection
     *
     * @return \Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer|null
     */
    public function findConnectionByIdWithPassword(int $idConnection): ?PunchoutCatalogConnectionTransfer
    {
        return $this->getFactory()
            ->createPunchoutCatalogsReader()
            ->findConnectionByIdWithPassword($idConnection);
    }

    /**
     * {@inheritDoc}
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
     * {@inheritDoc}
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

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idPunchoutCatalogTransaction
     *
     * @return \Generated\Shared\Transfer\PunchoutCatalogTransactionTransfer|null
     */
    public function findTransactionById(int $idPunchoutCatalogTransaction): ?PunchoutCatalogTransactionTransfer
    {
        return $this->getRepository()
            ->findTransactionById($idPunchoutCatalogTransaction);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitResponseTransfer
     */
    public function isCompanyBusinessUnitDeletable(CompanyBusinessUnitTransfer $companyBusinessUnitTransfer): CompanyBusinessUnitResponseTransfer
    {
        return $this->getFactory()
            ->createCompanyBusinessUnitDeleteChecker()
            ->isCompanyBusinessUnitDeletable($companyBusinessUnitTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserResponseTransfer
     */
    public function isCompanyUserDeletable(CompanyUserTransfer $companyUserTransfer): CompanyUserResponseTransfer
    {
        return $this->getFactory()
            ->createCompanyUserDeleteChecker()
            ->isCompanyUserDeletable($companyUserTransfer);
    }
}
