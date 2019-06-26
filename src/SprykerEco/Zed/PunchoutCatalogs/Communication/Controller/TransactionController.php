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
 * @method \SprykerEco\Zed\PunchoutCatalogs\Persistence\PunchoutCatalogsRepositoryInterface getRepository()
 * @method \SprykerEco\Zed\PunchoutCatalogs\Business\PunchoutCatalogsFacadeInterface getFacade()
 * @method \SprykerEco\Zed\PunchoutCatalogs\Communication\PunchoutCatalogsCommunicationFactory getFactory()
 */
class TransactionController extends AbstractController
{
    protected const PARAM_ID_PUNCHOUT_CATALOG_TRANSACTION = 'id-punchout-catalog-transaction';
    protected const MESSAGE_TRANSACTION_NOT_FOUND = 'Transaction not found';

    /**
     * @uses \SprykerEco\Zed\PunchoutCatalogs\Communication\Controller\TransactionController::indexAction()
     */
    protected const ROUTE_PUNCHOUT_CATALOGS_CONNECTION_LIST_PAGE = '/punchout-catalogs/transaction/index';

    /**
     * @return array
     */
    public function indexAction(): array
    {
        $table = $this->getFactory()
            ->createPunchoutCatalogsTransactionLogTable();

        return $this->viewResponse([
            'table' => $table->render(),
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction(): JsonResponse
    {
        $table = $this->getFactory()
            ->createPunchoutCatalogsTransactionLogTable();

        return $this->jsonResponse($table->fetchData());
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function viewAction(Request $request)
    {
        $idPunchoutCatalogTransaction = $this->castId(
            $request->query->get(static::PARAM_ID_PUNCHOUT_CATALOG_TRANSACTION)
        );

        $punchoutCatalogTransactionTransfer = $this->getFacade()
            ->findTransactionById($idPunchoutCatalogTransaction);

        if (!$punchoutCatalogTransactionTransfer) {
            $this->addErrorMessage(static::MESSAGE_TRANSACTION_NOT_FOUND);

            return $this->redirectResponse(static::ROUTE_PUNCHOUT_CATALOGS_CONNECTION_LIST_PAGE);
        }

        return [
            'transaction' => $punchoutCatalogTransactionTransfer,
        ];
    }
}
