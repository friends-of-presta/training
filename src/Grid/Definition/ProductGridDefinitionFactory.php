<?php

namespace PrestaShop\Training\Grid\Definition;

use PrestaShop\Training\Grid\Column\StockColumn;
use PrestaShop\PrestaShop\Core\Grid\Action\GridActionCollection;
use PrestaShop\PrestaShop\Core\Grid\Action\Row\RowActionCollection;
use PrestaShop\PrestaShop\Core\Grid\Action\Row\Type\LinkRowAction;
use PrestaShop\PrestaShop\Core\Grid\Action\Type\SimpleGridAction;
use PrestaShop\PrestaShop\Core\Grid\Column\ColumnCollection;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\Common\ActionColumn;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\DataColumn;
use PrestaShop\PrestaShop\Core\Grid\Definition\Factory\AbstractGridDefinitionFactory;
use PrestaShop\PrestaShop\Core\Grid\Filter\Filter;
use PrestaShop\PrestaShop\Core\Grid\Filter\FilterCollection;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use PrestaShop\PrestaShop\Core\Hook\HookDispatcherInterface;
use PrestaShop\Training\Grid\FormType\YesAndNoChoiceType;
use PrestaShopBundle\Form\Admin\Type\SearchAndResetType;
use PrestaShop\Training\Grid\Column\LinkColumn;

/**
 * Class ProductGridDefinitionFactory creates definition for our products grid
 */
final class ProductGridDefinitionFactory extends AbstractGridDefinitionFactory
{
    /**
     * @var string
     */
    private $resetFiltersUrl;

    /**
     * @var string
     */
    private $redirectUrl;

    /**
     * @param string $resetFiltersUrl
     * @param string $redirectUrl
     */
    public function __construct(HookDispatcherInterface $hookDispatcher, $resetFiltersUrl, $redirectUrl)
    {
        $this->resetFiltersUrl = $resetFiltersUrl;
        $this->redirectUrl = $redirectUrl;

        parent::__construct($hookDispatcher);
    }

    /**
     * {@inheritdoc}
     */
    protected function getId()
    {
        return 'training_products';
    }

    /**
     * {@inheritdoc}
     */
    protected function getName()
    {
        return $this->trans('Products', [], 'Modules.Training.Admin');
    }

    /**
     * {@inheritdoc}
     */
    protected function getColumns()
    {
        return (new ColumnCollection())
            ->add((new DataColumn('id_product'))
                ->setName($this->trans('ID', [], 'Modules.Training.Admin'))
                ->setOptions([
                    'field' => 'id_product',
                ])
            )
            ->add((new LinkColumn('name'))
                ->setName($this->trans('Name', [], 'Modules.Training.Admin'))
                ->setOptions([
                    'field' => 'name',
                    'route' => 'admin_product_form',
                    'route_param_name' => 'id',
                    'route_param_field' => 'id_product',
                ])
            )
            ->add((new StockColumn('in_stock'))
                ->setName($this->trans('In Stock', [], 'Modules.Training.Admin'))
                ->setOptions([
                    'quantity_field' => 'quantity',
                    'with_quantity' => true,
                ])
            )
            ->add((new ActionColumn('actions'))
                ->setName($this->trans('Actions', [], 'Admin.Actions'))
                ->setOptions([
                    'actions' => $this->getRowActions(),
                ])
            )
        ;
    }

    /**
     * {@inheritdoc}
     *
     * Define filters and associate them with columns.
     * Note that you can add filters that are not associated with any column.
     */
    protected function getFilters()
    {
        return (new FilterCollection())
            ->add((new Filter('id_product', TextType::class))
                ->setTypeOptions([
                    'required' => false,
                ])
                ->setAssociatedColumn('id_product')
            )
            ->add((new Filter('name', TextType::class))
                ->setTypeOptions([
                    'required' => false,
                ])
                ->setAssociatedColumn('name')
            )
            ->add((new Filter('in_stock', YesAndNoChoiceType::class))
                ->setAssociatedColumn('in_stock')
            )
            ->add((new Filter('actions', SearchAndResetType::class))
                ->setTypeOptions([
                    'attr' => [
                        'data-url' => $this->resetFiltersUrl,
                        'data-redirect' => $this->redirectUrl,
                    ],
                ])
                ->setAssociatedColumn('actions')
            )
        ;
    }

    /**
     * {@inheritdoc}
     *
     * Here we define what actions our products grid will have.
     */
    protected function getGridActions()
    {
        return (new GridActionCollection())
            ->add((new SimpleGridAction('common_refresh_list'))
                ->setName($this->trans('Refresh list', [], 'Admin.Advparameters.Feature'))
                ->setIcon('refresh')
            )
            ->add((new SimpleGridAction('common_show_query'))
                ->setName($this->trans('Show SQL query', [], 'Admin.Actions'))
                ->setIcon('code')
            )
            ->add((new SimpleGridAction('common_export_sql_manager'))
                ->setName($this->trans('Export to SQL Manager', [], 'Admin.Actions'))
                ->setIcon('storage')
            )
        ;
    }

    /**
     * Extracted row action definition into separate methods.
     */
    private function getRowActions()
    {
        return (new RowActionCollection())
            ->add((new LinkRowAction('edit'))
                ->setName($this->trans('Edit', [], 'Admin.Actions'))
                ->setOptions([
                    'route' => 'admin_product_form',
                    'route_param_name' => 'id',
                    'route_param_field' => 'id_product',
                ])
                ->setIcon('edit')
            )
        ;
    }
}
