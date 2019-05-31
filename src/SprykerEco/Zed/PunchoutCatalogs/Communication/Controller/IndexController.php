<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\PunchoutCatalogs\Communication\Controller;

use Generated\Shared\Transfer\PunchoutCatalogResponseTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerEco\Zed\PunchoutCatalogs\Communication\PunchoutCatalogsCommunicationFactory getFactory()
 * @method \SprykerEco\Zed\PunchoutCatalogs\Business\PunchoutCatalogsFacadeInterface getFacade()
 * @method \SprykerEco\Zed\PunchoutCatalogs\Persistence\PunchoutCatalogsRepositoryInterface getRepository()
 */
class IndexController extends AbstractController
{
    protected const PARAM_ID_PUNCHOUT_CATALOG_CONNECTION = 'id-punchout-catalog-connection';

    protected const ROUTE_PUNCHOUT_CATALOGS_CONNECTION_LIST_PAGE = '/punchout-catalogs/';
    protected const ROUTE_PUNCHOUT_CATALOGS_CONNECTION_EDIT_PAGE = '/punchout-catalogs/index/edit';
    protected const ROUTE_PUNCHOUT_CATALOGS_CONNECTION_CREATE_PAGE = '/punchout-catalogs/index/create';

    protected const MESSAGE_CONNECTION_UPDATED = 'Connection updated';
    protected const MESSAGE_CONNECTION_NOT_FOUND = 'Connection not found';
    protected const MESSAGE_CONNECTION_ADDED = 'Connection added';

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
                $this->addSuccessMessage(static::MESSAGE_CONNECTION_ADDED);
            }

            $this->handleResponseErrors($punchoutCatalogResponseTransfer);
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
        }

        $punchoutCatalogConnectionEditForm = $this->getFactory()
            ->getPunchoutCatalogConnectionForm($punchoutCatalogConnectionTransfer);

        $punchoutCatalogConnectionEditForm->handleRequest($request);

        if ($punchoutCatalogConnectionEditForm->isSubmitted() && $punchoutCatalogConnectionEditForm->isValid()) {
             return $this->processPunchoutCatalogConnectionEditForm($punchoutCatalogConnectionEditForm);
        }

        return [
            'punchoutCatalogConnectionForm' => $punchoutCatalogConnectionEditForm->createView(),
        ];
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $punchoutCatalogConnectionEditForm
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function processPunchoutCatalogConnectionEditForm(FormInterface $punchoutCatalogConnectionEditForm): RedirectResponse
    {
        $punchoutCatalogResponseTransfer = $this->getFacade()
            ->updateConnection($punchoutCatalogConnectionEditForm->getData());

        if ($punchoutCatalogResponseTransfer->getIsSuccessful()) {
            $this->addSuccessMessage(static::MESSAGE_CONNECTION_UPDATED);
        }

        $this->handleResponseErrors($punchoutCatalogResponseTransfer);

        return $this->redirectResponse(
            $this->generateEditConnectionUrl(
                $punchoutCatalogConnectionEditForm->getData()
                    ->getIdPunchoutCatalogConnection()
            )
        );
    }

    /**
     * @param int $idPunchoutCatalogConnection
     *
     * @return string
     */
    protected function generateEditConnectionUrl(int $idPunchoutCatalogConnection): string
    {
        return Url::generate(static::ROUTE_PUNCHOUT_CATALOGS_CONNECTION_EDIT_PAGE, [
            static::PARAM_ID_PUNCHOUT_CATALOG_CONNECTION => $idPunchoutCatalogConnection,
        ])->build();
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
