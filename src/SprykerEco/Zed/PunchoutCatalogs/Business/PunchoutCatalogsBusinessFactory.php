<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\PunchoutCatalogs\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use SprykerEco\Zed\PunchoutCatalogs\Business\Checker\CompanyBusinessUnitDeleteChecker;
use SprykerEco\Zed\PunchoutCatalogs\Business\Checker\CompanyBusinessUnitDeleteCheckerInterface;
use SprykerEco\Zed\PunchoutCatalogs\Business\Checker\CompanyUserDeleteChecker;
use SprykerEco\Zed\PunchoutCatalogs\Business\Checker\CompanyUserDeleteCheckerInterface;
use SprykerEco\Zed\PunchoutCatalogs\Business\Reader\PunchoutCatalogsReader;
use SprykerEco\Zed\PunchoutCatalogs\Business\Reader\PunchoutCatalogsReaderInterface;
use SprykerEco\Zed\PunchoutCatalogs\Business\Writer\PunchoutCatalogsWriter;
use SprykerEco\Zed\PunchoutCatalogs\Business\Writer\PunchoutCatalogsWriterInterface;
use SprykerEco\Zed\PunchoutCatalogs\Dependency\Facade\PunchoutCatalogsToVaultFacadeInterface;
use SprykerEco\Zed\PunchoutCatalogs\PunchoutCatalogsDependencyProvider;

/**
 * @method \SprykerEco\Zed\PunchoutCatalogs\Persistence\PunchoutCatalogsRepositoryInterface getRepository()
 * @method \SprykerEco\Zed\PunchoutCatalogs\Persistence\PunchoutCatalogsEntityManagerInterface getEntityManager()()
 * @method \SprykerEco\Zed\PunchoutCatalogs\PunchoutCatalogsConfig getConfig()
 */
class PunchoutCatalogsBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \SprykerEco\Zed\PunchoutCatalogs\Business\Reader\PunchoutCatalogsReaderInterface
     */
    public function createPunchoutCatalogsReader(): PunchoutCatalogsReaderInterface
    {
        return new PunchoutCatalogsReader(
            $this->getRepository(),
            $this->getVaultFacade()
        );
    }

    /**
     * @return \SprykerEco\Zed\PunchoutCatalogs\Business\Writer\PunchoutCatalogsWriterInterface
     */
    public function createPunchoutCatalogsWriter(): PunchoutCatalogsWriterInterface
    {
        return new PunchoutCatalogsWriter(
            $this->getEntityManager(),
            $this->getVaultFacade(),
            $this->getRepository()
        );
    }

    /**
     * @return \SprykerEco\Zed\PunchoutCatalogs\Business\CompanyBusinessUnitDeleteCheckerInterface
     */
    public function createCompanyBusinessUnitDeleteChecker(): CompanyBusinessUnitDeleteCheckerInterface
    {
        return new CompanyBusinessUnitDeleteChecker($this->createPunchoutCatalogsReader());
    }

    /**
     * @return \SprykerEco\Zed\PunchoutCatalogs\Business\Checker\CompanyUserDeleteCheckerInterface
     */
    public function createCompanyUserDeleteChecker(): CompanyUserDeleteCheckerInterface
    {
        return new CompanyUserDeleteChecker($this->createPunchoutCatalogsReader());
    }

    /**
     * @return \SprykerEco\Zed\PunchoutCatalogs\Dependency\Facade\PunchoutCatalogsToVaultFacadeInterface
     */
    public function getVaultFacade(): PunchoutCatalogsToVaultFacadeInterface
    {
        return $this->getProvidedDependency(PunchoutCatalogsDependencyProvider::FACADE_VAULT);
    }
}
