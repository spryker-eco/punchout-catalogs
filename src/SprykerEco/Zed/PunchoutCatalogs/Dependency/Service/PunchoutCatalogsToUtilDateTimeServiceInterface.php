<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\PunchoutCatalogs\Dependency\Service;

interface PunchoutCatalogsToUtilDateTimeServiceInterface
{
    /**
     * @param \DateTime|string $date
     *
     * @return string
     */
    public function formatDateTime($date);
}
