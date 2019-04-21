<?php

namespace PrestaShop\Training\Controller\Admin;

use PrestaShopBundle\Security\Annotation\AdminSecurity;
use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use PrestaShop\Training\Form\Type\NotificationsForm;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Here, you'll learn basics of PrestaShop modern controllers.
 *
 * @see https://devdocs.prestashop.com/1.7/development/architecture/migration-guide/controller-routing/#modern-symfony-controllers
 */
class ExempleController extends FrameworkBundleAdminController
{
    /**
     * @see https://devdocs.prestashop.com/1.7/development/architecture/migration-guide/controller-routing/#security
     * @AdminSecurity(
     *     "is_granted(['read'], request.get('_legacy_controller'))",
     *     message="You do not have permission to access Exemple page."
     * )
     *
     * @return Response
     */
    public function indexAction()
    {
        $formData = [
            'enable_notifications' => $this->configuration->getBoolean('MC_NOTIFICATIONS', true),
            'stock_limit' => $this->configuration->getInt('MC_STOCK_LIMIT', 0),
            'list_emails' => $this->configuration->get('MC_NOTIFIERS_EMAILS', ''),
        ];

        $form = $this->createForm(NotificationsForm::class, $formData);

        return $this->render('@Modules/training/views/Admin/Training/exemple.html.twig', [
            'layoutTitle' => 'Controller using the modern architecture',
            'help_link' => false,
            'notificationsForm' => $form->createView(),
        ]);
    }

    /**
     * @AdminSecurity(
     *     "is_granted(['create', 'update'], request.get('_legacy_controller'))",
     *     message="You do not have permission to change settings.",
     *     redirectRoute="admin_ps_training_index"
     * )
     *
     * @return Response
     */
    public function formAction(Request $request)
    {
        $form = $this->createForm(NotificationsForm::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $this->configuration->set('MC_NOTIFICATIONS', $formData['enable_notifications']);
            $this->configuration->set('MC_STOCK_LIMIT', $formData['stock_limit']);
            $this->configuration->set('MC_NOTIFIERS_EMAILS', $formData['list_emails']);
        }

        return $this->redirectToRoute('admin_ps_training_index');
    }
}
