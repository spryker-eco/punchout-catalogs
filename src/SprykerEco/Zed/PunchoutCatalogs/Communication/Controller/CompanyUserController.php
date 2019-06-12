<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\PunchoutCatalogs\Communication\Controller;

use Generated\Shared\Transfer\CompanyUserCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserCriteriaFilterTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerEco\Zed\PunchoutCatalogs\Communication\PunchoutCatalogsCommunicationFactory getFactory()
 * @method \SprykerEco\Zed\PunchoutCatalogs\Business\PunchoutCatalogsFacadeInterface getFacade()
 * @method \SprykerEco\Zed\PunchoutCatalogs\Persistence\PunchoutCatalogsRepositoryInterface getRepository()
 */
class CompanyUserController extends AbstractController
{
    protected const KEY_ID = 'id';
    protected const KEY_TEXT = 'text';
    protected const KEY_RESULTS = 'results';

    protected const PARAM_COMPANY_BUSINESS_UNIT = 'id-company-business-unit';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function indexAction(Request $request): JsonResponse
    {
        $idCompanyBusinessUnit = $this->castId($request->query->getInt(static::PARAM_COMPANY_BUSINESS_UNIT));

        $companyUserCollectionTransfer = $this->getFactory()
            ->getCompanyUserFacade()
            ->getCompanyUserCollection(
                (new CompanyUserCriteriaFilterTransfer())
                    ->setIdCompanyBusinessUnit($idCompanyBusinessUnit)
            );

        return $this->jsonResponse([
            static::KEY_RESULTS => $this->prepareChoices($companyUserCollectionTransfer),
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserCollectionTransfer $companyUserCollectionTransfer
     *
     * @return array
     */
    protected function prepareChoices(CompanyUserCollectionTransfer $companyUserCollectionTransfer): array
    {
        $companyUsers = [];

        foreach ($companyUserCollectionTransfer->getCompanyUsers() as $companyUserTransfer) {
            $customerTransfer = $companyUserTransfer->getCustomer();

            $companyUsers[] = [
                static::KEY_ID => $companyUserTransfer->getIdCompanyUser(),
                static::KEY_TEXT => sprintf(
                    '%s %s (%s)',
                    $customerTransfer->getFirstName(),
                    $customerTransfer->getLastName(),
                    $customerTransfer->getEmail()
                ),
            ];
        }

        return $companyUsers;
    }
}
