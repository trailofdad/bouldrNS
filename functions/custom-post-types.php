<?php

    $labels = array(
      'name'               => 'Problems',
      'singular_name'      => 'Problem',
      'menu_name'          => 'Problems',
      'name_admin_bar'     => 'Problem',
      'add_new'            => _x('Add New', 'problem'),
      'add_new_item'       => 'Add New Problem',
      'new_item'           => 'New Problem',
      'edit_item'          => 'Edit Problem',
      'view_item'          => 'View Problem',
      'all_items'          => 'All Problems',
      'search_items'       => 'Search Problems',
      'parent_item_colon'  => 'Parent Problem:',
      'not_found'          => 'No problems found.',
      'not_found_in_trash' => 'No problems found in Trash.'
    );

    $args = array(
      'labels'             => $labels,
      'description'        => 'Holds information on problems',
      'public'             => true,
      'query_var'          => true,
      'rewrite'            => array( 'slug' => 'problem' ),
      'capability_type'    => 'post',
      'hierarchical'       => false,
      'supports'           => array( 'title', 'editor', 'custom-fields', 'comments', 'revisions', 'thumbnail' ),
      'taxonomies'		 		 =>	array('area'),
      'delete_with_user'   => false,
      'show_in_rest'       => true,
      'menu_position'      => 1,
      'menu_icon'          => 'dashicons-location'
    );

    register_post_type( 'bouldrns_problem', $args );