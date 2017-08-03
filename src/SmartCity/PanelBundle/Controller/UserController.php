<?php

namespace SmartCity\PanelBundle\Controller;

use SmartCity\UserBundle\Annotation\FrontendAccessible;
use SmartCity\UserBundle\Entity\Repository\UserRepository;
use SmartCity\UserBundle\Entity\User;
use SmartCity\CourseBundle\Entity\Course;
use SmartCity\UserBundle\Entity\Constants\UserConstants;
use SmartCity\CoreBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
// use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use SmartCity\UserBundle\Form\Type\UserType;
use Symfony\Component\HttpFoundation\JsonResponse;


/**
 * User controller.
 *
 * @Route("/")
 */
class UserController extends BaseController
{
    /**
     * User listing.
     *
     * @Route("/", name="panel_user_index")
     * @FrontendAccessible(adminAccessible=true)
     * @Method("GET")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        $datatable = $this->get('SmartCity.user.datatable');
        $datatable->buildDatatable();

        return array(
            'datatable' => $datatable,
        );
    }

/**
     * Get all User entities.
     *
     * @Route("/results", name="panel_user_results")
     * @FrontendAccessible(adminAccessible=true)
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexResultsAction()
    {
        $userDatatable = $this->get("SmartCity.user.datatable");
        $userDatatable->buildDatatable();

        $datatable = $this->get("sg_datatables.query")->getQueryFrom($userDatatable);
        return $datatable->getResponse();
    }

    /**
     * Creates a new User entity.
     *
     * @Route("/new", name="panel_user_new", options={"expose"=true})
     * @FrontendAccessible(adminAccessible=true)
     * @Template()
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {

        $user = new User();
        $form = $this->createForm(new UserType(), $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $userModel = $em->getRepository('SmartCityUserBundle:User');

            // $username = $form->getData()->getUsername();
            $email = $form->getData()->getEmail();
            $cellphone = $form->getData()->getCellphone();
            $nationalCode = $form->getData()->getNationalCode();

            $isExist = $this->get('SmartCity.user.service')->checkExistence($email, $cellphone, $nationalCode);
            
            if(!$isExist['status']){
                return $this->errorJson($isExist['message']);
            }

            $altGeorgianDate = $form->getData()->getJalaliBirthday();
            $jalali = new \DateTime(date("c", intval(strtotime($altGeorgianDate))));

            $user->setUsername(uniqid());
            $user->setBirthday($jalali);

            $em->persist($user);
            $em->flush();

            return $this->success(array(
                'id' => $user->getId()
            ));

        }

        return array(
            'user' => $user,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a User entity.
     *
     * @Route("/{id}", name="panel_user_show", options={"expose"=true})
     * @FrontendAccessible(adminAccessible=true)
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SmartCityUserBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        return array(
            'user' => $entity,
        );
    }

    /**
     * Edits an existing User entity.
     *
     * @Route("/{id}/edit", name="panel_user_edit", options={"expose"=true})
     * @FrontendAccessible(adminAccessible=true)
     * @Template()
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, $id)
    {
        
        $em = $this->getDoctrine()->getManager();
        $userModel = $em->getRepository('SmartCityUserBundle:User');
        $user = $userModel->find($id);

        $current_email = $user->getEmail();
        $current_cellphone = $user->getCellphone();
        $current_nationalCode = $user->getNationalCode();

        $editForm = $this->createForm(new UserType(), $user);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $formData = $request->request->get('user');

            if($current_email != $formData['email']){
                if ($userModel->isEmailExists($formData['email'])) {
                    return $this->errorJson($this->container->get('translator')->trans('label.email_exist', array(), 'labels'));
                }    
            }

            $phoneService = $this->get('SmartCity.phone.service');
            $current_cellphone = $phoneService->normalize($current_cellphone->getNationalNumber());
            $cellphone = $phoneService->normalize($formData['cellphone']);
            if($current_cellphone != $cellphone){
                if ($userModel->isCellphoneExist($cellphone)) {
                    return $this->errorJson($this->container->get('translator')->trans('label.cellphone_exist', array(), 'labels'));
                }
            }

            if($current_nationalCode != $formData['nationalCode']){
                if ($userModel->isNationalCodeExist($formData['nationalCode'])) {
                    return $this->errorJson($this->container->get('translator')->trans('label.nationalCode_exist', array(), 'labels'));
                }
            }
        
            $altGeorgianDate = $formData['jalaliBirthday'];
            $jalali = new \DateTime(date("c", intval(strtotime($altGeorgianDate))));
            $user->setBirthday($jalali);

            $em->persist($user);
            $em->flush();
            
            return $this->success(array(
                'id' => $user->getId()
            ));
        }

        return array(
            'user' => $user,
            'edit_form' => $editForm->createView(),
        );
    }


    /**
     * Edits roles of an existing User entity.
     *
     * @Route("/{id}/role/edit", name="panel_user_role_edit", options={"expose"=true})
     * @FrontendAccessible(adminAccessible=true)
     * @Template()
     */
    public function roleEditAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('SmartCityUserBundle:User')->find($id);

