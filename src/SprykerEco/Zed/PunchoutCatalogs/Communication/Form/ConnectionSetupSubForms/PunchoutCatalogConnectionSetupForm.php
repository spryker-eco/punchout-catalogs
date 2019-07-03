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
use Symfony\Component\Form\CallbackTransformer;
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
    protected const TOGGLE_GROUP_LOGIN_MODE = 'login-mode';

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
                'Single User' => static::LOGIN_MODE_SINGLE_USER,
                'Dynamic User Creation' => static::LOGIN_MODE_DYNAMIC_USER,
            ],
            'attr' => [
                'class' => 'toggle-trigger',
                'data-toggle-group' => static::TOGGLE_GROUP_LOGIN_MODE,
            ],
            'constraints' => [
                new NotBlank(),
            ],
        ]);

        $builder->addModelTransformer($this->createLoginModeDependentFieldsModelTransformer());

        return $this;
    }

    /**
     * @return \Symfony\Component\Form\CallbackTransformer
     */
    protected function createLoginModeDependentFieldsModelTransformer(): CallbackTransformer
    {
        return new CallbackTransformer(
            function (?PunchoutCatalogConnectionSetupTransfer $punchoutCatalogConnectionSetupTransfer = null) {
                if (!$punchoutCatalogConnectionSetupTransfer) {
                    return null;
                }

                if ($punchoutCatalogConnectionSetupTransfer->getLoginMode() === static::LOGIN_MODE_SINGLE_USER) {
                    $punchoutCatalogConnectionSetupTransfer->setFkCompanyBusinessUnit(null);

                    return $punchoutCatalogConnectionSetupTransfer;
                }

                $punchoutCatalogConnectionSetupTransfer->setFkCompanyUser(null);

                return $punchoutCatalogConnectionSetupTransfer;
            },
            function (PunchoutCatalogConnectionSetupTransfer $punchoutCatalogConnectionSetupTransfer) {
                return $punchoutCatalogConnectionSetupTransfer;
            }
        );
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addCompanyBusinessUnitField(FormBuilderInterface $builder)
    {
        $builder->add(PunchoutCatalogConnectionSetupTransfer::FK_COMPANY_BUSINESS_UNIT, SelectType::class, [
            'label' => 'Default Business Unit',
            'attr' => [
                'class' => 'toggle-inner-item',
                'data-toggle-group' => static::TOGGLE_GROUP_LOGIN_MODE,
                'data-toggle-type' => static::LOGIN_MODE_DYNAMIC_USER,
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addCompanyUserField(FormBuilderInterface $builder)
    {
        $builder->add(PunchoutCatalogConnectionSetupTransfer::FK_COMPANY_USER, SelectType::class, [
            'label' => 'Single User',
            'attr' => [
                'class' => 'toggle-inner-item',
                'data-toggle-group' => static::TOGGLE_GROUP_LOGIN_MODE,
                'data-toggle-type' => static::LOGIN_MODE_SINGLE_USER,
            ],
        ]);

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
     * @param int $parentCompanyBusinessUnitId
     *
     * @return void
     */
    protected function updateCompanyBusinessUnitFieldChoices(FormInterface $form, int $parentCompanyBusinessUnitId): void
    {
        $companyBusinessUnitChoices = $this->getFactory()
            ->createPunchoutCatalogSetupRequestConnectionTypeFormDataProvider()
            ->getCompanyBusinessUnitChoices($parentCompanyBusinessUnitId);

        $existingOptions = $form->get(PunchoutCatalogConnectionSetupTransfer::FK_COMPANY_BUSINESS_UNIT)
            ->getConfig()
            ->getOptions();

        $form->add(PunchoutCatalogConnectionSetupTransfer::FK_COMPANY_BUSINESS_UNIT, SelectType::class, array_merge(
            $existingOptions,
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

        $existingOptions = $form->get(PunchoutCatalogConnectionSetupTransfer::FK_COMPANY_USER)
            ->getConfig()
            ->getOptions();

        $form->add(PunchoutCatalogConnectionSetupTransfer::FK_COMPANY_USER, SelectType::class, array_merge(
            $existingOptions,
            [
                'choices' => $companyUserChoices,
            ]
        ));
    }
}
