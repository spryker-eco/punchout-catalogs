<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PunchoutCatalogs\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer;
use Spryker\Shared\Vault\VaultConfig as SharedVaultConfig;
use Spryker\Shared\Vault\VaultConstants;
use Spryker\Zed\Vault\Business\VaultBusinessFactory;
use Spryker\Zed\Vault\VaultConfig;
use SprykerEco\Zed\PunchoutCatalogs\Business\PunchoutCatalogsBusinessFactory;
use SprykerEco\Zed\PunchoutCatalogs\Business\PunchoutCatalogsFacadeInterface;
use SprykerEco\Zed\PunchoutCatalogs\Dependency\Facade\PunchoutCatalogsToVaultFacadeBridge;
use SprykerEco\Zed\PunchoutCatalogs\Dependency\Facade\PunchoutCatalogsToVaultFacadeInterface;
use SprykerEco\Zed\PunchoutCatalogs\Persistence\PunchoutCatalogsEntityManager;
use SprykerEco\Zed\PunchoutCatalogs\Persistence\PunchoutCatalogsRepository;

/**
 * Auto-generated group annotations
 *
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
     * @see \SprykerEco\Zed\PunchoutCatalogs\Business\Writer\PunchoutCatalogsWriter::PASSWORD_VAULT_DATA_TYPE
     */
    protected const VAULT_DATA_TYPE_PASSWORD = 'pwg_punchout_catalog_connection.password';

    /**
     * @see \Spryker\Shared\Vault\VaultConstants::ENCRYPTION_KEY
     */
    protected const VAULT_ENCRYPTION_KEY = 'test-encryption-key';

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
        $punchoutConnectionTransfer = $this->getPunchoutCatalogsFacadeWithMockedVaultFacade()
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
        $idPunchoutCatalogConnection = $this->createPunchoutCatalogConnection(
            $this->tester->createCompanyBusinessUnit()
        )->getIdPunchoutCatalogConnection();

        // Act
        $punchoutCatalogConnectionTransfer = $this->getPunchoutCatalogsFacadeWithMockedVaultFacade()
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
        $punchoutCatalogConnectionTransfer = $this->createPunchoutCatalogConnection(
            $this->tester->createCompanyBusinessUnit()
        );

        // Act
        $persistentPunchoutCatalogConnectionTransfer = $this->getPunchoutCatalogsFacadeWithMockedVaultFacade()
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
        $punchoutCatalogResponseTransfer = $this->getPunchoutCatalogsFacadeWithMockedVaultFacade()
            ->createConnection($punchoutCatalogConnectionTransfer);

        // Assert
        $this->assertTrue($punchoutCatalogResponseTransfer->getIsSuccessful());

        $password = $this->retrieveConnectionPasswordFromVault(
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
        $punchoutCatalogResponseTransfer = $this->getPunchoutCatalogsFacadeWithMockedVaultFacade()
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
        $punchoutCatalogConnectionTransfer = $this->createPunchoutCatalogConnection(
            $this->tester->createCompanyBusinessUnit()
        );

        $punchoutCatalogConnectionTransfer = $this->getPunchoutCatalogsFacadeWithMockedVaultFacade()
            ->findConnectionById($punchoutCatalogConnectionTransfer->getIdPunchoutCatalogConnection());

        $punchoutCatalogConnectionTransfer->setUsername('Updated username');

        // Act
        $punchoutCatalogResponseTransfer = $this->getPunchoutCatalogsFacadeWithMockedVaultFacade()
            ->updateConnection($punchoutCatalogConnectionTransfer);

        // Assert
        $this->assertTrue($punchoutCatalogResponseTransfer->getIsSuccessful());

        $updatedPunchoutCatalogConnectionTransfer = $this->getPunchoutCatalogsFacadeWithMockedVaultFacade()
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
        $punchoutCatalogResponseTransfer = $this->getPunchoutCatalogsFacadeWithMockedVaultFacade()
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
        $punchoutTransactionTransfer = $this->getPunchoutCatalogsFacadeWithMockedVaultFacade()
            ->findTransactionById($idPunchoutCatalogTransaction);

        // Assert
        $this->assertNull($punchoutTransactionTransfer);
    }

    /**
     * @return void
     */
    public function testFindTransactionByIdRetrievesTransactionWhenItExists(): void
    {
        // Arrange
        $punchoutCatalogTransactionTransfer = $this->tester->createPunchoutCatalogTransaction(
            $this->createPunchoutCatalogConnection(
                $this->tester->createCompanyBusinessUnit()
            )
        );

        // Act
        $punchoutCatalogTransactionTransfer = $this->getPunchoutCatalogsFacadeWithMockedVaultFacade()
            ->findTransactionById($punchoutCatalogTransactionTransfer->getIdPunchoutCatalogTransaction());

        // Assert
        $this->assertNotNull($punchoutCatalogTransactionTransfer);
        $this->assertEquals($punchoutCatalogTransactionTransfer->getIdPunchoutCatalogTransaction(), $punchoutCatalogTransactionTransfer->getIdPunchoutCatalogTransaction());
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return \Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer
     */
    protected function createPunchoutCatalogConnection(CompanyBusinessUnitTransfer $companyBusinessUnitTransfer): PunchoutCatalogConnectionTransfer
    {
        return $this->getPunchoutCatalogsFacadeWithMockedVaultFacade()
            ->createConnection($this->tester->createPunchoutCatalogConnectionTransfer())
            ->getPunchoutCatalogConnection();
    }

    /**
     * @return \SprykerEco\Zed\PunchoutCatalogs\Business\PunchoutCatalogsFacadeInterface
     */
    protected function getPunchoutCatalogsFacadeWithMockedVaultFacade(): PunchoutCatalogsFacadeInterface
    {
        $punchoutCatalogBusinessFactoryMock = $this->getMockBuilder(PunchoutCatalogsBusinessFactory::class)
            ->setMethods(['getVaultFacade', 'getRepository', 'getEntityManager'])
            ->getMock();

        $punchoutCatalogBusinessFactoryMock->method('getVaultFacade')
            ->willReturn($this->getVaultFacadeWithSharedConfig());
        $punchoutCatalogBusinessFactoryMock->method('getRepository')
            ->willReturn(new PunchoutCatalogsRepository());
        $punchoutCatalogBusinessFactoryMock->method('getEntityManager')
            ->willReturn(new PunchoutCatalogsEntityManager());

        return $this->tester->getFacade()
            ->setFactory($punchoutCatalogBusinessFactoryMock);
    }

    /**
     * @return \SprykerEco\Zed\PunchoutCatalogs\Dependency\Facade\PunchoutCatalogsToVaultFacadeInterface
     */
    protected function getVaultFacadeWithSharedConfig(): PunchoutCatalogsToVaultFacadeInterface
    {
        $vaultSharedConfigMock = $this->getMockBuilder(SharedVaultConfig::class)
            ->setMethods(['get'])
            ->getMock();

        $vaultSharedConfigMock->method('get')
            ->with(VaultConstants::ENCRYPTION_KEY, false)
            ->willReturn('test_encryption_key');

        $vaultConfig = (new VaultConfig())
            ->setSharedConfig($vaultSharedConfigMock);

        $vaultBusinessFactory = (new VaultBusinessFactory())
            ->setConfig($vaultConfig);

        $vaultFacade = $this->tester->getLocator()
            ->vault()
            ->facade()
            ->setFactory($vaultBusinessFactory);

        return new PunchoutCatalogsToVaultFacadeBridge($vaultFacade);
    }

    /**
     * @param int $idPunchoutCatalogConnection
     *
     * @return string|null
     */
    protected function retrieveConnectionPasswordFromVault(int $idPunchoutCatalogConnection): ?string
    {
        return $this->getVaultFacadeWithSharedConfig()
            ->retrieve(
                static::VAULT_DATA_TYPE_PASSWORD,
                $idPunchoutCatalogConnection
            );
    }
}
