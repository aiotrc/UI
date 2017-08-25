<?php

namespace SmartCity\UserBundle\Datatables;

use SmartCity\UserBundle\Entity\Constants\UserConstants;
use Sg\DatatablesBundle\Datatable\View\AbstractDatatableView;
use Sg\DatatablesBundle\Datatable\View\Style;
use SymfonyPersia\JalaliDateBundle\lib\JalaliDateTime;

/**
 * Class UserDatatable
 *
 * @package SmartCity\UserBundle\Datatables
 */
class UserDatatable extends AbstractDatatableView
{

    /**
     * {@inheritdoc}
     */
    public function buildDatatable(array $options = array())
    {

        $this->features->set(array(
            'auto_width' => true,
            'defer_render' => true,
            'info' => true,
            'jquery_ui' => false,
            'length_change' => true,
            'ordering' => true,
            'paging' => true,
            'processing' => true,
            'scroll_x' => false,
            'scroll_y' => '',
            'searching' => true,
            'state_save' => false,
            'delay' => 0,
            'extensions' => array(),
        ));

        $this->ajax->set(array(
            'url' => $this->router->generate('panel_user_results'),
            'type' => 'GET'
        ));

        $this->options->set(array(
            'class' => Style::BOOTSTRAP_3_STYLE . ' table-condensed text-center',
            'display_start' => 0,
            'defer_loading' => -1,
            'length_menu' => array(10, 25, 50, 100),
            'order_classes' => true,
            'order' => array(array(0, 'desc')),
            'order_multi' => true,
            'page_length' => 50,
            'paging_type' => Style::FULL_NUMBERS_PAGINATION,
            'renderer' => '',
            'scroll_collapse' => false,
            'search_delay' => 4,
            'state_duration' => 7200,
            'stripe_classes' => array(),
            'individual_filtering' => true,
            'individual_filtering_position' => 'head',
            'use_integration_options' => true,
            'dom' => "<'row'<'col-sm-6'l><'col-sm-6'f>>" .
                    "<'row'<'col-sm-12'tr>>" .
                    "<'row'<'col-sm-6'i><'col-sm-6'p>>",
            'force_dom' => true,
        ));

        // $provinces = $this->em->getRepository('SmartCityGeoBundle:Province')->findAll();
        $roles = $this->em->getRepository('SmartCityUserBundle:Role')->findAll();

        // foreach ($roles as $role) {
        //     if ($role['role']) {
        //         # code...
        //     }
        // }

        foreach (UserConstants::$user_statuses as $key => $status) {
            $statuses[$key] = $this->translator->trans($status, array(), 'labels');
        }

        foreach (UserConstants::$user_sexes as $key => $sex) {
            $sexes[$key] = $this->translator->trans($sex, array(), 'labels');
        }

        $this->columnBuilder
            // ->add('id', 'column', array(
            //     'title' => $this->translator->trans('label.id', array(), 'labels'),
            //     'width' => '10px',
            //     'searchable' => false,
            // ))
            ->add('firstname', 'column', array(
                'title' => $this->translator->trans('label.user.firstname', array(), 'labels'),
                'width' => '80px',
                'visible' => false,
                'filter' => array('text', array(
                    'search_type' => 'like',
                    'class' => 'form-control input-sm',
                ))
            ))
            ->add('lastname', 'column', array(
                'title' => $this->translator->trans('label.user.lastname', array(), 'labels'),
                'width' => '80px',
                'visible' => false,
                'filter' => array('text', array(
                    'search_type' => 'like',
                    'class' => 'form-control input-sm',
                ))
            ))
            ->add('fullname', 'virtual', array(
                'title' => $this->translator->trans('label.user.fullname', array(), 'labels'),
                'class' => 'user_fullname',
                'width' => '100px',
                'filter' => array('text', array(
                    'search_type' => 'like',
                    'class' => 'form-control input-sm',
                ))
            ))
            // ->add('firstname', 'column', array('visible' => false))
            // ->add('lastname', 'column', array('visible' => false))
            ->add('cellphone', 'column', array(
                'title' => $this->translator->trans('label.cellphone', array(), 'labels'),
                'width' => '95px',
                'filter' => array('text', array(
                    'search_type' => 'like',
                    'class' => 'form-control input-sm',
                ))
            ))
            ->add('email', 'column', array(
                'title' => $this->translator->trans('label.email', array(), 'labels'),
                'width' => '100px',
                'filter' => array('text', array(
                    'search_type' => 'like',
                    'class' => 'form-control input-sm',
                ))
            ))
            ->add('status', 'column', array(
                'title' => $this->translator->trans('label.user.status.label', array(), 'labels'),
                'width' => '80px',
                'filter' => array('select', array(
                    'search_type' => 'eq',
                    'select_options' =>
                        ['' => $this->translator->trans('label.any', array(), 'labels')] +
                        $statuses,
                    'class' => 'form-control input-sm'
                )),
            ))

            // ->add('createdAt', 'column', array(
            //     'title' => $this->translator->trans('label.user.register_date', array(), 'labels'),
            //     'width' => '100px',
            //     'searchable' => false
            // ))
            ->add('roles.title', 'array', array(
                'title' => $this->translator->trans('label.role.label', array(), 'labels'),
                'width' => '100px',
                'data' => 'roles[, ].title',
                'filter' => array('select', array(
                    'search_type' => 'eq',
                    'select_options' =>
                        ['' => $this->translator->trans('label.any', array(), 'labels')] +
                        // $roles,
                        $this->getCollectionAsOptionsArray($roles, 'title', 'title'),
                    'class' => 'form-control input-sm'
                )),
            ))

            ->add('lastSeen', 'timeago', array(
                'title' => $this->translator->trans('label.user.lastseen', array(), 'labels'),
                'width' => '100px',
                'searchable' => false
            ))
            // ->add('provinces.title', 'array', array(
            //     'title' => $this->translator->trans('label.province.title', array(), 'labels'),
            //     'width' => '150px',
            //     'data' => 'provinces[, ].title',
            //     'filter' => array('select2', array(
            //         'select_options' => 
            //         // ['' => $this->translator->trans('label.any', array(), 'labels')] + 
            //         $this->getCollectionAsOptionsArray($provinces, 'title', 'title'),
            //         'search_type' => 'in',
            //         'multiple' => true,
            //         'placeholder' => $this->translator->trans('label.search', array(), 'labels'),
            //         'allow_clear' => false,
            //         'tags' => false,
            //         'language' => 'fa',
            //         // 'url' => 'select2_color',
            //         // 'delay' => 250,
            //         // 'cache' => true
            //         'class' => 'form-control input-sm'
            //     )),
            // ))

            ->add(null, 'action', array(
                'title' => $this->translator->trans('label.actions', array(), 'labels'),
                'width' => '80px',
                'actions' => array(
                    array(
                        'route' => 'panel_user_edit',
                        'route_parameters' => array(
                            'id' => 'id'
                        ),
                        'icon' => 'fa fa-pencil',
                        // 'label' => $this->translator->trans('label.edit', array(), 'labels'),
                        'attributes' => array(
                            'rel' => 'tooltip',
                            'title' => $this->translator->trans('button.edit', array(), 'buttons'),
                            'class' => 'btn btn-sm green',
                            'role' => 'button',
                            'target' => 'blank'
                        ),
                    ),
                    array(
                        'route' => 'panel_user_role_edit',
                        'route_parameters' => array(
                            'id' => 'id'
                        ),
                        'icon' => 'fa fa-lock',
                        'attributes' => array(
                            'rel' => 'tooltip',
                            'title' => $this->translator->trans('button.role.edit', array(), 'buttons'),
                            'class' => 'role_edit_button btn btn-sm blue',
                            'role' => 'button',
                        ),
                    ),
                )
            ));
    }


