<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\PunchoutCatalogs\Communication\Form;

use Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer;
use Spryker\Zed\Gui\Communication\Form\Type\SelectType;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
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
    public const OPTION_CONNECTION_TYPE_SUB_FORM_TYPES = 'OPTION_CONNECTION_TYPE_SUB_FORM_TYPES';

    protected const VALIDATION_GROUP_DISABLED = 'disabled';

    protected const DEPENDENT_FIELD_GROUP_CONNECTION_TYPE = 'type';

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
            ->addConnectionTypeField($builder, $options)
            ->addConnectionFormatSubForms($builder, $options)
            ->addConnectionTypeSubForms($builder, $options);
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
            static::OPTION_CONNECTION_TYPE_SUB_FORM_TYPES,
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
            'label' => 'Name',
            'constraints' => [
                new NotBlank(),
                new Length(['max' => 255]),
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

        $builder->add(PunchoutCatalogConnectionTransfer::FORMAT, ChoiceType::class, [
            'label' => 'Format',
            'choices' => array_combine($formats, $formats),
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
    protected function addConnectionTypeField(FormBuilderInterface $builder, array $options)
    {
        $types = array_keys($options[static::OPTION_CONNECTION_TYPE_SUB_FORM_TYPES]);

        $builder->add(PunchoutCatalogConnectionTransfer::TYPE, ChoiceType::class, [
            'label' => 'Type',
            'choices' => array_combine($types, $types),
            'constraints' => [
                new NotBlank(),
            ],
            'attr' => [
                'class' => 'dependent-trigger',
                'data-dependent-group' => static::DEPENDENT_FIELD_GROUP_CONNECTION_TYPE,
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
    protected function addConnectionFormatSubForms(FormBuilderInterface $builder, array $options)
    {
        foreach ($options[static::OPTION_CONNECTION_FORMAT_SUB_FORM_TYPES] as $connectionFormat => $connectionFormatSubFormType) {
            $builder->add($connectionFormat, $connectionFormatSubFormType, [
                'mapped' => false,
                'validation_groups' => static::VALIDATION_GROUP_DISABLED,
                'label' => false,
            ]);
        }

        $this->addConnectionFormatDynamicSubFormListener($builder);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addConnectionTypeSubForms(FormBuilderInterface $builder, array $options)
    {
        foreach ($options[static::OPTION_CONNECTION_TYPE_SUB_FORM_TYPES] as $connectionType => $connectionTypeSubFormType) {
            $builder->add($connectionType, $connectionTypeSubFormType, [
                'mapped' => false,
                'validation_groups' => static::VALIDATION_GROUP_DISABLED,
                'label' => false,
                'attr' => [
                    'class' => 'dependent-child',
                    'data-dependent-type' => $connectionType,
                    'data-dependent-group' => static::DEPENDENT_FIELD_GROUP_CONNECTION_TYPE,
                ],
            ]);
        }

        $this->addConnectionTypeDynamicSubFormListener($builder);

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
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return void
     */
    protected function addConnectionTypeDynamicSubFormListener(FormBuilderInterface $builder): void
    {
        $connectionTypeSubFormTypes = static::OPTION_CONNECTION_TYPE_SUB_FORM_TYPES;
        $formModificationCallback = function (FormEvent $event) use ($connectionTypeSubFormTypes) {
            $type = $event->getData()[PunchoutCatalogConnectionTransfer::TYPE] ?? null;

            if (!$type) {
                return;
            }

            $this->addActiveDependentFieldSubFormToConnectionForm(
                $event->getForm(),
                $connectionTypeSubFormTypes,
                $type
            );
        };

        $builder->addEventListener(FormEvents::PRE_SET_DATA, $formModificationCallback)
            ->addEventListener(FormEvents::PRE_SUBMIT, $formModificationCallback);
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

        $form->add($selectedSubFormName, $associatedFormType, array_merge(
            $options,
            [
                'inherit_data' => true,
                'label' => false,
            ]
        ));
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addAddMappingField(FormBuilderInterface $builder)
    {
        $builder->add(PunchoutCatalogConnectionTransfer::MAPPING, TextareaType::class, [
            'label' => 'Mapping',
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
            SelectType::class,
            [
                'choices' => $options[static::OPTION_BUSINESS_UNIT_CHOICES],
                'placeholder' => 'Choose business unit',
                'label' => 'Business Unit',
                'constraints' => [
                    new NotBlank(),
                ],
            ]
        );

        return $this;
    }
}
