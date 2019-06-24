<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\PunchoutCatalogs\Communication\Plugin;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use SprykerEco\Zed\PunchoutCatalogs\Communication\Form\PunchoutCatalogSetupRequestConnectionTypeForm;

/**
 * @method \SprykerEco\Zed\PunchoutCatalogs\Communication\PunchoutCatalogsCommunicationFactory getFactory()
 * @method \SprykerEco\Zed\PunchoutCatalogs\PunchoutCatalogsConfig getConfig()
 * @method \SprykerEco\Zed\PunchoutCatalogs\Business\PunchoutCatalogsFacadeInterface getFacade()
 */
class SetupRequestPunchoutCatalogConnectionTypePlugin extends AbstractPlugin implements PunchoutCatalogConnectionTypePluginInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public function getConnectionType(): string
    {
        return 'single_request';
    }

    /**
     * {@inheritdoc}
     * - Returns a form that is capable of handling all attributes related to the setup_request connection type.
     *
     * @api
     *
     * @return string
     */
    public function getType(): string
    {
        return PunchoutCatalogSetupRequestConnectionTypeForm::class;
    }
}