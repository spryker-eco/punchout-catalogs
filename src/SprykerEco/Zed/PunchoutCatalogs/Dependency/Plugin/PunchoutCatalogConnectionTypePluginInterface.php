<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\PunchoutCatalogs\Dependency\Plugin;

use Spryker\Zed\Kernel\Communication\Form\FormTypeInterface;

interface PunchoutCatalogConnectionTypePluginInterface extends FormTypeInterface
{
    /**
     * Specification:
     * - Retrieves the type of the connection.
     * - Value is a lowercase key, capable for glossary key.
     *
     * Example: setup_request
     *
     * @api
     *
     * @return string
     */
    public function getConnectionType(): string;

    /**
     * Specification:
     * - Retrieves a form that is capable of handling all attributes related to the given connection type.
     *
     * @api
     *
     * @return string
     */
    public function getType(): string;
}
