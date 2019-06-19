<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\PunchoutCatalogs\Communication\Form\ConnectionSetupSubForms;

use Closure;
use Generated\Shared\Transfer\PunchoutCatalogConnectionSetupTransfer;
use Generated\Shared\Transfer\PunchoutCatalogConnectionTransfer;
use Spryker\Zed\Gui\Communication\Form\Type\SelectType;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
class PunchoutCatalogConnectionSetupForm extends AbstractType
{
    protected const DEPENDENT_GROUP_LOGIN_MODE = 'login-mode';

    protected const LOGIN_MODE_SINGLE_USER = 'single_user';
    protected const LOGIN_MODE_DYNAMIC_USER = 'dynamic_user';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addLoginModeField($builder)
            ->addCompanyBusinessUnitField($builder)
            ->addCompanyBusinessUnitFieldListeners($builder)
            ->addCompanyUserField($builder)
            ->addCompanyUserFieldListeners($builder);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PunchoutCatalogConnectionSetupTransfer::class,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addLoginModeField(FormBuilderInterface $builder)
    {
        $builder->add(PunchoutCatalogConnectionSetupTransfer::LOGIN_MODE, ChoiceType::class, [
            'label' => 'Login Mode',
            'choices' => [
                'single_user' => static::LOGIN_MODE_SINGLE_USER,
                'dynamic_user_creation' => static::LOGIN_MODE_DYNAMIC_USER,
            ],
            'attr' => [
                'class' => 'dependent-trigger',
                'data-dependent-group' => static::DEPENDENT_GROUP_LOGIN_MODE,
            ],
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
    protected function addCompanyBusinessUnitField(FormBuilderInterface $builder)
    {
        $builder->add(PunchoutCatalogConnectionSetupTransfer::FK_COMPANY_BUSINESS_UNIT, SelectType::class, array_merge(
            $this->getCompanyBusinessUnitFieldOptions()
        ));

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addCompanyUserField(FormBuilderInterface $builder)
    {
        $builder->add(
            PunchoutCatalogConnectionSetupTransfer::FK_COMPANY_USER,
            SelectType::class,
            $this->getCompanyUserFieldOptions()
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addCompanyBusinessUnitFieldListeners(FormBuilderInterface $builder)
    {
        $builder->addEventListener(FormEvents::PRE_SUBMIT, $this->createCompanyBusinessUnitFormEventListener());
        $builder->addEventListener(FormEvents::PRE_SET_DATA, $this->createCompanyBusinessUnitFormEventListener());

        return $this;
    }

    /**
     * @return \Closure
     */
    protected function createCompanyBusinessUnitFormEventListener(): Closure
    {
        return function (FormEvent $event) {
            $form = $event->getForm();
            $loginMode = $event->getData()[PunchoutCatalogConnectionSetupTransfer::LOGIN_MODE] ?? null;

            if ($loginMode !== static::LOGIN_MODE_DYNAMIC_USER) {
                $this->disableCompanyBusinessUnitField($form);

                return;
            }

            $parentCompanyBusinessUnitId = $form
                ->getParent()
                ->getParent()
                ->get(PunchoutCatalogConnectionTransfer::FK_COMPANY_BUSINESS_UNIT)
                ->getData();

            if (!$parentCompanyBusinessUnitId) {
                return;
            }

            $this->updateCompanyBusinessUnitFieldChoices($form, $parentCompanyBusinessUnitId);
        };
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addCompanyUserFieldListeners(FormBuilderInterface $builder)
    {
        $companyUserEventListenerCallback = $this->createCompanyUserFormEventListener();

        $builder->addEventListener(FormEvents::PRE_SUBMIT, $companyUserEventListenerCallback);
        $builder->addEventListener(FormEvents::PRE_SET_DATA, $companyUserEventListenerCallback);

        return $this;
    }

    /**
     * @return \Closure
     */
    protected function createCompanyUserFormEventListener(): Closure
    {
        return function (FormEvent $event) {
            $form = $event->getForm();
            $loginMode = $event->getData()[PunchoutCatalogConnectionSetupTransfer::LOGIN_MODE] ?? null;

            if ($loginMode !== static::LOGIN_MODE_SINGLE_USER) {
                $this->disableCompanyUserField($form);

                return;
            }

            $parentCompanyBusinessUnitId = $form
                ->getParent()
                ->getParent()
                ->get(PunchoutCatalogConnectionTransfer::FK_COMPANY_BUSINESS_UNIT)
                ->getData();

            if (!$parentCompanyBusinessUnitId) {
                return;
            }

            $this->updateCompanyUserFieldChoices($form, $parentCompanyBusinessUnitId);
        };
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return void
     */
    protected function disableCompanyBusinessUnitField(FormInterface $form): void
    {
        $form->add(PunchoutCatalogConnectionSetupTransfer::FK_COMPANY_BUSINESS_UNIT, SelectType::class, array_merge(
            $this->getCompanyBusinessUnitFieldOptions(),
            [
                'mapped' => false,
            ]
        ));
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return void
     */
    protected function disableCompanyUserField(FormInterface $form): void
    {
        $form->add(PunchoutCatalogConnectionSetupTransfer::FK_COMPANY_USER, SelectType::class, array_merge(
            $this->getCompanyUserFieldOptions(),
            [
                'mapped' => false,
            ]
        ));
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     * @param int $parentCompanyBusinessUnitId
     *
     * @return void
     */
    protected function updateCompanyBusinessUnitFieldChoices(FormInterface $form, int $parentCompanyBusinessUnitId): void
    {
        $companyBusinessUnitChoices = $this->getFactory()
            ->createPunchoutCatalogSetupRequestConnectionTypeFormDataProvider()
            ->getCompanyBusinessUnitChoices($parentCompanyBusinessUnitId);

        $form->add(PunchoutCatalogConnectionSetupTransfer::FK_COMPANY_BUSINESS_UNIT, SelectType::class, array_merge(
            $this->getCompanyBusinessUnitFieldOptions(),
            [
                'choices' => $companyBusinessUnitChoices,
            ]
        ));
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     * @param int $parentCompanyBusinessUnitId
     *
     * @return void
     */
    protected function updateCompanyUserFieldChoices(FormInterface $form, int $parentCompanyBusinessUnitId): void
    {
        $companyUserChoices = $this->getFactory()
            ->createPunchoutCatalogSetupRequestConnectionTypeFormDataProvider()
            ->getCompanyUserChoices($parentCompanyBusinessUnitId);

        $form->add(PunchoutCatalogConnectionSetupTransfer::FK_COMPANY_USER, SelectType::class, array_merge(
            $this->getCompanyUserFieldOptions(),
            [
                'choices' => $companyUserChoices,
            ]
        ));
    }

    /**
     * @return array
     */
    protected function getCompanyBusinessUnitFieldOptions(): array
    {
        return [
            'label' => 'Default Business Unit',
            'attr' => [
                'class' => 'dependent-child',
                'data-dependent-group' => static::DEPENDENT_GROUP_LOGIN_MODE,
                'data-dependent-type' => static::LOGIN_MODE_DYNAMIC_USER,
            ],
        ];
    }

    /**
     * @return array
     */
    protected function getCompanyUserFieldOptions(): array
    {
        return [
            'label' => 'Single User',
            'attr' => [
                'class' => 'dependent-child',
                'data-dependent-group' => static::DEPENDENT_GROUP_LOGIN_MODE,
                'data-dependent-type' => static::LOGIN_MODE_SINGLE_USER,
            ],
        ];
    }
}
