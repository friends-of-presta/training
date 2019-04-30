<?php

namespace PrestaShop\Training\Grid;

use PrestaShop\PrestaShop\Core\Grid\Column\Type\DataColumn;
use PrestaShop\PrestaShop\Core\Grid\Definition\GridDefinitionInterface;
use PrestaShop\PrestaShop\Core\Grid\Filter\Filter;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * Stores all the customizations done in the Logs Grid of the Back Office.
 */
final class Logs
{
    /**
     * This moves the column "Object Type" after the "Error Code" one.
     * No need to touch any template for that!
     *
     * @param GridDefinitionInterface $definition
     */
    public static function moveObjectTypeAfterErrorColumn(GridDefinitionInterface $definition)
    {
        $definition->getColumns()
            ->remove('object_type')
            ->addAfter(
                'error_code',
                (new DataColumn('object_type'))
                    ->setName('Object Type')
                    ->setOptions([
                        'field' => 'object_type',
                    ])
            )
        ;
    }

    /**
     * You can update the Filter Form of any grid.
     * See @doc https://symfony.com/doc/3.4/components/form.html for all available options.
     *
     * @param GridDefinitionInterface $definition
     */
    public static function updateObjectTypeFilterForm(GridDefinitionInterface $definition)
    {
        $definition->getFilters()
            ->remove('message')
            ->add((new Filter('message', TextType::class))
                ->setAssociatedColumn('message')
                ->setTypeOptions([
                    'attr' => [
                        'placeholder' => 'This field accepts regular expressions.',
                    ],
                ])
            )
        ;
    }
}
