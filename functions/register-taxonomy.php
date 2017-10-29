<?php

$problem_cpt = array('gaston_problem');

$area_labels = array(
    'name'              => _x( 'Area', 'taxonomy general name', 'gaston' ),
    'singular_name'     => _x( 'Area', 'taxonomy singular name', 'gaston' ),
    'search_items'      => __( 'Search Areas', 'gaston' ),
    'all_items'         => __( 'All Areas', 'gaston' ),
    'parent_item'       => __( 'Parent Area', 'gaston' ),
    'parent_item_colon' => __( 'Parent Area:', 'gaston' ),
    'edit_item'         => __( 'Edit Area', 'gaston' ),
    'update_item'       => __( 'Update Area', 'gaston' ),
    'add_new_item'      => __( 'Add New Area', 'gaston' ),
    'new_item_name'     => __( 'New Area Name', 'gaston' ),
    'menu_name'         => __( 'Areas', 'gaston' )
);

$area_args = array(
    'hierarchical'          => true,
    'labels'                => $area_labels,
    'show_ui'               => true,
    'show_admin_column'     => true
);

register_taxonomy( 'area', $problem_cpt, $area_args);