<?php

namespace SmartCity\UserBundle\Datatables;

use Sg\DatatablesBundle\Datatable\View\AbstractDatatableView;
use Sg\DatatablesBundle\Datatable\View\Style;

/**
 * Class ActionGroupDatatable
 *
 * @package SmartCity\UserBundle\Datatables
 */
class ActionGroupDatatable extends AbstractDatatableView
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
            'url' => $this->router->generate('panel_action_group_results'),
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
            'individual_filtering' => false,
            'individual_filtering_position' => 'head',
            'use_integration_options' => true,
            'dom' => "<'row'<'col-sm-6'l><'col-sm-6'f>>" .
                    "<'row'<'col-sm-12'tr>>" .
                    "<'row'<'col-sm-6'i><'col-sm-6'p>>",
            'force_dom' => true,
        ));

        $this->columnBuilder
            ->add('id', 'column', array(
                'title' => $this->translator->trans('label.id', array(), 'labels'),
            ))
            ->add('title', 'column', array(
                'title' => $this->translator->trans('label.title', array(), 'labels'),
            ))
            ->add('code', 'column', array(
                'title' => $this->translator->trans('label.actiongroup.code', array(), 'labels'),
            ))
            ->add('visible', 'column', array(
                'title' => $this->translator->trans('label.visible', array(), 'labels'),
            ))
            ->add(null, 'action', array(
                'title' => $this->translator->trans('label.edit', array(), 'labels'),
                'actions' => array(
                    array(
                        'route' => 'panel_action_group_edit',
                        'route_parameters' => array(
                            'id' => 'id'
                        ),
                        'icon' => 'fa fa-pencil',
                        'attributes' => array(
                            'rel' => 'tooltip',
                            'title' =>  $this->translator->trans('label.edit', array(), 'labels'),
                            'class' => 'btn btn-sm blue',
                            'role' => 'button'
                        ),
                    )
                )
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
        return 'SmartCity\UserBundle\Entity\ActionGroup';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'actiongroup_datatable';
    }
}
