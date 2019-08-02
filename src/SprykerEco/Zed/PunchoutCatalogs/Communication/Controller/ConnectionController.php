<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\PunchoutCatalogs\Communication\Controller;

use Generated\Shared\Transfer\PunchoutCatalogResponseTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerEco\Zed\PunchoutCatalogs\Persistence\PunchoutCatalogsRepositoryInterface getRepository()
 * @method \SprykerEco\Zed\PunchoutCatalogs\Business\PunchoutCatalogsFacadeInterface getFacade()
 * @method \SprykerEco\Zed\PunchoutCatalogs\Communication\PunchoutCatalogsCommunicationFactory getFactory()
 */
class ConnectionController extends AbstractController
{
    protected const PARAM_ID_PUNCHOUT_CATALOG_CONNECTION = 'id-punchout-catalog-connection';

    /**
     * @see \SprykerEco\Zed\PunchoutCatalogs\Communication\Controller\IndexController::indexAction()
     */
    protected const ROUTE_PUNCHOUT_CATALOGS_CONNECTION_LIST_PAGE = '/punchout-catalogs';

    protected const MESSAGE_CONNECTION_NOT_FOUND = 'Connection not found';
    protected const MESSAGE_CONNECTION_ACTIVATED = 'Connection "%connection_name%" was activated.';
    protected const MESSAGE_CONNECTION_DEACTIVATED = 'Connection "%connection_name%" was deactivated.';

    protected const MESSAGE_PARAM_CONNECTION_NAME = '%connection_name%';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function activateAction(Request $request): RedirectResponse
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

        $punchoutCatalogConnectionTransfer->setIsActive(true);
        $punchoutCatalogResponseTransfer = $this->getFacade()
            ->updateConnection($punchoutCatalogConnectionTransfer);

        if ($punchoutCatalogResponseTransfer->getIsSuccessful()) {
            $this->addSuccessMessage(static::MESSAGE_CONNECTION_ACTIVATED, [
                static::MESSAGE_PARAM_CONNECTION_NAME => $punchoutCatalogConnectionTransfer->getName(),
            ]);

            return $this->redirectResponse(static::ROUTE_PUNCHOUT_CATALOGS_CONNECTION_LIST_PAGE);
        }

        $this->handleResponseErrors($punchoutCatalogResponseTransfer);

        return $this->redirectResponse(static::ROUTE_PUNCHOUT_CATALOGS_CONNECTION_LIST_PAGE);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deactivateAction(Request $request): RedirectResponse
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

        $punchoutCatalogConnectionTransfer->setIsActive(false);
        $punchoutCatalogResponseTransfer = $this->getFacade()
            ->updateConnection($punchoutCatalogConnectionTransfer);

        if ($punchoutCatalogResponseTransfer->getIsSuccessful()) {
            $this->addSuccessMessage(static::MESSAGE_CONNECTION_DEACTIVATED, [
                static::MESSAGE_PARAM_CONNECTION_NAME => $punchoutCatalogConnectionTransfer->getName(),
            ]);

            return $this->redirectResponse(static::ROUTE_PUNCHOUT_CATALOGS_CONNECTION_LIST_PAGE);
        }

        $this->handleResponseErrors($punchoutCatalogResponseTransfer);

        return $this->redirectResponse(static::ROUTE_PUNCHOUT_CATALOGS_CONNECTION_LIST_PAGE);
    }

    /**
     * @param \Generated\Shared\Transfer\PunchoutCatalogResponseTransfer $punchoutCatalogResponseTransfer
     *
     * @return void
     */
    protected function handleResponseErrors(PunchoutCatalogResponseTransfer $punchoutCatalogResponseTransfer): void
    {
        foreach ($punchoutCatalogResponseTransfer->getMessages() as $messageTransfer) {
            $this->addErrorMessage($messageTransfer->getValue());
        }
    }
}
