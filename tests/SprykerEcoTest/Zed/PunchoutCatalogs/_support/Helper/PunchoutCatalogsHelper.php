<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\PunchoutCatalogs\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\PunchoutCatalogConnectionBuilder;
use Generated\Shared\DataBuilder\PunchoutCatalogTransactionBuilder;
use Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer;
use Generated\Shared\Transfer\PunchoutCatalogTransactionTransfer;
use Orm\Zed\PunchoutCatalog\Persistence\PgwPunchoutCatalogTransaction;
use Orm\Zed\PunchoutCatalog\Persistence\PgwPunchoutCatalogTransactionQuery;
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

    /**
     * @param array $seed
     *
     * @return \Generated\Shared\Transfer\PunchoutCatalogTransactionTransfer
     */
    public function havePunchoutCatalogTransaction(array $seed = []): PunchoutCatalogTransactionTransfer
    {
        $punchoutCatalogConnectionTransfer = (new PunchoutCatalogTransactionBuilder($seed))->build();

        $punchoutCatalogTransactionEntity = $this->createPunchoutCatalogTransaction();
        $punchoutCatalogTransactionEntity->fromArray($punchoutCatalogConnectionTransfer->toArray());

        if ($punchoutCatalogConnectionTransfer->getConnection()) {
            $punchoutCatalogTransactionEntity->setFkPunchoutCatalogConnection(
                $punchoutCatalogConnectionTransfer->getConnection()->getIdPunchoutCatalogConnection()
            );
        }

        if ($punchoutCatalogConnectionTransfer->getCompanyBusinessUnit()) {
            $punchoutCatalogTransactionEntity->setFkCompanyBusinessUnit(
                $punchoutCatalogConnectionTransfer->getCompanyBusinessUnit()->getIdCompanyBusinessUnit()
            );
        }
        $punchoutCatalogTransactionEntity->save();

        return $punchoutCatalogConnectionTransfer->setIdPunchoutCatalogTransaction(
            $punchoutCatalogTransactionEntity->getIdPunchoutCatalogTransaction()
        );
    }

    /**
     * @return \Orm\Zed\PunchoutCatalog\Persistence\PgwPunchoutCatalogTransaction
     */
    protected function createPunchoutCatalogTransaction(): PgwPunchoutCatalogTransaction
    {
        return new PgwPunchoutCatalogTransaction();
    }
}
