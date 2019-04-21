<?php

namespace PrestaShop\Training\Grid\FormType;

use PrestaShopBundle\Form\Admin\Type\TranslatorAwareType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class YesAndNoChoiceType from type which proves Yes/No input
 */
final class YesAndNoChoiceType extends TranslatorAwareType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'choices' => [
                $this->trans('Yes', 'Admin.Global') => 1,
                $this->trans('No', 'Admin.Global') => 0,
            ],
            'required' => false,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return ChoiceType::class;
    }
}
