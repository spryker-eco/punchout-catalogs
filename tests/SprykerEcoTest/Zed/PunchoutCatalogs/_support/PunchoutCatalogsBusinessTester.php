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
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\PunchoutCatalogConnectionCartTransfer;
use Generated\Shared\Transfer\PunchoutCatalogConnectionSetupTransfer;
use Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer;
use Generated\Shared\Transfer\PunchoutCatalogTransactionTransfer;

/**
 * Inherited Methods
 *
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
    protected const CONNECTION_CART_TOTALS_MODE = 'Test totals mode';

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
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function createCompanyUser(): CompanyUserTransfer
    {
        $customerTransfer = $this->createCustomer();

        return $this->haveCompanyUser(
            [
                CompanyUserTransfer::ID_COMPANY_USER => '78945689',
                CompanyUserTransfer::CUSTOMER => $customerTransfer,
                CompanyUserTransfer::FK_CUSTOMER => $customerTransfer->getIdCustomer(),
                CompanyUserTransfer::FK_COMPANY => $this->haveCompany()->getIdCompany()
            ]
        );
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function createCustomer(): CustomerTransfer
    {
        return $this->haveCustomer();
    }

    /**
     * @param int $idCompanyBusinessUnit
     * @param int $idCompanyUser
     *
     * @return \Generated\Shared\Transfer\PunchoutCatalogConnectionSetupTransfer
     */
    public function createPunchoutCatalogsConnectionSetupTransfer(
        int $idCompanyBusinessUnit,
        int $idCompanyUser
    ): PunchoutCatalogConnectionSetupTransfer
    {
        return (new PunchoutCatalogConnectionSetupTransfer())
            ->setLoginMode(static::CONNECTION_SETUP_LOGIN_MODE)
            ->setFkCompanyBusinessUnit($idCompanyBusinessUnit)
            ->setFkCompanyUser($idCompanyUser);
    }

    /**
     * @return \Generated\Shared\Transfer\PunchoutCatalogConnectionCartTransfer
     */
    public function createPunchoutCatalogsConnectionCartTransfer(): PunchoutCatalogConnectionCartTransfer
    {
        return (new PunchoutCatalogConnectionCartTransfer())
            ->setDefaultSupplierId(static::CONNECTION_CART_SUPPLIED_ID)
            ->setMaxDescriptionLength(static::CONNECTION_CART_MAX_DESCRIPTION_LENGTH)
            ->setTotalsMode(static::CONNECTION_CART_TOTALS_MODE)
            ->setMapping(static::CONNECTION_CART_MAPPING)
            ->setEncoding(static::CONNECTION_CART_ENCODING);
    }

    /**
     * @param \Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer $punchoutCatalogConnectionTransfer
     *
     * @return \Generated\Shared\Transfer\PunchoutCatalogTransactionTransfer
     */
    public function createPunchoutCatalogTransaction(PunchoutCatalogConnectionTransfer $punchoutCatalogConnectionTransfer): PunchoutCatalogTransactionTransfer
    {
        $companyBusinessUnitTransfer = $this->createCompanyBusinessUnit();

        return $this->havePunchoutCatalogTransaction([
            PunchoutCatalogTransactionTransfer::CONNECTION => $punchoutCatalogConnectionTransfer,
            PunchoutCatalogTransactionTransfer::TYPE => static::CONNECTION_NAME,
            PunchoutCatalogConnectionTransfer::FK_COMPANY_BUSINESS_UNIT => $companyBusinessUnitTransfer->getIdCompanyBusinessUnit(),
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer|null $companyBusinessUnitTransfer
     * @param \Generated\Shared\Transfer\CompanyUserTransfer|null $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer
     */
    public function createPunchoutCatalogConnectionTransfer(
        ?CompanyBusinessUnitTransfer $companyBusinessUnitTransfer = null,
        ?CompanyUserTransfer $companyUserTransfer = null
    ): PunchoutCatalogConnectionTransfer {
        $companyBusinessUnitTransfer = $companyBusinessUnitTransfer ?? $this->createCompanyBusinessUnit();
        $companyUserTransfer = $companyUserTransfer ?? $this->createCompanyUser();

        $punchoutCatalogsConnectionSetupTransfer = $this
            ->createPunchoutCatalogsConnectionSetupTransfer(
                $companyBusinessUnitTransfer->getIdCompanyBusinessUnit(),
                $companyUserTransfer->getIdCompanyUser()
            );

        return (new PunchoutCatalogConnectionTransfer())
            ->setFkCompanyBusinessUnit($companyBusinessUnitTransfer->getIdCompanyBusinessUnit())
            ->setName(static::CONNECTION_NAME)
            ->setUsername(static::CONNECTION_USERNAME)
            ->setPassword(static::CONNECTION_PASSWORD)
            ->setType(static::CONNECTION_TYPE)
            ->setFormat(static::CONNECTION_FORMAT)
            ->setSetup($punchoutCatalogsConnectionSetupTransfer)
            ->setCart($this->createPunchoutCatalogsConnectionCartTransfer());
    }
}
