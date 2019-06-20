<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\PunchoutCatalogs\Communication\Plugin;

use Spryker\Zed\Kernel\Communication\Form\FormTypeInterface;

interface PunchoutCatalogConnectionFormatPluginInterface extends FormTypeInterface
{
    /**
     * Specification:
     * - Retrieves the format of the connection.
     * - Value is a lowercase key, capable for glossary key.
     *
     * Example: cxml
     *
     * @api
     *
     * @return string
     */
    public function getConnectionFormat(): string;

    /**
     * Specification:
     * - Retrieves a form that is capable of handling all attributes related to the given connection format.
     *
     * @api
     *
     * @return string
     */
    public function getType(): string;
}
