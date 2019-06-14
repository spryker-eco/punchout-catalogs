<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\PunchoutCatalogs\Communication\Controller;

use Generated\Shared\Transfer\PunchoutCatalogResponseTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerEco\Zed\PunchoutCatalogs\Communication\PunchoutCatalogsCommunicationFactory getFactory()
 * @method \SprykerEco\Zed\PunchoutCatalogs\Business\PunchoutCatalogsFacadeInterface getFacade()
 * @method \SprykerEco\Zed\PunchoutCatalogs\Persistence\PunchoutCatalogsRepositoryInterface getRepository()
 */
class IndexController extends AbstractController
{
    protected const PARAM_ID_PUNCHOUT_CATALOG_CONNECTION = 'id-punchout-catalog-connection';

    /**
     * @uses SprykerEco\Zed\PunchoutCatalogs\Communication\Controller::indexAction()
     */
    protected const ROUTE_PUNCHOUT_CATALOGS_CONNECTION_LIST_PAGE = '/punchout-catalogs/';

    protected const MESSAGE_CONNECTION_UPDATED = 'Connection "%connection_name%" was updated successfully.';
    protected const MESSAGE_CONNECTION_NOT_FOUND = 'Connection not found';
    protected const MESSAGE_CONNECTION_ADDED = 'Connection "%connection_name%" was created successfully.';

    /**
     * @return array
     */
    public function indexAction(): array
    {
        $table = $this->getFactory()
            ->createPunchoutCatalogsConnectionsTable();

        return $this->viewResponse([
            'punchoutCatalogsConnectionsTable' => $table->render(),
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction(): JsonResponse
    {
        $table = $this->getFactory()
            ->createPunchoutCatalogsConnectionsTable();

        return $this->jsonResponse($table->fetchData());
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request)
    {
        $punchoutCatalogConnectionsForm = $this->getFactory()
            ->getPunchoutCatalogConnectionForm();

        $punchoutCatalogConnectionsForm->handleRequest($request);

        if ($punchoutCatalogConnectionsForm->isSubmitted() && $punchoutCatalogConnectionsForm->isValid()) {
            $punchoutCatalogResponseTransfer = $this->getFacade()
                ->createConnection($punchoutCatalogConnectionsForm->getData());

            if ($punchoutCatalogResponseTransfer->getIsSuccessful()) {
                $this->addSuccessMessage(static::MESSAGE_CONNECTION_ADDED, [
                    '%connection_name%' => $punchoutCatalogResponseTransfer->getPunchoutCatalogConnection()
                        ->getName(),
                ]);
            }

            $this->handleResponseErrors($punchoutCatalogResponseTransfer);

            return $this->redirectResponse(static::ROUTE_PUNCHOUT_CATALOGS_CONNECTION_LIST_PAGE);
        }

        return [
            'punchoutCatalogConnectionForm' => $punchoutCatalogConnectionsForm->createView(),
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function editAction(Request $request)
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

        $punchoutCatalogConnectionEditForm = $this->getFactory()
            ->getPunchoutCatalogConnectionForm($punchoutCatalogConnectionTransfer);

        $punchoutCatalogConnectionEditForm->handleRequest($request);

        if ($punchoutCatalogConnectionEditForm->isSubmitted() && $punchoutCatalogConnectionEditForm->isValid()) {
            $this->processPunchoutCatalogConnectionEditForm($punchoutCatalogConnectionEditForm);

            return $this->redirectResponse(static::ROUTE_PUNCHOUT_CATALOGS_CONNECTION_LIST_PAGE);
        }

        return [
            'punchoutCatalogConnectionForm' => $punchoutCatalogConnectionEditForm->createView(),
            'idPunchoutCatalogConnection' => $idPunchoutCatalogConnection,
        ];
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $punchoutCatalogConnectionEditForm
     *
     * @return void
     */
    protected function processPunchoutCatalogConnectionEditForm(FormInterface $punchoutCatalogConnectionEditForm): void
    {
        $punchoutCatalogResponseTransfer = $this->getFacade()
            ->updateConnection($punchoutCatalogConnectionEditForm->getData());

        if ($punchoutCatalogResponseTransfer->getIsSuccessful()) {
            $this->addSuccessMessage(static::MESSAGE_CONNECTION_UPDATED, [
                '%connection_name%' => $punchoutCatalogResponseTransfer->getPunchoutCatalogConnection()
                    ->getName(),
            ]);
        }

        $this->handleResponseErrors($punchoutCatalogResponseTransfer);
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
