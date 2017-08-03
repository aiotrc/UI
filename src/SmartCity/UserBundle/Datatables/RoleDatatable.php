<?php

namespace SmartCity\UserBundle\Datatables;

use SmartCity\UserBundle\Entity\Constants\UserConstants;
use Sg\DatatablesBundle\Datatable\View\AbstractDatatableView;
use Sg\DatatablesBundle\Datatable\View\Style;

/**
 * Class RoleDatatable
 *
 * @package SmartCity\UserBundle\Datatables
 */
class RoleDatatable extends AbstractDatatableView
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
            'url' => $this->router->generate('panel_role_results'),
            'type' => 'GET'
        ));

        $this->options->set(array(
            'class' => Style::BOOTSTRAP_3_STYLE . ' table-condensed text-center',
            'display_start' => -1,
            'dom' => 'lfrtip', // default, but not used because 'use_integration_options' = true
            'length_menu' => array(10, 25, 50, 100),
            'order_classes' => true,
            'order' => array(array(0, 'asc')),
            'order_multi' => true,
            'page_length' => 50,
            'paging_type' => Style::FULL_NUMBERS_PAGINATION,
            'renderer' => '', // default, but not used because 'use_integration_options' = true
            'scroll_collapse' => false,
            'search_delay' => 4,
            'state_duration' => 7200,
            'stripe_classes' => array(),
            'individual_filtering' => false,
            'individual_filtering_position' => 'head',
            'use_integration_options' => true,
            'force_dom' => true
        ));

        $this->columnBuilder
            ->add('id', 'column', array(
                'title' => $this->translator->trans('label.id', array(), 'labels'),
                'class' => 'text-right'
            ))
            ->add('title', 'column', array(
                'title' => $this->translator->trans('label.role.title', array(), 'labels'),
                'class' => 'text-right'
            ))
            ->add('role', 'column', array(
                'title' => $this->translator->trans('label.role.code', array(), 'labels'),
                'class' => 'text-right'
            ))
            ->add('visible', 'column', array(
                'title' => $this->translator->trans('label.visible', array(), 'labels'),
            ))
            ->add(null, 'action', array(
                'title' => $this->translator->trans('label.actions', array(), 'labels'),
                'width' => '80px',
                'actions' => array(
                    array(
                        'route' => 'panel_role_edit',
                        'route_parameters' => array(
                            'id' => 'id'
                        ),
                        'icon' => 'fa fa-pencil',
                        'attributes' => array(
                            'rel' => 'tooltip',
                            'title' => $this->translator->trans('button.edit', array(), 'buttons'),
                            'class' => 'btn btn-xs purple',
                            'role' => 'button'
                        ),
                    )
                ),
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getLineFormatter()
    {
        $formatter = function ($line) {

            if ($line['visible']) {
                $line['visible'] = '<i class="fa fa-check" style="color:green"></i>';
            }
            else{
                $line['visible'] = '-';
            }

            return $line;
        };

        return $formatter;
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        return 'SmartCity\UserBundle\Entity\Role';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'role_datatable';
    }
}
