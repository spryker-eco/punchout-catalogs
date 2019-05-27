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
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \SprykerEco\Zed\PunchoutCatalogs\Persistence\PunchoutCatalogsRepositoryInterface getRepository()
 * @method \SprykerEco\Zed\PunchoutCatalogs\Business\PunchoutCatalogsFacadeInterface getFacade()
 * @method \SprykerEco\Zed\PunchoutCatalogs\Communication\PunchoutCatalogsCommunicationFactory getFactory()
 * @method \SprykerEco\Zed\PunchoutCatalogs\PunchoutCatalogsConfig getConfig()
 */
class PunchoutCatalogConnectionForm extends AbstractType
{
    public const OPTION_BUSINESS_UNIT_CHOICES = 'OPTION_BUSINESS_UNIT_ID_LIST';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param string[] $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addNameField($builder)
            ->addBusinessUnitField($builder, $options)
            ->addAddMappingField($builder);
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
     * @param string[] $options
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
                'placeholder' => 'Choose business unit',
                'label' => 'Business unit',
                'constraints' => [
                    new NotBlank(),
                ],
            ]
        );

        return $this;
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
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired(static::OPTION_BUSINESS_UNIT_CHOICES);
    }

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'punchoutCatalogConnection';
    }
}
