<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\PunchoutCatalogs\Communication\Form;

use Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \SprykerEco\Zed\PunchoutCatalogs\PunchoutCatalogsConfig getConfig()
 * @method \SprykerEco\Zed\PunchoutCatalogs\Persistence\PunchoutCatalogsRepositoryInterface getRepository()
 * @method \SprykerEco\Zed\PunchoutCatalogs\Business\PunchoutCatalogsFacadeInterface getFacade()
 * @method \SprykerEco\Zed\PunchoutCatalogs\Communication\PunchoutCatalogsCommunicationFactory getFactory()
 */
class PunchoutCatalogConnectionForm extends AbstractType
{
    public const OPTION_BUSINESS_UNIT_CHOICES = 'OPTION_BUSINESS_UNIT_CHOICES';
    public const OPTION_CONNECTION_FORMAT_SUB_FORM_TYPES = 'OPTION_CONNECTION_FORMAT_FORMS';

    protected const BUSINESS_UNIT_FIELD_PLACEHOLDER = 'Choose business unit';
    protected const BUSINESS_UNIT_FIELD_LABEL = 'Business unit';

    protected const VALIDATION_GROUP_DISABLED = 'disabled';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addNameField($builder)
            ->addBusinessUnitField($builder, $options)
            ->addAddMappingField($builder)
            ->addConnectionFormatField($builder, $options)
            ->addConnectionFormatSubForms($builder, $options);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired([
            static::OPTION_BUSINESS_UNIT_CHOICES,
            static::OPTION_CONNECTION_FORMAT_SUB_FORM_TYPES,
        ]);
    }

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'punchoutCatalogConnection';
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addNameField(FormBuilderInterface $builder)
    {
        $builder->add(PunchoutCatalogConnectionTransfer::NAME, TextType::class, [
            'constraints' => [
                new NotBlank(),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addConnectionFormatField(FormBuilderInterface $builder, array $options)
    {
        $formats = array_keys($options[static::OPTION_CONNECTION_FORMAT_SUB_FORM_TYPES]);

        $builder->add(
            PunchoutCatalogConnectionTransfer::FORMAT,
            ChoiceType::class,
            [
                'choices' => array_combine($formats, $formats),
                'attr' => [
                    'class' => 'dependent-trigger',
                    'data-dependent-group' => 'format',
                ],
                'constraints' => [
                    new NotBlank(),
                ],
            ]
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addConnectionFormatSubForms(FormBuilderInterface $builder, array $options)
    {
        foreach ($options[static::OPTION_CONNECTION_FORMAT_SUB_FORM_TYPES] as $connectionFormat => $connectionFormatSubFormType) {
            $builder->add($connectionFormat, $connectionFormatSubFormType, [
                'mapped' => false,
                'validation_groups' => static::VALIDATION_GROUP_DISABLED,
                'label' => false,
                'attr' => [
                    'class' => 'dependent-child',
                    'data-dependent-type' => $connectionFormat,
                    'data-dependent-group' => 'format',
                ],
            ]);
        }

        $this->addConnectionFormatDynamicSubFormListener($builder);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return void
     */
    protected function addConnectionFormatDynamicSubFormListener(FormBuilderInterface $builder): void
    {
        $connectionFormatSubFormTypes = static::OPTION_CONNECTION_FORMAT_SUB_FORM_TYPES;
        $formModificationCallback = function (FormEvent $event) use ($connectionFormatSubFormTypes) {
            $format = $event->getData()[PunchoutCatalogConnectionTransfer::FORMAT] ?? null;

            if (!$format) {
                return;
            }

            $this->addActiveDependentFieldSubFormToConnectionForm(
                $event->getForm(),
                $connectionFormatSubFormTypes,
                $format
            );
        };

        $builder->addEventListener(FormEvents::PRE_SET_DATA, $formModificationCallback);
        $builder->addEventListener(FormEvents::PRE_SUBMIT, $formModificationCallback);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     * @param string $subFormsOptionName
     * @param string $selectedSubFormName
     *
     * @return void
     */
    protected function addActiveDependentFieldSubFormToConnectionForm(FormInterface $form, string $subFormsOptionName, string $selectedSubFormName): void
    {
        $options = $form->getConfig()->getOptions();
        $associatedFormType = $options[$subFormsOptionName][$selectedSubFormName] ?? null;

        if (!$selectedSubFormName || !$associatedFormType) {
            return;
        }

        $options = $form->get($selectedSubFormName)
            ->getConfig()
            ->getOptions();

        $form->add(
            $selectedSubFormName,
            $associatedFormType,
            array_merge(
                [
                    'inherit_data' => true,
                    'label' => false,
                ],
                $options
            )
        );
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addAddMappingField(FormBuilderInterface $builder)
    {
        $builder->add(PunchoutCatalogConnectionTransfer::MAPPING, TextareaType::class, [
            'required' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addBusinessUnitField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            PunchoutCatalogConnectionTransfer::FK_COMPANY_BUSINESS_UNIT,
            ChoiceType::class,
            [
                'choices' => $options[static::OPTION_BUSINESS_UNIT_CHOICES],
                'placeholder' => static::BUSINESS_UNIT_FIELD_PLACEHOLDER,
                'label' => static::BUSINESS_UNIT_FIELD_LABEL,
                'constraints' => [
                    new NotBlank(),
                ],
            ]
        );

        return $this;
    }
}
