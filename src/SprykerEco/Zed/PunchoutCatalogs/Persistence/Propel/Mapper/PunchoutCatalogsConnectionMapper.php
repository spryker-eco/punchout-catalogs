<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\PunchoutCatalogs\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\PunchoutCatalogConnectionCartTransfer;
use Generated\Shared\Transfer\PunchoutCatalogConnectionSetupTransfer;
use Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer;
use Generated\Shared\Transfer\PunchoutCatalogTransactionTransfer;
use Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnit;
use Orm\Zed\PunchoutCatalog\Persistence\PgwPunchoutCatalogConnection;
use Orm\Zed\PunchoutCatalog\Persistence\PgwPunchoutCatalogTransaction;

class PunchoutCatalogsConnectionMapper
{
    /**
     * @var \SprykerEco\Zed\PunchoutCatalogs\Persistence\Propel\Mapper\PunchoutCatalogsConnectionSetupMapper
     */
    protected $punchoutCatalogConnectionSetupMapper;

    /**
     * @var \SprykerEco\Zed\PunchoutCatalogs\Persistence\Propel\Mapper\PunchoutCatalogsConnectionCartMapper
     */
    protected $punchoutCatalogConnectionCartMapper;

    /**
     * @param \SprykerEco\Zed\PunchoutCatalogs\Persistence\Propel\Mapper\PunchoutCatalogsConnectionSetupMapper $punchoutCatalogConnectionSetupMapper
     * @param \SprykerEco\Zed\PunchoutCatalogs\Persistence\Propel\Mapper\PunchoutCatalogsConnectionCartMapper $punchoutCatalogConnectionCartMapper
     */
    public function __construct(PunchoutCatalogsConnectionSetupMapper $punchoutCatalogConnectionSetupMapper, PunchoutCatalogsConnectionCartMapper $punchoutCatalogConnectionCartMapper)
    {
        $this->punchoutCatalogConnectionSetupMapper = $punchoutCatalogConnectionSetupMapper;
        $this->punchoutCatalogConnectionCartMapper = $punchoutCatalogConnectionCartMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer $punchoutCatalogConnectionTransfer
     * @param \Orm\Zed\PunchoutCatalog\Persistence\PgwPunchoutCatalogConnection $punchoutCatalogConnectionEntity
     *
     * @return \Orm\Zed\PunchoutCatalog\Persistence\PgwPunchoutCatalogConnection
     */
    public function mapPunchoutCatalogConnectionTransferToEntity(
        PunchoutCatalogConnectionTransfer $punchoutCatalogConnectionTransfer,
        PgwPunchoutCatalogConnection $punchoutCatalogConnectionEntity
    ): PgwPunchoutCatalogConnection {
        $punchoutCatalogConnectionEntity->fromArray($punchoutCatalogConnectionTransfer->toArray());

        return $punchoutCatalogConnectionEntity;
    }

    /**
     * @param \Orm\Zed\PunchoutCatalog\Persistence\PgwPunchoutCatalogConnection $punchoutCatalogConnectionEntity
     * @param \Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer $punchoutCatalogConnectionTransfer
     *
     * @return \Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer
     */
    public function mapPunchoutCatalogConnectionEntityToTransfer(
        PgwPunchoutCatalogConnection $punchoutCatalogConnectionEntity,
        PunchoutCatalogConnectionTransfer $punchoutCatalogConnectionTransfer
    ): PunchoutCatalogConnectionTransfer {
        $punchoutCatalogConnectionTransfer->fromArray($punchoutCatalogConnectionEntity->toArray(), true);

        if ($punchoutCatalogConnectionEntity->getPgwPunchoutCatalogConnectionSetup()) {
            $punchoutCatalogConnectionTransfer->setSetup(
                $this->punchoutCatalogConnectionSetupMapper->mapPunchoutCatalogConnectionSetupEntityToTransfer(
                    $punchoutCatalogConnectionEntity->getPgwPunchoutCatalogConnectionSetup(),
                    new PunchoutCatalogConnectionSetupTransfer()
                )
            );
        }

        if ($punchoutCatalogConnectionEntity->getPgwPunchoutCatalogConnectionCart()) {
            $punchoutCatalogConnectionTransfer->setCart(
                $this->punchoutCatalogConnectionCartMapper->mapPunchoutCatalogConnectionCartEntityToTransfer(
                    $punchoutCatalogConnectionEntity->getPgwPunchoutCatalogConnectionCart(),
                    new PunchoutCatalogConnectionCartTransfer()
                )
            );
        }

        if ($punchoutCatalogConnectionEntity->getCompanyBusinessUnit()) {
            $punchoutCatalogConnectionTransfer->setCompanyBusinessUnit(
                $this->mapCompanyBusinessUnitEntityToTransfer(
                    $punchoutCatalogConnectionEntity->getCompanyBusinessUnit(),
                    new CompanyBusinessUnitTransfer()
                )
            );
        }

        return $punchoutCatalogConnectionTransfer;
    }

    /**
     * @param \Orm\Zed\PunchoutCatalog\Persistence\PgwPunchoutCatalogTransaction $punchoutCatalogTransaction
     * @param \Generated\Shared\Transfer\PunchoutCatalogTransactionTransfer $punchoutCatalogTransactionTransfer
     *
     * @return \Generated\Shared\Transfer\PunchoutCatalogTransactionTransfer
     */
    public function mapPunchoutCatalogTransactionEntityToTransfer(
        PgwPunchoutCatalogTransaction $punchoutCatalogTransaction,
        PunchoutCatalogTransactionTransfer $punchoutCatalogTransactionTransfer
    ): PunchoutCatalogTransactionTransfer {
        $punchoutCatalogTransactionTransfer->fromArray($punchoutCatalogTransaction->toArray(), true);

        if ($punchoutCatalogTransaction->getPunchoutCatalogConnection()) {
            $punchoutCatalogTransactionTransfer->setConnection(
                $this->mapPunchoutCatalogConnectionEntityToTransfer(
                    $punchoutCatalogTransaction->getPunchoutCatalogConnection(),
                    new PunchoutCatalogConnectionTransfer()
                )
            );
        }

        return $punchoutCatalogTransactionTransfer;
    }

    /**
     * @param \Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnit $companyBusinessUnitEntity
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitTransfer
     */
    public function mapCompanyBusinessUnitEntityToTransfer(
        SpyCompanyBusinessUnit $companyBusinessUnitEntity,
        CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
    ): CompanyBusinessUnitTransfer {
        return $companyBusinessUnitTransfer->fromArray($companyBusinessUnitEntity->toArray(), true);
    }
}
