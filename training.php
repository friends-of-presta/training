<?php

require_once __DIR__.'/vendor/autoload.php';

use Symfony\Component\Form\Extension\Core\Type\TextType;

use PrestaShop\PrestaShop\Core\Grid\Column\Type\DataColumn;
use PrestaShop\PrestaShop\Core\Grid\Filter\Filter;

use PrestaShop\Training\Product\ProductHooks;
use PrestaShop\Training\Product\AlternativeDescription;
use PrestaShop\Training\Product\ProductsCollection;
use PrestaShop\Training\Menu\TabManager;

/**
 * A complete module to demonstrate PrestaShop 1.7 features
 */
class Training extends Module
{
    /**
     * @var array list of available Product hooks.
     */
    private $productHooks;

    public function __construct()
    {
        $this->name = 'training';
        $this->version = '1.0.0';
        $this->author = 'PrestaShop';
        $this->need_instance = false;

        parent::__construct();

        $this->displayName = $this->trans('PrestaShop Training module');

        $this->productHooks = array_merge(ProductHooks::PRODUCT_LIST_HOOKS, ProductHooks::PRODUCT_FORM_HOOKS);
    }

    public function hookActionLanguageGridDefinitionModifier(array $params)
    {
        /** @var \PrestaShop\PrestaShop\Core\Grid\Definition\GridDefinitionInterface $definition */
        $definition = $params['definition'];

        $definition->getColumns()
            ->remove('id_lang')
            ->addAfter(
                'date_format_full',
                (new DataColumn('locale'))
                    ->setName('LOCALE')
                    ->setOptions([
                        'field' => 'locale',
                    ])
            )
        ;

        $definition->getFilters()
            ->remove('id_lang')
            ->add((new Filter('locale', TextType::class))
                ->setAssociatedColumn('locale')
                ->setTypeOptions([
                    'attr' => [
                        'placeholder' => 'SEARCH ISO CODE',
                    ],
                ])
            )
        ;
    }

    /**
     * Adds a new option "Shop motto" to the General Page form.
     *
     * @param array $hookParams
     */
    public function hookActionGeneralPageForm(array $hookParams)
    {
        /** @var \Symfony\Component\Form\FormBuilder $formBuilder */
        $formBuilder = $hookParams['form_builder'];

        $formBuilder->add('shop_motto', TextType::class, [
            'data' => Configuration::get('PS_TRAINING_SHOP_MOTTO'),
        ]);
    }

    /**
     * Save the extra configuration value added to the General Page form.
     *
     * @param array $hookParams
     */
    public function hookActionGeneralPageSave(array $hookParams)
    {
        $motto = $hookParams['form_data']['shop_motto'];

        Configuration::updateValue('PS_TRAINING_SHOP_MOTTO', $motto);
    }

    /**
     * Display "alternative" in Product page.
     * @param array $hookParams
     * @return string
     */
    public function hookDisplayAdminProductsMainStepLeftColumnMiddle($hookParams)
    {
        $productId = $hookParams['id_product'];
        /** @var \Symfony\Component\Form\FormInterface $formFactory */
        $formFactory = $this->get('form.factory');

        /** @var \Twig\Environment $twig */
        $twig = $this->get('twig');
        $form = AlternativeDescription::addToForm($productId, $formFactory);
        // You don't need to design your form, call only form_row(my_field) in
        // your template.
        return AlternativeDescription::setTemplateToProductPage($twig, $form);
    }

    /**
     * Add the field "alternative_description to Product table.
     */
    public function hookActionDispatcherBefore()
    {
        AlternativeDescription::addToProductDefinition();
    }

    /**
     * Manage the list of product fields available in the Product Catalog page.
     * @param array $hookParams
     */
    public function hookActionAdminProductsListingFieldsModifier(&$hookParams)
    {
        $hookParams['sql_select']['alternative_description'] = [
            'table' => 'p',
            'field' => 'alternative_description',
            'filtering' => "LIKE '%%%s%%'",
        ];
    }

    /**
     * Manage the list of products available in the Product Catalog page.
     * @param array $hookParams
     */
    public function hookActionAdminProductsListingResultsModifier(&$hookParams)
    {
        $hookParams['products'] = ProductsCollection::make($hookParams['products'])
            ->sortBy('alternative_description')
            ->all()
        ;
    }
    /**
     * Manage the information in a specific tab of Product Page.
     * @param array $hookParams
     * @return string
     */
    public function hookDisplayAdminProductsExtra(&$hookParams)
    {
        return $this->get('twig')->render('@PrestaShop/Products/module_panel.html.twig');
    }

    /**
     * {@inheritdoc}
     */
    public function install()
    {
        return parent::install() &&
            $this->registerHook('actionLanguageGridDefinitionModifier') &&
            $this->registerHook('actionLanguageGridQueryBuilderModifier') &&
            $this->registerHook('actionLanguageGridPresenterModifier') &&
            $this->registerHook('actionLanguageGridGridFilterFormModifier') &&
            $this->registerHook('actionLanguageGridGridDataModifier') &&

            $this->registerHook('actionGeneralPageForm') &&
            $this->registerHook('actionGeneralPageSave') &&

            AlternativeDescription::addToProductTable() &&
            $this->registerHook($this->productHooks) &&
            $this->installTabs()
            ;
    }

    /**
     * {@inheritdoc}
     */
    public function uninstall()
    {
        $productHooksUnregistrationSuccess = true;

        foreach ($this->productHooks as $productHook) {
            if (false === $this->unregisterHook($productHook)) {
                $productHooksUnregistrationSuccess = false;
            }
        }
        return parent::uninstall() &&
            $this->unregisterHook('actionLanguageGridDefinitionModifier') &&
            $this->unregisterHook('actionLanguageGridQueryBuilderModifier') &&
            $this->unregisterHook('actionLanguageGridPresenterModifier') &&
            $this->unregisterHook('actionLanguageGridGridFilterFormModifier') &&
            $this->unregisterHook('actionLanguageGridGridDataModifier') &&

            $this->unregisterHook('actionGeneralPageForm') &&
            $this->unregisterHook('actionGeneralPageSave') &&

            AlternativeDescription::removeToProductTable() &&
            $this->uninstallTabs() &&
            $productHooksUnregistrationSuccess
        ;

        $this->get('cache_clearer')->clearAllCaches();
    }

    public function installTabs()
    {
        TabManager::addTab('AdminTraining', 'Training Menu', 'training', 'AdminTools');
        TabManager::addTab('AdminTrainingIndexClass', 'Controller exemple', 'training', 'AdminTraining');
        TabManager::addTab('AdminTrainingGridClass', 'Grid exemple', 'training', 'AdminTraining');

        return true;
    }

    public function uninstallTabs()
    {
        TabManager::removeTab('AdminTrainingIndexClass');
        TabManager::removeTab('AdminTrainingGridClass');
        TabManager::removeTab('AdminTraining');

        return true;
    }

    /**
     * @return bool forces the module to use the new translation system.
     */
    public function isUsingNewTranslationSystem()
    {
        return true;
    }
}
