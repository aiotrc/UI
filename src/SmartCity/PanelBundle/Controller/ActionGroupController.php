<?php

namespace SmartCity\PanelBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use SmartCity\UserBundle\Entity\ActionGroup;
use SmartCity\UserBundle\Form\Type\ActionGroupType;
use SmartCity\UserBundle\Annotation\FrontendAccessible;

/**
 * ActionGroup controller.
 *
 * @Route("/")
 */
class ActionGroupController extends Controller
{
    /**
     * ActionGroup listing.
     *
     * @Route("/", name="panel_action_group_index")
     * @FrontendAccessible(adminAccessible=true)
     * @Method("GET")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        $actionGroupDatatable = $this->get("SmartCity.actionGroup.datatable");
        $actionGroupDatatable->buildDatatable();

        return array(
            "datatable" => $actionGroupDatatable,
        );
    }

    /**
     * Get all User entities.
     *
     * @Route("/results", name="panel_action_group_results")
     * @FrontendAccessible(adminAccessible=true)
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexResultsAction()
    {
        $actionGroupDatatable = $this->get("SmartCity.actionGroup.datatable");
        $actionGroupDatatable->buildDatatable();

        $datatable = $this->get("sg_datatables.query")->getQueryFrom($actionGroupDatatable);
        return $datatable->getResponse();
    }

    /**
     * Creates a new ActionGroup entity.
     *
     * @Route("/new", name="panel_action_group_new")
     * @FrontendAccessible(adminAccessible=true)
     * @Method({"GET", "POST"})
     * @Template()
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function newAction(Request $request)
    {

        $entity = new ActionGroup();
        $form = $this->createForm(new ActionGroupType(), $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirectToRoute('panel_action_group_edit', array('id' => $entity->getId()));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a ActionGroup entity.
     *
     * @Route("/{id}", name="panel_action_group_show", options={"expose"=true})
     * @FrontendAccessible(adminAccessible=true)
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SmartCityUserBundle:ActionGroup')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ActionGroup entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing ActionGroup entity.
     *
     * @Route("/{id}/edit", name="panel_action_group_edit", options={"expose"=true})
     * @FrontendAccessible(adminAccessible=true)
     * @Method({"GET", "POST"})
     * @Template()
     * 
     * @param Request $request
     * @param ActionGroup $entity
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function editAction(Request $request, ActionGroup $entity)
    {
        $deleteForm = $this->createDeleteForm($entity->getId());
        $editForm = $this->createForm(new ActionGroupType(), $entity);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirectToRoute('panel_action_group_edit', array('id' => $entity->getId()));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a ActionGroup entity.
     *
     * @Route("/{id}", name="panel_action_group_delete")
     * @FrontendAccessible(adminAccessible=true)
     * @FrontendAccessible(adminAccessible=true)
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SmartCityUserBundle:ActionGroup')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find ActionGroup entity.');
            }

            // $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('panel_action_group_index'));
    }

    /**
     * Creates a form to delete a ActionGroup entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('panel_action_group_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
