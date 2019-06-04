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
    protected const CONNECTION_NAME = 'Test name';
    protected const CONNECTION_USERNAME = 'Test username';
    protected const CONNECTION_TYPE = 'Test type';
    protected const CONNECTION_FORMAT = 'Test format';
    protected const CONNECTION_PASSWORD = 'Test password';

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
        $idPunchoutCatalogConnection = $this->tester->havePunchoutCatalogConnection([
            PunchoutCatalogConnectionTransfer::NAME => static::CONNECTION_NAME,
            PunchoutCatalogConnectionTransfer::USERNAME => static::CONNECTION_USERNAME,
            PunchoutCatalogConnectionTransfer::TYPE => static::CONNECTION_TYPE,
            PunchoutCatalogConnectionTransfer::FORMAT => static::CONNECTION_FORMAT,
            PunchoutCatalogConnectionTransfer::FK_COMPANY_BUSINESS_UNIT => $this->tester->createCompanyBusinessUnit()
                ->getIdCompanyBusinessUnit(),
        ])->getIdPunchoutCatalogConnection();

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
    public function testFindConnectionByIdRetrievesPasswordFormVaultWhenItExists(): void
    {
        // Arrange
        $idPunchoutCatalogConnection = $this->tester->havePunchoutCatalogConnection([
            PunchoutCatalogConnectionTransfer::NAME => static::CONNECTION_NAME,
            PunchoutCatalogConnectionTransfer::USERNAME => static::CONNECTION_USERNAME,
            PunchoutCatalogConnectionTransfer::TYPE => static::CONNECTION_TYPE,
            PunchoutCatalogConnectionTransfer::FORMAT => static::CONNECTION_FORMAT,
            PunchoutCatalogConnectionTransfer::PASSWORD => static::CONNECTION_PASSWORD,
            PunchoutCatalogConnectionTransfer::FK_COMPANY_BUSINESS_UNIT => $this->tester->createCompanyBusinessUnit()
                ->getIdCompanyBusinessUnit(),
        ])->getIdPunchoutCatalogConnection();

        // Act
        $punchoutCatalogConnectionTransfer = $this->tester->getFacade()
            ->findConnectionById($idPunchoutCatalogConnection);

        // Assert
        $this->assertNotNull($punchoutCatalogConnectionTransfer);
        $this->assertEquals(static::CONNECTION_PASSWORD, $punchoutCatalogConnectionTransfer->getPassword());
    }

    /**
     * @return void
     */
    public function testCreateConnectionCreatesStoresPasswordToVault(): void
    {
        // Arrange
        $companyBusinessUnitEntity = $this->tester->createCompanyBusinessUnit();
        $punchoutCatalogConnectionTransfer = (new PunchoutCatalogConnectionTransfer())
            ->setFkCompanyBusinessUnit($companyBusinessUnitEntity->getIdCompanyBusinessUnit())
            ->setName(static::CONNECTION_NAME)
            ->setUsername(static::CONNECTION_USERNAME)
            ->setPassword(static::CONNECTION_PASSWORD)
            ->setType(static::CONNECTION_TYPE)
            ->setFormat(static::CONNECTION_FORMAT);

        // Act
        $punchoutCatalogResponseTransfer = $this->tester->getFacade()
            ->createConnection($punchoutCatalogConnectionTransfer);

        // Assert
        $this->assertTrue($punchoutCatalogResponseTransfer->getIsSuccessful());

        $password = $this->tester->retrieveConnectionPasswordFromVault(
            $punchoutCatalogResponseTransfer->getPunchoutCatalogConnection()
                ->getIdPunchoutCatalogConnection()
        );

        $this->assertEquals(static::CONNECTION_PASSWORD, $password);
    }

    /**
     * @return void
     */
    public function testCreateConnectionCreatesConnectionWhenAllParametersAreSet(): void
    {
        // Arrange
        $companyBusinessUnitEntity = $this->tester->createCompanyBusinessUnit();
        $punchoutCatalogConnectionTransfer = (new PunchoutCatalogConnectionTransfer())
            ->setFkCompanyBusinessUnit($companyBusinessUnitEntity->getIdCompanyBusinessUnit())
            ->setName(static::CONNECTION_NAME)
            ->setUsername(static::CONNECTION_USERNAME)
            ->setPassword(static::CONNECTION_PASSWORD)
            ->setType(static::CONNECTION_TYPE)
            ->setFormat(static::CONNECTION_FORMAT);

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
        $punchoutCatalogConnectionTransfer = $this->tester->havePunchoutCatalogConnection([
            PunchoutCatalogConnectionTransfer::NAME => static::CONNECTION_NAME,
            PunchoutCatalogConnectionTransfer::USERNAME => static::CONNECTION_USERNAME,
            PunchoutCatalogConnectionTransfer::TYPE => static::CONNECTION_TYPE,
            PunchoutCatalogConnectionTransfer::FORMAT => static::CONNECTION_FORMAT,
            PunchoutCatalogConnectionTransfer::FK_COMPANY_BUSINESS_UNIT => $this->tester->createCompanyBusinessUnit()
                ->getIdCompanyBusinessUnit(),
        ]);

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
}
