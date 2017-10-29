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
  'show_ui'            => true,
  'query_var'          => true,
  'rewrite'            => array( 'slug' => 'problem' ),
  'capability_type'    => 'post',
  'hierarchical'       => true,
  'supports'           => array( 'title', 'editor', 'custom-fields', 'comments', 'revisions', 'thumbnail' ),
  'taxonomies'		 		 =>	array('area'),
  'delete_with_user'   => false,
  'show_in_rest'       => true,
  'menu_position'      => 1,
  'menu_icon'          => 'dashicons-location',
  'register_meta_box_cb' => array( 'add_grade_metabox', 'add_rating_metabox' )
);

register_post_type( 'gaston_problem', $args );

function add_grade_metabox() {
  add_meta_box( 'gaston-grade', __( 'Grade', 'textdomain' ), 'gaston_grade_meta', 'gaston_problem' );
}

function add_rating_metabox() {
  add_meta_box( 'gaston-rating', __( 'Rating', 'textdomain' ), 'gaston_rating_meta', 'gaston_problem' );
}

add_action( 'add_meta_boxes', 'add_grade_metabox' );
add_action( 'add_meta_boxes', 'add_rating_metabox' );

function gaston_grade_meta() {
  global $post;

  //Nonce to verify the data
  echo '<input type="hidden" '
  . 'name="grade_meta_nonce" '
  . 'id="grade_meta_nonce" '
  . 'value = "' . wp_create_nonce(plugin_basename(__FILE__)) . '" />';

  //Get the grade data if it is already written
  $grade = get_post_meta($post->ID, 'grade', true);

  //Output the field

  echo '<input type = "text" name="grade" value="' .
  $grade . '" class = "property-grade" ';
}

function gaston_rating_meta() {
  global $post;

  //Nonce to verify the data
  echo '<input type="hidden" '
  . 'name="rating_meta_nonce" '
  . 'id="rating_meta_nonce" '
  . 'value = "' . wp_create_nonce(plugin_basename(__FILE__)) . '" />';

  //Get the rating data if it is already written
  $rating = get_post_meta($post->ID, 'rating', true);

  //Output the field

  echo '<input type = "text" name="rating" value="' .
  $rating . '" class = "property-rating" ';
}

add_action('save_post', 'gaston_save_rating_meta', 1, 2);
add_action('save_post', 'gaston_save_grade_meta', 1, 2);

function gaston_save_grade_meta($post_id, $post) {

  //Verify it came from proper authorization.
  if (!wp_verify_nonce($_POST['grade_meta_nonce'], plugin_basename(__FILE__))) {
    return $post->ID;
  }

  //Check if the current user can edit the post
  if (!current_user_can('edit_post', $post->ID)) {
    return $post->ID;
  }

  //Add values to custom fields
  $property_meta['grade'] = $_POST['grade'];

  //Add values to custom fields
  foreach ($property_meta as $key => $value) {
    if ($post->post_type == "revision")
      return;
    $value = implode(',', (array) $value);

    if (get_post_meta($post->ID, $key, FALSE)) {
      update_post_meta($post->ID, $key, $value);
    } else {
      add_post_meta($post->ID, $key, $value);
    }
    if (!$value) {
      delete_post_meta($post->ID, $key);
    }
  }
}

function gaston_save_rating_meta($post_id, $post) {

  //Verify it came from proper authorization.
  if (!wp_verify_nonce($_POST['rating_meta_nonce'], plugin_basename(__FILE__))) {
    return $post->ID;
  }

  //Check if the current user can edit the post
  if (!current_user_can('edit_post', $post->ID)) {
    return $post->ID;
  }

  //Add values to custom fields
  $property_meta['rating'] = $_POST['rating'];

  //Add values to custom fields
  foreach ($property_meta as $key => $value) {
    if ($post->post_type == "revision")
      return;
    $value = implode(',', (array) $value);

    if (get_post_meta($post->ID, $key, FALSE)) {
      update_post_meta($post->ID, $key, $value);
    } else {
      add_post_meta($post->ID, $key, $value);
    }
    if (!$value) {
      delete_post_meta($post->ID, $key);
    }
  }
}
