<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\PunchoutCatalogs\Communication\Form\ConnectionSetupSubForms;

use Generated\Shared\Transfer\PunchoutCatalogConnectionCartTransfer;
use Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @method \SprykerEco\Zed\PunchoutCatalogs\PunchoutCatalogsConfig getConfig()
 * @method \SprykerEco\Zed\PunchoutCatalogs\Persistence\PunchoutCatalogsRepositoryInterface getRepository()
 * @method \SprykerEco\Zed\PunchoutCatalogs\Business\PunchoutCatalogsFacadeInterface getFacade()
 * @method \SprykerEco\Zed\PunchoutCatalogs\Communication\PunchoutCatalogsCommunicationFactory getFactory()
 */
class PunchoutCatalogConnectionCartForm extends AbstractType
{
    protected const MIN_DESCRIPTION_LENGTH = 16;
    protected const MAX_DESCRIPTION_LENGTH = 99999;

    protected const MESSAGE_PARAM_TOTALS_MODE = '%totals_mode%';
    protected const MESSAGE_PARAM_CONNECTION_FORMAT = '%connection_format%';

    protected const MESSAGE_INCOMPATIBLE_TOTALS_MODE_AND_CONNECTION_FORMAT = 'You can’t use "%s" for "%s" connection - please choose another one.';

    protected const TEMPLATE_PATH_MAX_DESCRIPTION_LENGTH_FIELD = '@PunchoutCatalogs/Form/Connection/Setup/max_description_length.twig';

    protected const ALLOWED_CONNECTION_FORMATS_FOR_TOTALS_MODES = [
        'disabled' => ['cxml', 'oci'],
        'line' => ['cxml', 'oci'],
        'header' => ['cxml'],
    ];

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addMaxDescriptionLengthField($builder)
            ->addEncodingField($builder)
            ->addTotalsModeField($builder)
            ->addMappingField($builder)
            ->addMaxDescriptionLengthField($builder)
            ->addDefaultSupplierIdField($builder);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PunchoutCatalogConnectionCartTransfer::class,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormView $view
     * @param \Symfony\Component\Form\FormInterface $form
     * @param array $options
     *
     * @return void
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['min_description_length'] = static::MIN_DESCRIPTION_LENGTH;
        $view->vars['max_description_length'] = static::MAX_DESCRIPTION_LENGTH;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addMaxDescriptionLengthField(FormBuilderInterface $builder)
    {
        $builder->add(PunchoutCatalogConnectionCartTransfer::MAX_DESCRIPTION_LENGTH, IntegerType::class, [
            'label' => 'Set Description length on "Transfer to Requisition"',
            'required' => false,
            'constraints' => [
                new Range([
                    'min' => static::MIN_DESCRIPTION_LENGTH,
                    'max' => static::MAX_DESCRIPTION_LENGTH,
                ]),
            ],
            'attr' => [
                'template_path' => static::TEMPLATE_PATH_MAX_DESCRIPTION_LENGTH_FIELD,
            ],
        ]);

        $builder->get(PunchoutCatalogConnectionCartTransfer::MAX_DESCRIPTION_LENGTH)
            ->addViewTransformer($this->createMaxDescriptionLengthViewTransformer());

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addTotalsModeField(FormBuilderInterface $builder)
    {
        $builder->add(PunchoutCatalogConnectionCartTransfer::TOTALS_MODE, ChoiceType::class, [
            'label' => 'Totals Mode',
            'choices' => [
                'Disabled' => 'disabled',
                'Line' => 'line',
                'Header (does not work with OCI)' => 'header',
            ],
            'constraints' => [
                $this->createTotalsModeValidationConstraint(),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addEncodingField(FormBuilderInterface $builder)
    {
        $choices = $this->getFactory()
            ->createPunchoutCatalogSetupRequestConnectionTypeFormDataProvider()
            ->getCartEncodingChoices();

        $builder->add(PunchoutCatalogConnectionCartTransfer::ENCODING, ChoiceType::class, [
            'label' => 'Cart Encoding',
            'choices' => $choices,
            'constraints' => [
                new NotBlank(),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addMappingField(FormBuilderInterface $builder)
    {
        $builder->add(PunchoutCatalogConnectionCartTransfer::MAPPING, TextareaType::class, [
            'label' => 'Cart Mapping',
            'required' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addDefaultSupplierIdField(FormBuilderInterface $builder)
    {
        $builder->add(PunchoutCatalogConnectionCartTransfer::DEFAULT_SUPPLIER_ID, TextType::class, [
            'label' => 'Default Supplier ID',
            'constraints' => [
                new NotBlank(),
                new Length(['max' => 64]),
            ],
        ]);

        return $this;
    }

    /**
     * @return \Symfony\Component\Form\CallbackTransformer
     */
    protected function createMaxDescriptionLengthViewTransformer(): CallbackTransformer
    {
        return new CallbackTransformer(
            function (string $maxDescriptionLength) {
                return $maxDescriptionLength;
            },
            function (string $maxDescriptionLength) {
                if (!$maxDescriptionLength) {
                    return (string)static::MAX_DESCRIPTION_LENGTH;
                }

                return $maxDescriptionLength;
            }
        );
    }

    /**
     * @return \Symfony\Component\Validator\Constraint
     */
    protected function createTotalsModeValidationConstraint(): Constraint
    {
        return new Callback([
            'callback' => function (string $totalsMode, ExecutionContextInterface $context) {
                $connectionFormat = $context->getRoot()
                     ->get(PunchoutCatalogConnectionTransfer::FORMAT)
                     ->getData();

                $allowedConnectionFormats = static::ALLOWED_CONNECTION_FORMATS_FOR_TOTALS_MODES[$totalsMode] ?? [];

                if (!in_array($connectionFormat, $allowedConnectionFormats)) {
                    $context->addViolation(
                        sprintf(
                            static::MESSAGE_INCOMPATIBLE_TOTALS_MODE_AND_CONNECTION_FORMAT,
                            static::MESSAGE_PARAM_TOTALS_MODE,
                            static::MESSAGE_PARAM_CONNECTION_FORMAT
                        ),
                        [
                            static::MESSAGE_PARAM_TOTALS_MODE => $totalsMode,
                            static::MESSAGE_PARAM_CONNECTION_FORMAT => $connectionFormat,
                        ]
                    );
                }
            },
        ]);
    }
}
