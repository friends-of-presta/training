<?php

namespace PrestaShop\Training\Controller\Admin;

use PrestaShop\Training\Grid\Filter\ProductFilters;
use PrestaShopBundle\Security\Annotation\AdminSecurity;
use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * Exemple of Grid with a list of products.
 */
class ProductController extends FrameworkBundleAdminController
{
    /**
     * Show products listing
     *
     * @AdminSecurity(
     *     "is_granted(['read'], request.get('_legacy_controller'))",
     *     message="You do not have permission to access Products listing page."
     * )
     *
     * @param ProductFilters $filters
     *
     * @return Response
     */
    public function listingAction(ProductFilters $filters)
    {
        $presenter = $this->get('prestashop.core.grid.presenter.grid_presenter');
        $productGrid = $this->get('ps_training.grid.product_grid_factory')->getGrid($filters);

        return $this->render('@Modules/training/views/Admin/Training/grid.html.twig', [
            'layoutTitle' => $this->trans('Products', 'Modules.Training.Admin'),
            'productGrid' => $presenter->present($productGrid),
        ]);
    }

    /**
     * Perform search on products list
     *
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function searchAction(Request $request)
    {
        $definitionFactory = $this->get('ps_training.grid.definition.product_grid_definition_factory');
        $productDefinition = $definitionFactory->getDefinition();

        $gridFilterFormFactory = $this->get('prestashop.core.grid.filter.form_factory');
        $filtersForm = $gridFilterFormFactory->create($productDefinition);

        $filtersForm->handleRequest($request);
        $filters = [];

        if ($filtersForm->isSubmitted()) {
            $filters = $filtersForm->getData();
        }

        return $this->redirectToRoute('admin_ps_training_products', ['filters' => $filters]);
    }
}
