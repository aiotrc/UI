<?php

namespace SmartCity\PanelBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use SmartCity\UserBundle\Entity\Role;
use SmartCity\UserBundle\Form\Type\RoleType;
use SmartCity\UserBundle\Annotation\FrontendAccessible;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Role controller.
 *
 * @Route("/")
 */
class RoleController extends Controller
{
    /**
     * Lists all Role entities.
     *
     * @Route("/", name="panel_role_index")
     * @FrontendAccessible(adminAccessible=true)
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $roleDatatable = $this->get("SmartCity.role.datatable");
        $roleDatatable->buildDatatable();

        return array(
            "datatable" => $roleDatatable,
        );
    }

    /**
     * Get all Role entities.
     *
     * @Route("/results", name="panel_role_results", options={"expose"=true})
     * @FrontendAccessible(adminAccessible=true)
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function resultsAction()
    {
        $datatable = $this->get('SmartCity.role.datatable');
        $datatable->buildDatatable();

        $datatableQuery = $this->get("sg_datatables.query")->getQueryFrom($datatable);
        $qb = $datatableQuery->getQuery();
        return $datatableQuery->getResponse();
    }

    /**
     * Creates a new Role entity.
     *
     * @Route("/new", name="panel_role_new")
     * @FrontendAccessible(adminAccessible=true)
     * @Method({"GET", "POST"})
     * @Template()
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function newAction(Request $request)
    {

        $entity = new Role();
        $form = $this->createForm(new RoleType(), $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirectToRoute('panel_role_edit', array('id' => $entity->getId()));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a Role entity.
     *
     * @Route("/{id}", name="panel_role_show", options={"expose"=true})
     * @FrontendAccessible(adminAccessible=true)
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SmartCityUserBundle:Role')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Role entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing role entity.
     *
     * @Route("/{id}/edit", name="panel_role_edit", options={"expose"=true})
     * @FrontendAccessible(adminAccessible=true)
     * @Method({"GET", "POST"})
     * @Template()
     * 
     * @param Request $request
     * @param Role $role
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function editAction(Request $request, Role $entity)
    {
        $deleteForm = $this->createDeleteForm($entity->getId());
        $editForm = $this->createForm(new RoleType(), $entity);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirectToRoute('panel_role_edit', array('id' => $entity->getId()));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    
    /**
     * Deletes a Role entity.
     *
     * @Route("/{id}", name="panel_role_delete")
     * @FrontendAccessible(adminAccessible=true)
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SmartCityUserBundle:Role')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Role entity.');
            }

            // $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('panel_role_index'));
    }

    /**
     * Creates a form to delete a Role entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('panel_role_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
