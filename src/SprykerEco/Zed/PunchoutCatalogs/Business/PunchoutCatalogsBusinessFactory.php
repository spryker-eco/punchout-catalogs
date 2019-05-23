<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\PunchoutCatalogs\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use SprykerEco\Zed\PunchoutCatalogs\Business\Reader\PunchoutCatalogsReader;
use SprykerEco\Zed\PunchoutCatalogs\Business\Reader\PunchoutCatalogsReaderInterface;
use SprykerEco\Zed\PunchoutCatalogs\Business\Writer\PunchoutCatalogsWriter;
use SprykerEco\Zed\PunchoutCatalogs\Business\Writer\PunchoutCatalogsWriterInterface;

/**
 * @method \SprykerEco\Zed\PunchoutCatalogs\Persistence\PunchoutCatalogsRepositoryInterface getRepository()
 * @method \SprykerEco\Zed\PunchoutCatalogs\Persistence\PunchoutCatalogsEntityManagerInterface getEntityManager()()
 */
class PunchoutCatalogsBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \SprykerEco\Zed\PunchoutCatalogs\Business\Reader\PunchoutCatalogsReaderInterface
     */
    public function createPunchoutCatalogsReader(): PunchoutCatalogsReaderInterface
    {
        return new PunchoutCatalogsReader(
            $this->getRepository()
        );
    }

    /**
     * @return \SprykerEco\Zed\PunchoutCatalogs\Business\Writer\PunchoutCatalogsWriterInterface
     */
    public function createPunchoutCatalogsWriter(): PunchoutCatalogsWriterInterface
    {
        return new PunchoutCatalogsWriter(
            $this->getEntityManager()
        );
    }
}
