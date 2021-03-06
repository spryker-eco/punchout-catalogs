<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\PunchoutCatalogs\Communication\Plugin\PunchoutCatalogs;

use SprykerEco\Zed\PunchoutCatalogs\Communication\Form\PunchoutCatalogSetupRequestBundleModeForm;
use SprykerEco\Zed\PunchoutCatalogs\Dependency\Plugin\PunchoutCatalogSetupRequestFormExtensionPluginInterface;

class BundleModePunchoutCatalogSetupRequestFormExtensionPlugin implements PunchoutCatalogSetupRequestFormExtensionPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getType(): string
    {
        return PunchoutCatalogSetupRequestBundleModeForm::class;
    }
}
