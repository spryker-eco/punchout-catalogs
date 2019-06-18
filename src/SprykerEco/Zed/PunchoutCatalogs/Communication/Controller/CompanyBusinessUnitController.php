<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\PunchoutCatalogs\Communication\Controller;

use Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \SprykerEco\Zed\PunchoutCatalogs\Communication\PunchoutCatalogsCommunicationFactory getFactory()
 * @method \SprykerEco\Zed\PunchoutCatalogs\Business\PunchoutCatalogsFacadeInterface getFacade()
 * @method \SprykerEco\Zed\PunchoutCatalogs\Persistence\PunchoutCatalogsRepositoryInterface getRepository()
 */
class CompanyBusinessUnitController extends AbstractController
{
    protected const KEY_ID = 'id';
    protected const KEY_TEXT = 'text';
    protected const KEY_RESULTS = 'results';

    protected const PARAM_ID_PARENT_COMPANY_BUSINESS_UNIT = 'id-parent-company-business-unit';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function indexAction(Request $request): JsonResponse
    {
        $idParentCompanyBusinessUnit = $this->castId(
            $request->query->getInt(static::PARAM_ID_PARENT_COMPANY_BUSINESS_UNIT)
        );
        $parentCompanyBusinessUnitTransfer = $this->getFactory()
            ->getCompanyBusinessUnitFacade()
            ->findCompanyBusinessUnitById($idParentCompanyBusinessUnit);

        if (!$parentCompanyBusinessUnitTransfer) {
            throw new NotFoundHttpException();
        }

        $companyBusinessUnitCollectionTransfer = $this->getFactory()
            ->getCompanyBusinessUnitFacade()
            ->getCompanyBusinessUnitCollection(
                (new CompanyBusinessUnitCriteriaFilterTransfer())
                    ->setIdParentCompanyBusinessUnit($idParentCompanyBusinessUnit)
            );

        return $this->jsonResponse([
            static::KEY_RESULTS => $this->prepareCompanyBusinessUnitChoices($companyBusinessUnitCollectionTransfer)
                ?: [$this->prepareCompanyBusinessUnitChoice($parentCompanyBusinessUnitTransfer)],
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer $companyBusinessUnitCollectionTransfer
     *
     * @return array
     */
    protected function prepareCompanyBusinessUnitChoices(CompanyBusinessUnitCollectionTransfer $companyBusinessUnitCollectionTransfer): array
    {
        $companyBusinessUnits = [];

        foreach ($companyBusinessUnitCollectionTransfer->getCompanyBusinessUnits() as $companyBusinessUnitTransfer) {
            $companyBusinessUnits[] = $this->prepareCompanyBusinessUnitChoice($companyBusinessUnitTransfer);
        }

        return $companyBusinessUnits;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return array
     */
    protected function prepareCompanyBusinessUnitChoice(CompanyBusinessUnitTransfer $companyBusinessUnitTransfer): array
    {
        return [
            static::KEY_ID => $companyBusinessUnitTransfer->getIdCompanyBusinessUnit(),
            static::KEY_TEXT => sprintf(
                '%s - %s',
                $companyBusinessUnitTransfer->getName(),
                $companyBusinessUnitTransfer->getCompany()
                    ->getName()
            ),
        ];
    }
}
