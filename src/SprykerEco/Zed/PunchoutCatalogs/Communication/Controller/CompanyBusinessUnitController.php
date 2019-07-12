<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\PunchoutCatalogs\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerEco\Zed\PunchoutCatalogs\Communication\PunchoutCatalogsCommunicationFactory getFactory()
 * @method \SprykerEco\Zed\PunchoutCatalogs\Business\PunchoutCatalogsFacadeInterface getFacade()
 * @method \SprykerEco\Zed\PunchoutCatalogs\Persistence\PunchoutCatalogsRepositoryInterface getRepository()
 */
class CompanyBusinessUnitController extends AbstractController
{
    protected const PARAM_ID_PARENT_COMPANY_BUSINESS_UNIT = 'id-parent-company-business-unit';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function indexAction(Request $request): JsonResponse
    {
        $parentCompanyBusinessUnitId = $this->castId(
            $request->query->getInt(static::PARAM_ID_PARENT_COMPANY_BUSINESS_UNIT)
        );

        $companyBusinessUnitChoices = $this->getFactory()
            ->createPunchoutCatalogSetupRequestConnectionTypeFormDataProvider()
            ->getFormattedCompanyBusinessUnitChoices($parentCompanyBusinessUnitId);

        return $this->jsonResponse($companyBusinessUnitChoices);
    }
}
