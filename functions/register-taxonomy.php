<?php

$problem_cpt = array('bouldrns_problem');

$area_labels = array(
    'name'              => _x( 'Area', 'taxonomy general name', 'bouldrns' ),
    'singular_name'     => _x( 'Area', 'taxonomy singular name', 'bouldrns' ),
    'search_items'      => __( 'Search Areas', 'bouldrns' ),
    'all_items'         => __( 'All Areas', 'bouldrns' ),
    'parent_item'       => __( 'Parent Area', 'bouldrns' ),
    'parent_item_colon' => __( 'Parent Area:', 'bouldrns' ),
    'edit_item'         => __( 'Edit Area', 'bouldrns' ),
    'update_item'       => __( 'Update Area', 'bouldrns' ),
    'add_new_item'      => __( 'Add New Area', 'bouldrns' ),
    'new_item_name'     => __( 'New Area Name', 'bouldrns' ),
    'menu_name'         => __( 'Areas', 'bouldrns' )
);

$area_args = array(
    'hierarchical'          => true,
    'labels'                => $area_labels,
    'show_ui'               => true,
    'show_admin_column'     => true
);

register_taxonomy( 'area', $problem_cpt, $area_args);