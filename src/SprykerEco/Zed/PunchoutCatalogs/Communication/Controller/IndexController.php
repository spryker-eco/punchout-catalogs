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
    protected const ROUTE_PARAM_ID_PUNCHOUT_CATALOG_CONNECTION = 'id-punchout-catalog-connection';
    protected const ROUTE_PUNCHOUT_CATALOGS_CONNECTION_LIST_PAGE = '/punchout-catalogs/';
    protected const ROUTE_PUNCHOUT_CATALOGS_CONNECTION_EDIT_PAGE = '/punchout-catalogs/index/edit';
    protected const ROUTE_PUNCHOUT_CATALOGS_CONNECTION_CREATE_PAGE = '/punchout-catalogs/index/create';

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
                $this->addSuccessMessage('Connection added');
            }

            $this->handleResponseErrors($punchoutCatalogResponseTransfer);

            return $this->redirectResponse(static::ROUTE_PUNCHOUT_CATALOGS_CONNECTION_CREATE_PAGE);
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
            $request->query->get(static::ROUTE_PARAM_ID_PUNCHOUT_CATALOG_CONNECTION)
        );

        $punchoutCatalogConnectionTransfer = $this->getFacade()
            ->findConnectionById($idPunchoutCatalogConnection);

        if (!$punchoutCatalogConnectionTransfer) {
            $this->addErrorMessage('Connection not found');

            return $this->redirectResponse(static::ROUTE_PUNCHOUT_CATALOGS_CONNECTION_LIST_PAGE);
        }

        $punchoutCatalogConnectionsForm = $this->getFactory()
            ->getPunchoutCatalogConnectionForm($punchoutCatalogConnectionTransfer);

        $punchoutCatalogConnectionsForm->handleRequest($request);

        if ($punchoutCatalogConnectionsForm->isSubmitted() && $punchoutCatalogConnectionsForm->isValid()) {
             return $this->processConnectionEditForm($punchoutCatalogConnectionsForm);
        }

        return [
            'punchoutCatalogConnectionForm' => $punchoutCatalogConnectionsForm->createView(),
        ];
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $punchoutCatalogConnectionsForm
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function processConnectionEditForm(FormInterface $punchoutCatalogConnectionsForm): RedirectResponse
    {
        $punchoutCatalogResponseTransfer = $this->getFacade()
            ->updateConnection($punchoutCatalogConnectionsForm->getData());

        if ($punchoutCatalogResponseTransfer->getIsSuccessful()) {
            $this->addSuccessMessage('Connection updated');
        }

        $this->handleResponseErrors($punchoutCatalogResponseTransfer);

        return $this->redirectResponse(
            $this->generateEditConnectionUrl(
                $punchoutCatalogConnectionsForm->getData()
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
            static::ROUTE_PARAM_ID_PUNCHOUT_CATALOG_CONNECTION => $idPunchoutCatalogConnection,
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
