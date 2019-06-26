<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\PunchoutCatalogs\Communication\Controller;

use Generated\Shared\Transfer\PunchoutCatalogEntryPointFilterTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerEco\Zed\PunchoutCatalogs\Persistence\PunchoutCatalogsRepositoryInterface getRepository()
 * @method \SprykerEco\Zed\PunchoutCatalogs\Business\PunchoutCatalogsFacadeInterface getFacade()
 * @method \SprykerEco\Zed\PunchoutCatalogs\Communication\PunchoutCatalogsCommunicationFactory getFactory()
 */
class EntryPointsController extends AbstractController
{
    /**
     * @see \SprykerEco\Zed\PunchoutCatalogs\Communication\Controller\IndexController::indexAction()
     */
    protected const ROUTE_PUNCHOUT_CATALOGS_CONNECTION_LIST_PAGE = '/punchout-catalogs/';

    protected const PARAM_ID_PUNCHOUT_CATALOG_CONNECTION = 'id-punchout-catalog-connection';

    protected const MESSAGE_CONNECTION_NOT_FOUND = 'Connection not found';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function viewAction(Request $request)
    {
        $idPunchoutCatalogConnection = $this->castId(
            $request->query->get(static::PARAM_ID_PUNCHOUT_CATALOG_CONNECTION)
        );

        $punchoutCatalogConnectionTransfer = $this->getFacade()
            ->findConnectionById($idPunchoutCatalogConnection);

        if (!$punchoutCatalogConnectionTransfer) {
            $this->addErrorMessage(static::MESSAGE_CONNECTION_NOT_FOUND);

            return $this->redirectResponse(static::ROUTE_PUNCHOUT_CATALOGS_CONNECTION_LIST_PAGE);
        }

        $punchoutCatalogEntryPointFilterTransfer = (new PunchoutCatalogEntryPointFilterTransfer())
            ->setIdCompanyBusinessUnit($punchoutCatalogConnectionTransfer->getFkCompanyBusinessUnit());

        $punchoutCatalogEntryPointTransfer = $this->getFactory()
            ->getPunchoutCatalogFacade()
            ->getRequestEntryPointsByBusinessUnit($punchoutCatalogEntryPointFilterTransfer);

        return [
            'punchoutCatalogEntryPoints' => $punchoutCatalogEntryPointTransfer,
            'punchoutCatalogConnection' => $punchoutCatalogConnectionTransfer,
        ];
    }
}