    /**
     * {@inheritdoc}
     */
    public function getLineFormatter()
    {
        $formatter = function ($line) {
            // dump($line);
            // die();
            $line["fullname"] = $line["firstname"] . " " . $line["lastname"];
            
            if($line['lastSeen'] !=''){
                $timeago = $line['lastSeen'];
                $defaultTimestamp = new \DateTime('1997-01-01 00:00:00');
                if ($timeago->getTimestamp() == $defaultTimestamp->getTimestamp()) {
                    $line['lastSeen'] = null;
                }    
            }
            

            if ($line['status'] != '') {
                $line['status'] = $this->translator->trans(UserConstants::$user_statuses[$line['status']], array(), 'labels');
            }

            // if ($line['sex'] != '') {
                // $line['sex'] = $this->translator->trans(UserConstants::$user_sexes[$line['sex']], array(), 'labels');
            // }

            // if ($line['roles'] != '') {
            //     $line['roles'] = $this->translator->trans(UserConstants::$user_roles[$line['roles'][0]['role']], array(), 'labels');
            // }

            if ($line['cellphone'] != '') {
                $line['cellphone'] = $line['cellphone']->getNationalNumber();
            }

            // $timeStamp = $line['createdAt']->getTimestamp();
            // $line['createdAt'] = JalaliDateTime::date('l j F H:i', $timeStamp);

            return $line;
        };
        return $formatter;
    }


    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        return 'SmartCity\UserBundle\Entity\User';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'user_datatable';
    }
}