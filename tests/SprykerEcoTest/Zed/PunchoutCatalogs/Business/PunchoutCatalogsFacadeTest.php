<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PunchoutCatalogs\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer;
use Orm\Zed\PunchoutCatalog\Persistence\PgwPunchoutCatalogConnection;
use SprykerEco\Zed\PunchoutCatalogs\Business\PunchoutCatalogsFacadeInterface;

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
        $punchoutConnectionTransfer = $this->getFacade()
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
        $idPunchoutCatalogConnection = $this->createPunchoutCatalogsConnection();

        // Act
        $punchoutCatalogConnectionTransfer = $this->getFacade()
            ->findConnectionById($idPunchoutCatalogConnection);

        // Assert
        $this->assertNotNull($punchoutCatalogConnectionTransfer);
        $this->assertEquals($idPunchoutCatalogConnection, $punchoutCatalogConnectionTransfer->getIdPunchoutCatalogConnection());
    }

    /**
     * @return void
     */
    public function testCreateConnectionCreatesConnectionWhenAllParametersAreSet(): void
    {
        // Arrange
        $companyBusinessUnitEntity = $this->createCompanyBusinessUnit();
        $punchoutCatalogConnectionTransfer = (new PunchoutCatalogConnectionTransfer())
            ->setFkCompanyBusinessUnit($companyBusinessUnitEntity->getIdCompanyBusinessUnit())
            ->setName(static::CONNECTION_NAME)
            ->setUsername(static::CONNECTION_USERNAME)
            ->setType(static::CONNECTION_TYPE)
            ->setFormat(static::CONNECTION_FORMAT);

        // Act
        $punchoutCatalogResponseTransfer = $this->getFacade()->createConnection($punchoutCatalogConnectionTransfer);

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
        $punchoutCatalogConnectionTransfer = $this->getFacade()
            ->findConnectionById(
                $this->createPunchoutCatalogsConnection()
            );

        $punchoutCatalogConnectionTransfer->setUsername('Updated username');

        // Act
        $punchoutCatalogResponseTransfer = $this->getFacade()
            ->updateConnection($punchoutCatalogConnectionTransfer);

        // Assert
        $this->assertTrue($punchoutCatalogResponseTransfer->getIsSuccessful());

        $updatedPunchoutCatalogConnectionTransfer = $this->getFacade()
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
        $punchoutCatalogResponseTransfer = $this->getFacade()
            ->updateConnection($punchoutCatalogConnectionTransfer);

        // Assert
        $this->assertFalse($punchoutCatalogResponseTransfer->getIsSuccessful());
    }

    /**
     * @return int
     */
    protected function createPunchoutCatalogsConnection(): int
    {
        $companyTransferBusinessUnit = $this->createCompanyBusinessUnit();

        $punchoutCatalogConnectionEntity = (new PgwPunchoutCatalogConnection())
            ->setName(static::CONNECTION_NAME)
            ->setUsername(static::CONNECTION_USERNAME)
            ->setType(static::CONNECTION_TYPE)
            ->setFormat(static::CONNECTION_FORMAT)
            ->setFkCompanyBusinessUnit($companyTransferBusinessUnit->getIdCompanyBusinessUnit());

        $punchoutCatalogConnectionEntity->save();

        return $punchoutCatalogConnectionEntity->getIdPunchoutCatalogConnection();
    }

    /**
     * @return \Generated\Shared\Transfer\CompanyTransfer
     */
    public function createCompany(): CompanyTransfer
    {
        return $this->tester->haveCompany(
            [
                CompanyTransfer::NAME => 'Test company',
                CompanyTransfer::STATUS => 'approved',
                CompanyTransfer::IS_ACTIVE => true,
                CompanyTransfer::INITIAL_USER_TRANSFER => new CompanyUserTransfer(),
            ]
        );
    }

    /**
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitTransfer
     */
    public function createCompanyBusinessUnit(): CompanyBusinessUnitTransfer
    {
        return $this->tester->haveCompanyBusinessUnit(
            [
                CompanyBusinessUnitTransfer::NAME => 'test business unit',
                CompanyBusinessUnitTransfer::EMAIL => 'test@spryker.com',
                CompanyBusinessUnitTransfer::PHONE => '1234567890',
                CompanyBusinessUnitTransfer::FK_COMPANY => $this->createCompany()->getIdCompany(),
            ]
        );
    }

    /**
     * @return \SprykerEco\Zed\PunchoutCatalogs\Business\PunchoutCatalogsFacadeInterface
     */
    protected function getFacade(): PunchoutCatalogsFacadeInterface
    {
        return $this->tester->getFacade();
    }
}