        if (!$user) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $edit_form = $this->createRoleForm($user);

        if ($request->getMethod() == "PUT") {

            $edit_form->handleRequest($request);

            if ($edit_form->isValid()) {
                $em->flush();
            }

        }
        return array(
            'edit_form' => $edit_form->createView(),
        );
    }

    /**
     * Creates a form to edit roles of an User entity.
     *
     * @param User $user
     * @return \Symfony\Component\Form\Form The form
     * @internal param User $entity The entity
     *
     */
    private function createRoleForm(User $user)
    {

        $form = $this->createFormBuilder($user)
            ->setAction($this->generateUrl('panel_user_role_edit', array('id' => $user->getId())))
            ->setMethod('PUT')
            // ->add('type', 'choice', array(
            //         'choices' => UserConstants::$user_types,
            //         'label' => 'label.type',
            //         'translation_domain' => 'labels',
            //         'attr' => array(
            //             'class' => 'form-control'
            //         )
            //     )
            // )
            ->add('roles', null, array(
                    'label' => 'label.roles',
                    'translation_domain' => 'labels',
                    'property' => 'title',
                    'expanded' => true,
                    'multiple' => true
                )
            )
            ->add('status', 'choice', array(
                    'choices' => UserConstants::$user_statuses,                
                    'label' => 'label.user.status.label',
                    'translation_domain' => 'labels',
                    'attr' => array(
                        'class' => 'form-control'
                    )
                )
            )
            ->getForm();

        return $form;
    }

    /**
     * Query users to find a user ID by username , fullname or email
     *
     * AJAX Request
     * JSON Response
     *
     * @Route("/find/{_query}", name="panel_user_find", options={"expose"=true})
     * @FrontendAccessible(adminAccessible=true)
     * @Method("GET")
     */
    public function findUsersAction(Request $request, $_query)
    {
        if($_query == '_query'){
            $_query = $request->query->get('q');  // for select2 plugin
        }
        $em = $this->getDoctrine()->getManager();

        $userModel = $em->getRepository('SmartCityUserBundle:User');
        $users = $userModel->searchForUser($_query);

        return new JsonResponse($users);
    }

    /**
     * Query users to find a user ID by username , fullname or email
     *
     * AJAX Request
     * JSON Response
     *
     * @Route("/check-existence", name="panel_user_check_existence", options={"expose"=true})
     * @FrontendAccessible(adminAccessible=true)
     * @Method("POST")
     */
    public function checkExistence(Request $request){
        $em = $this->getDoctrine()->getManager();
        $userModel = $em->getRepository('SmartCityUserBundle:User');

        // $username = $request->query->get('username');
        // if ($userModel->isUsernameExists($username)) {
                // return new JsonResponse(array(
                //     'status' => false,
                //     'message' => $this->get('translator')->trans('label.username_exist', array(), 'labels')
                // ));
        // }

        $email = $request->query->get('email');
        if ($userModel->isEmailExists($email)) {
            return new JsonResponse(array(
                'status' => false,
                'message' => $this->get('translator')->trans('label.email_exist', array(), 'labels')
            ));
        }

        $cellphone = $request->query->get('cellphone');
        $phoneService = $this->get('SmartCity.phone.service');
        $standard_phone = $phoneService->normalize($cellphone->getNationalNumber());
        if ($userModel->isCellphoneExist($standard_phone)) {
            return new JsonResponse(array(
                'status' => false,
                'message' => $this->get('translator')->trans('label.cellphone_exist', array(), 'labels') 
            ));
        }

        $nationalCode = $request->query->get('nationalCode');
        if ($userModel->isNationalCodeExist($nationalCode)) {
            return new JsonResponse(array(
                'status' => false,
                'message' => $this->get('translator')->trans('label.nationalCode_exist', array(), 'labels') 
            ));
        }
    }

}
