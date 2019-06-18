<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\PunchoutCatalogs\Communication\Form;

use Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use SprykerEco\Zed\PunchoutCatalogs\Communication\Form\ConnectionSetupSubForms\PunchoutCatalogConnectionCartForm;
use SprykerEco\Zed\PunchoutCatalogs\Communication\Form\ConnectionSetupSubForms\PunchoutCatalogConnectionSetupForm;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \SprykerEco\Zed\PunchoutCatalogs\PunchoutCatalogsConfig getConfig()
 * @method \SprykerEco\Zed\PunchoutCatalogs\Persistence\PunchoutCatalogsRepositoryInterface getRepository()
 * @method \SprykerEco\Zed\PunchoutCatalogs\Business\PunchoutCatalogsFacadeInterface getFacade()
 * @method \SprykerEco\Zed\PunchoutCatalogs\Communication\PunchoutCatalogsCommunicationFactory getFactory()
 */
class PunchoutCatalogSetupRequestConnectionTypeForm extends AbstractType
{
    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addCartSubForm($builder)
            ->executeExtendFormPlugins($builder)
            ->addSetupSubForm($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addCartSubForm(FormBuilderInterface $builder)
    {
        $builder->add(PunchoutCatalogConnectionTransfer::CART, PunchoutCatalogConnectionCartForm::class, [
            'label' => false,
            'inherit_data' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addSetupSubForm(FormBuilderInterface $builder)
    {
        $builder->add(PunchoutCatalogConnectionTransfer::SETUP, PunchoutCatalogConnectionSetupForm::class, [
            'label' => false,
            'inherit_data' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function executeExtendFormPlugins(FormBuilderInterface $builder)
    {
        $setupRequestFormExtensionPlugins = $this->getFactory()
            ->getSetupRequestFormExtensionPlugins();

        foreach ($setupRequestFormExtensionPlugins as $setupRequestFormExtensionPlugin) {
            $builder->add(md5($setupRequestFormExtensionPlugin->getType()), $setupRequestFormExtensionPlugin->getType(), [
                'inherit_data' => true,
                'label' => false,
            ]);
        }

        return $this;
    }
}
