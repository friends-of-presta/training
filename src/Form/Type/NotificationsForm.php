<?php

namespace PrestaShop\Training\Form\Type;

use PrestaShopBundle\Form\Admin\Type\CommonAbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use PrestaShopBundle\Form\Admin\Type\SwitchType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Form management with PrestaShop 1.7.
 */
class NotificationsForm extends CommonAbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('enable_notifications', SwitchType::class, [
            'label' => 'Activer les notifications ?',
        ])
            ->add('stock_limit', NumberType::class, [
                'label' => 'Limite de stocks',
            ])
            ->add('list_emails', null, [
                'label' => 'Liste des emails',
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Sauver',
            ])
        ;
    }
}
