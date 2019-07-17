<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PunchoutCatalogs\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group PunchoutCatalogs
 * @group Business
 * @group Facade
 * @group PunchoutCatalogsFacadeTest
 * Add your own group annotations below this line
 */
class PunchoutCatalogsFacadeTest extends Unit
{
    protected const NOT_EXISTING_CONNECTION_ID = 0;
    protected const NOT_EXISTING_TRANSACTION_ID = 0;

    /**
     * @var \SprykerEcoTest\Zed\PunchoutCatalogs\PunchoutCatalogsBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testFindConnectionByIdReturnsNullWhenConnectionNotFoundByProvidedId(): void
    {
        // Arrange
        $idPunchoutCatalogConnection = static::NOT_EXISTING_CONNECTION_ID;

        // Act
        $punchoutConnectionTransfer = $this->tester->getFacade()
            ->findConnectionById($idPunchoutCatalogConnection);

        // Assert
        $this->assertNull($punchoutConnectionTransfer);
    }

    /**
     * @return void
     */
    public function testFindConnectionByIdRetrievesConnectionWhenItExists(): void
    {
        // Arrange
        $idPunchoutCatalogConnection = $this->tester->createPunchoutCatalogConnection(
            $this->tester->createCompanyBusinessUnit()
        )->getIdPunchoutCatalogConnection();

        // Act
        $punchoutCatalogConnectionTransfer = $this->tester->getFacade()
            ->findConnectionById($idPunchoutCatalogConnection);

        // Assert
        $this->assertNotNull($punchoutCatalogConnectionTransfer);
        $this->assertEquals($idPunchoutCatalogConnection, $punchoutCatalogConnectionTransfer->getIdPunchoutCatalogConnection());
    }

    /**
     * @return void
     */
    public function testFindConnectionByIdWithPasswordRetrievesPasswordFormVaultWhenItExists(): void
    {
        // Arrange
        $punchoutCatalogConnectionTransfer = $this->tester->createPunchoutCatalogConnection(
            $this->tester->createCompanyBusinessUnit()
        );

        // Act
        $persistentPunchoutCatalogConnectionTransfer = $this->tester->getFacade()
            ->findConnectionByIdWithPassword($punchoutCatalogConnectionTransfer->getIdPunchoutCatalogConnection());

        // Assert
        $this->assertNotNull($persistentPunchoutCatalogConnectionTransfer);
        $this->assertEquals($punchoutCatalogConnectionTransfer->getPassword(), $persistentPunchoutCatalogConnectionTransfer->getPassword());
    }

    /**
     * @return void
     */
    public function testCreateConnectionCreatesStoresPasswordToVault(): void
    {
        // Arrange
        $punchoutCatalogConnectionTransfer = $this->tester->createPunchoutCatalogConnectionTransfer();

        // Act
        $punchoutCatalogResponseTransfer = $this->tester->getFacade()
            ->createConnection($punchoutCatalogConnectionTransfer);

        // Assert
        $this->assertTrue($punchoutCatalogResponseTransfer->getIsSuccessful());

        $password = $this->tester->retrieveConnectionPasswordFromVault(
            $punchoutCatalogResponseTransfer->getPunchoutCatalogConnection()
                ->getIdPunchoutCatalogConnection()
        );

        $this->assertEquals($punchoutCatalogConnectionTransfer->getPassword(), $password);
    }

    /**
     * @return void
     */
    public function testCreateConnectionCreatesConnectionWhenAllParametersAreSet(): void
    {
        // Arrange
        $punchoutCatalogConnectionTransfer = $this->tester->createPunchoutCatalogConnectionTransfer();

        // Act
        $punchoutCatalogResponseTransfer = $this->tester->getFacade()
            ->createConnection($punchoutCatalogConnectionTransfer);

        // Assert
        $this->assertTrue($punchoutCatalogResponseTransfer->getIsSuccessful());
        $this->assertNotNull($punchoutCatalogResponseTransfer->getPunchoutCatalogConnection());
        $this->assertNotNull($punchoutCatalogResponseTransfer->getPunchoutCatalogConnection()->getIdPunchoutCatalogConnection());
    }

    /**
     * @return void
     */
    public function testUpdateConnectionUpdatesConnectionWhenItExists(): void
    {
        // Arrange
        $punchoutCatalogConnectionTransfer = $this->tester->createPunchoutCatalogConnection(
            $this->tester->createCompanyBusinessUnit()
        );

        $punchoutCatalogConnectionTransfer = $this->tester->getFacade()
            ->findConnectionById($punchoutCatalogConnectionTransfer->getIdPunchoutCatalogConnection());

        $punchoutCatalogConnectionTransfer->setUsername('Updated username');

        // Act
        $punchoutCatalogResponseTransfer = $this->tester->getFacade()
            ->updateConnection($punchoutCatalogConnectionTransfer);

        // Assert
        $this->assertTrue($punchoutCatalogResponseTransfer->getIsSuccessful());

        $updatedPunchoutCatalogConnectionTransfer = $this->tester->getFacade()
            ->findConnectionById($punchoutCatalogConnectionTransfer->getIdPunchoutCatalogConnection());

        $this->assertEquals($punchoutCatalogConnectionTransfer->getUsername(), $updatedPunchoutCatalogConnectionTransfer->getUsername());
    }

    /**
     * @return void
     */
    public function testUpdateConnectionUnsuccessfulIfNoConnectionExist(): void
    {
        // Arrange
        $punchoutCatalogConnectionTransfer = new PunchoutCatalogConnectionTransfer();
        $punchoutCatalogConnectionTransfer->setIdPunchoutCatalogConnection(static::NOT_EXISTING_CONNECTION_ID);

        // Act
        $punchoutCatalogResponseTransfer = $this->tester->getFacade()
            ->updateConnection($punchoutCatalogConnectionTransfer);

        // Assert
        $this->assertFalse($punchoutCatalogResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testFindTransactionByIdReturnsNullWhenTransactionNotFoundByProvidedId(): void
    {
        // Arrange
        $idPunchoutCatalogTransaction = static::NOT_EXISTING_TRANSACTION_ID;

        // Act
        $punchoutTransactionTransfer = $this->tester->getFacade()
            ->findConnectionById($idPunchoutCatalogTransaction);

        // Assert
        $this->assertNull($punchoutTransactionTransfer);
    }

    /**
     * @return void
     */
    public function testFindTransactionByIdRetrievesTransactionWhenItExists(): void
    {
        // Arrange
        $punchoutCatalogTransactionTransfer = $this->tester->createPunchoutCatalogTransaction();

        // Act
        $punchoutCatalogTransactionTransfer = $this->tester->getFacade()
            ->findTransactionById($punchoutCatalogTransactionTransfer->getIdPunchoutCatalogTransaction());

        // Assert
        $this->assertNotNull($punchoutCatalogTransactionTransfer);
        $this->assertEquals($punchoutCatalogTransactionTransfer->getIdPunchoutCatalogTransaction(), $punchoutCatalogTransactionTransfer->getIdPunchoutCatalogTransaction());
    }
}
