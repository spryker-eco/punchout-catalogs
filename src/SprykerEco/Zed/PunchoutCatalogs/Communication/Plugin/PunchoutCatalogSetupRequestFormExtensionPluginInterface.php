<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\PunchoutCatalogs\Communication\Plugin;

use Spryker\Zed\Kernel\Communication\Form\FormTypeInterface;

interface PunchoutCatalogSetupRequestFormExtensionPluginInterface extends FormTypeInterface
{
    /**
     * Specification:
     * - Retrieves a form that is capable to extend `PunchoutCatalogSetupRequestConnectionTypeForm`.
     *
     * @api
     *
     * @return string
     */
    public function getType(): string;
}
