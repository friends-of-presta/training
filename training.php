<?php

require_once __DIR__ . '/vendor/autoload.php';

use Twig\Environment;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilder;
use PrestaShop\PrestaShop\Core\Grid\Definition\GridDefinitionInterface;
use PrestaShop\PrestaShop\Adapter\Configuration;
use PrestaShop\Training\Product\ProductHooks;
use PrestaShop\Training\Product\AlternativeDescription;
use PrestaShop\Training\Product\ProductsCollection;
use PrestaShop\Training\Menu\TabManager;
use PrestaShop\Training\Grid\Logs;

/**
 * A complete module to demonstrate PrestaShop 1.7 features
 */
class Training extends Module
{
    /**
     * @var array list of available Product hooks
     */
    private $productHooks;

    /**
     * Training constructor.
     */
    public function __construct()
    {
        $this->name = 'training';
        $this->version = '1.0.1';
        $this->author = 'PrestaShop';
        $this->need_instance = false;

        parent::__construct();

        $this->displayName = $this->trans('PrestaShop Training module');
        $this->description = $this->trans('Demonstrate all the features of the PrestaShop Back Office, in its version 1.7.5');

        $this->productHooks = array_merge(ProductHooks::PRODUCT_LIST_HOOKS, ProductHooks::PRODUCT_FORM_HOOKS);
    }

    public function hookActionLogsGridDefinitionModifier(array $params)
    {
        /** @var GridDefinitionInterface $definition */
        $definition = $params['definition'];

        Logs::moveObjectTypeAfterErrorColumn($definition);
        Logs::updateObjectTypeFilterForm($definition);
    }

    /**
     * Adds a new option "Shop motto" to the General Page form.
     *
     * @param array $hookParams
     */
    public function hookActionGeneralPageForm(array $hookParams)
    {
        /** @var FormBuilder $formBuilder */
        $formBuilder = $hookParams['form_builder'];

        $formBuilder->add('shop_motto', TextType::class, [
            'data' => $this->get('prestashop.adapter.legacy.configuration')->get('PS_TRAINING_SHOP_MOTTO'),
        ]);
    }

    /**
     * Save the extra configuration "Shop motto" value added to the General Page form.
     *
     * @param array $hookParams
     */
    public function hookActionGeneralPageSave(array $hookParams)
    {
        $motto = $hookParams['form_data']['shop_motto'];

        /* @var Configuration */
        $this->get('prestashop.adapter.legacy.configuration')->set('PS_TRAINING_SHOP_MOTTO', $motto);
    }

    /**
     * Display "alternative" in Product page.
     *
     * @param array $hookParams
     *
     * @return string
     */
    public function hookDisplayAdminProductsMainStepLeftColumnMiddle($hookParams)
    {
        $productId = $hookParams['id_product'];
        /** @var FormFactoryInterface $formFactory */
        $formFactory = $this->get('form.factory');

        /** @var Environment $twig */
        $twig = $this->get('twig');

        /** @var FormInterface $form */
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
     *
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
     *
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
     *
     * @return string
     */
    public function hookDisplayAdminProductsExtra()
    {
        return $this->get('twig')->render('@PrestaShop/Products/module_panel.html.twig');
    }

    /**
     * {@inheritdoc}
     */
    public function install()
    {
        return parent::install() &&
            $this->registerHook('actionLogsGridDefinitionModifier') &&
            $this->registerHook('actionLogsGridQueryBuilderModifier') &&
            $this->registerHook('actionLogsGridPresenterModifier') &&
            $this->registerHook('actionLogsGridFilterFormModifier') &&
            $this->registerHook('actionLogsGridGridDataModifier') &&

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

        $uninstallationSuccess = parent::uninstall() &&
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

        return $uninstallationSuccess;
    }

    /**
     * Setup the Training Menu as Modern Controllers are not managed (yet) by the $tabs property.
     *
     * @return bool
     *
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function installTabs()
    {
        TabManager::addTab('AdminTraining', 'Training Menu', 'training', 'AdminTools');
        TabManager::addTab('AdminTrainingIndexClass', 'Controller exemple', 'training', 'AdminTraining');
        TabManager::addTab('AdminTrainingGridClass', 'Grid exemple', 'training', 'AdminTraining');

        return true;
    }

    /**
     * Remove the Training Menu as Modern Controllers are not managed (yet) by the $tabs property.
     *
     * @return bool
     *
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function uninstallTabs()
    {
        TabManager::removeTab('AdminTrainingIndexClass');
        TabManager::removeTab('AdminTrainingGridClass');
        TabManager::removeTab('AdminTraining');

        return true;
    }

    /**
     * @return bool forces the module to use the new translation system
     */
    public function isUsingNewTranslationSystem()
    {
        return true;
    }
}
