<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\PunchoutCatalogs;

use Codeception\Actor;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\PunchoutCatalogConnectionCartTransfer;
use Generated\Shared\Transfer\PunchoutCatalogConnectionSetupTransfer;
use Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer;
use Generated\Shared\Transfer\PunchoutCatalogTransactionTransfer;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 * @method \SprykerEco\Zed\PunchoutCatalogs\Business\PunchoutCatalogsFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class PunchoutCatalogsBusinessTester extends Actor
{
    use _generated\PunchoutCatalogsBusinessTesterActions;

   /**
    * Define custom actions here
    */

    /**
     * @see \SprykerEco\Zed\PunchoutCatalogs\Business\Writer\PunchoutCatalogsWriter::PASSWORD_VAULT_DATA_TYPE
     */
    protected const VAULT_DATA_TYPE_PASSWORD = 'pwg_punchout_catalog_connection.password';

    protected const CONNECTION_SETUP_LOGIN_MODE = 'single_user';
    protected const CONNECTION_CART_SUPPLIED_ID = 'Test supplier ID';
    protected const CONNECTION_CART_MAPPING = 'Test mapping';
    protected const CONNECTION_CART_ENCODING = 'url-encoded';
    protected const CONNECTION_CART_MAX_DESCRIPTION_LENGTH = 256;
    protected const CONNECTION_NAME = 'Test name';
    protected const CONNECTION_USERNAME = 'Test username';
    protected const CONNECTION_TYPE = 'Test type';
    protected const CONNECTION_FORMAT = 'Test format';
    protected const CONNECTION_PASSWORD = 'Test password';

    /**
     * @return \Generated\Shared\Transfer\CompanyTransfer
     */
    public function createCompany(): CompanyTransfer
    {
        return $this->haveCompany(
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
        return $this->haveCompanyBusinessUnit(
            [
                CompanyBusinessUnitTransfer::NAME => 'test business unit',
                CompanyBusinessUnitTransfer::EMAIL => 'test@spryker.com',
                CompanyBusinessUnitTransfer::PHONE => '1234567890',
                CompanyBusinessUnitTransfer::FK_COMPANY => $this->createCompany()->getIdCompany(),
            ]
        );
    }

    /**
     * @param int $idPunchoutCatalogConnection
     *
     * @return string|null
     */
    public function retrieveConnectionPasswordFromVault(int $idPunchoutCatalogConnection): ?string
    {
        return $this->getLocator()
            ->vault()
            ->facade()
            ->retrieve(
                static::VAULT_DATA_TYPE_PASSWORD,
                $idPunchoutCatalogConnection
            );
    }

    /**
     * @param int $idCompanyBusinessUnit
     *
     * @return \Generated\Shared\Transfer\PunchoutCatalogConnectionSetupTransfer
     */
    public function createPunchoutCatalogsConnectionSetupTransfer(int $idCompanyBusinessUnit): PunchoutCatalogConnectionSetupTransfer
    {
        return (new PunchoutCatalogConnectionSetupTransfer())
            ->setLoginMode(static::CONNECTION_SETUP_LOGIN_MODE)
            ->setFkCompanyBusinessUnit($idCompanyBusinessUnit);
    }

    /**
     * @return \Generated\Shared\Transfer\PunchoutCatalogConnectionCartTransfer
     */
    public function createPunchoutCatalogsConnectionCartTransfer(): PunchoutCatalogConnectionCartTransfer
    {
        return (new PunchoutCatalogConnectionCartTransfer())
            ->setDefaultSupplierId(static::CONNECTION_CART_SUPPLIED_ID)
            ->setMaxDescriptionLength(static::CONNECTION_CART_MAX_DESCRIPTION_LENGTH)
            ->setMapping(static::CONNECTION_CART_MAPPING)
            ->setEncoding(static::CONNECTION_CART_ENCODING);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return \Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer
     */
    public function createPunchoutCatalogConnection(CompanyBusinessUnitTransfer $companyBusinessUnitTransfer): PunchoutCatalogConnectionTransfer
    {
        $punchoutCatalogConnectionTransfer = $this->havePunchoutCatalogConnection([
            PunchoutCatalogConnectionTransfer::NAME => static::CONNECTION_NAME,
            PunchoutCatalogConnectionTransfer::USERNAME => static::CONNECTION_USERNAME,
            PunchoutCatalogConnectionTransfer::PASSWORD => static::CONNECTION_PASSWORD,
            PunchoutCatalogConnectionTransfer::TYPE => static::CONNECTION_TYPE,
            PunchoutCatalogConnectionTransfer::FORMAT => static::CONNECTION_FORMAT,
            PunchoutCatalogConnectionTransfer::FK_COMPANY_BUSINESS_UNIT => $companyBusinessUnitTransfer->getIdCompanyBusinessUnit(),
            PunchoutCatalogConnectionTransfer::SETUP => $this->createPunchoutCatalogsConnectionSetupTransfer($companyBusinessUnitTransfer->getIdCompanyBusinessUnit()),
            PunchoutCatalogConnectionTransfer::CART => $this->createPunchoutCatalogsConnectionCartTransfer(),
        ]);

        return $punchoutCatalogConnectionTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\PunchoutCatalogTransactionTransfer
     */
    public function createPunchoutCatalogTransaction(): PunchoutCatalogTransactionTransfer
    {
        $companyBusinessUnitTransfer = $this->createCompanyBusinessUnit();
        $punchoutCatalogConnectionTransfer = $this->createPunchoutCatalogConnection($companyBusinessUnitTransfer);

        return $this->havePunchoutCatalogTransaction([
            PunchoutCatalogTransactionTransfer::CONNECTION => $punchoutCatalogConnectionTransfer,
            PunchoutCatalogTransactionTransfer::TYPE => static::CONNECTION_NAME,
            PunchoutCatalogConnectionTransfer::FK_COMPANY_BUSINESS_UNIT => $companyBusinessUnitTransfer->getIdCompanyBusinessUnit(),
        ]);
    }

    /**
     * @return \Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer
     */
    public function createPunchoutCatalogConnectionTransfer(): PunchoutCatalogConnectionTransfer
    {
        $companyBusinessUnitTransfer = $this->createCompanyBusinessUnit();

        return (new PunchoutCatalogConnectionTransfer())
            ->setFkCompanyBusinessUnit($companyBusinessUnitTransfer->getIdCompanyBusinessUnit())
            ->setName(static::CONNECTION_NAME)
            ->setUsername(static::CONNECTION_USERNAME)
            ->setPassword(static::CONNECTION_PASSWORD)
            ->setType(static::CONNECTION_TYPE)
            ->setFormat(static::CONNECTION_FORMAT)
            ->setSetup($this->createPunchoutCatalogsConnectionSetupTransfer($companyBusinessUnitTransfer->getIdCompanyBusinessUnit()))
            ->setCart($this->createPunchoutCatalogsConnectionCartTransfer());
    }
}
