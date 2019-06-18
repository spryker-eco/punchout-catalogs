<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\PunchoutCatalogs\Communication\Form;

use Generated\Shared\Transfer\PunchoutCatalogConnectionCartTransfer;
use Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \SprykerEco\Zed\PunchoutCatalogs\PunchoutCatalogsConfig getConfig()
 * @method \SprykerEco\Zed\PunchoutCatalogs\Persistence\PunchoutCatalogsRepositoryInterface getRepository()
 * @method \SprykerEco\Zed\PunchoutCatalogs\Business\PunchoutCatalogsFacadeInterface getFacade()
 * @method \SprykerEco\Zed\PunchoutCatalogs\Communication\PunchoutCatalogsCommunicationFactory getFactory()
 */
class PunchoutCatalogSetupRequestBundleModeForm extends AbstractType
{
    protected const OPTION_VALUE_BUNDLE_MODE_COMPOSITE = 'composite';
    protected const OPTION_VALUE_BUNDLE_MODE_SINGLE = 'single';

    protected const OPTION_LABEL_BUNDLE_MODE_COMPOSITE = 'Composite';
    protected const OPTION_LABEL_BUNDLE_MODE_SINGLE = 'Single';

    protected const FIELD_LABEL_BUNDLE_MODE = 'Bundle Mode';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addBundleModeField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addBundleModeField(FormBuilderInterface $builder)
    {
        $builder->add(PunchoutCatalogConnectionCartTransfer::BUNDLE_MODE, ChoiceType::class, [
            'choices' => [
                static::OPTION_LABEL_BUNDLE_MODE_COMPOSITE => static::OPTION_VALUE_BUNDLE_MODE_COMPOSITE,
                static::OPTION_LABEL_BUNDLE_MODE_SINGLE => static::OPTION_VALUE_BUNDLE_MODE_SINGLE,
            ],
            'label' => static::FIELD_LABEL_BUNDLE_MODE,
            'constraints' => [
                new NotBlank(),
            ],
            'property_path' => PunchoutCatalogConnectionTransfer::CART . '.' . PunchoutCatalogConnectionCartTransfer::BUNDLE_MODE,
        ]);

        return $this;
    }
}
