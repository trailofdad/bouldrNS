<?php

class GASTON_ROUTE extends WP_REST_Controller {
  // Endpoints
  public function register_routes() {
    $namespace = 'gaston/v1';
    $base = 'climbs';
    $taxonomy = 'areas';

    // Get all climbs
    register_rest_route( $namespace, '/' . $base,
      array(
        'methods' => WP_REST_Server::READABLE,
        'callback' => array( $this, 'get_climbs')
      )
    );

    // Get single climb
    register_rest_route( $namespace, '/' . $base . '/(?P<id>\d+)',
      array(
        'methods' => WP_REST_Server::READABLE,
        'callback' => array( $this, 'get_single_climb')
      )
    );

    // Get climbs from area
    register_rest_route( $namespace, '/' . $taxonomy . '/(?P<id>[\d]+)',
      array(
        'methods' => WP_REST_Server::READABLE,
        'callback' => array( $this, 'get_single_area')
      )
    );
  }
  // Endpoints

  // Get all climbs
  public function get_climbs() {
    $args = array (
      'post_type' => array( 'gaston_climb' ),
      'posts_per_page' => -1,
      'order' => 'ASC',
      'orderby' => 'title',
      'post_status' => 'publish'
    );

    $query = new WP_Query( $args );
    $posts = $query->posts;
    $formatted_posts = array();

    foreach ($posts as $post) {
      $temp_climb = array(
        'climb_name' => $post->post_title,
        'climb_grade' => get_post_meta($post->ID, 'grade', true),
        'climb_description' => $post->post_content,
        'climb_rating' => get_post_meta($post->ID, 'rating', true),
        'last_modified' => $post->post_modified
      );

      array_push($formatted_posts, $temp_climb);
    }

    // TODO: massage data & append post meta

    return $formatted_posts;
  }

  public function get_single_climb( WP_REST_Request $request ) {
    $id = $request->get_param('id');
    $post = get_post($id);

    // TODO: massage data & append post meta

    return $post;
  }

  public function get_single_area( WP_REST_Request $request ) {
    $id = $request->get_param('id');

    wp_reset_query();

    $args = array(
        'post_type' => 'gaston_climb',
        'tax_query' => array(
            array(
                'taxonomy' => 'area',
                'field' => 'term_id',
                'terms' => $id,
            ),
        ),
     );

     $query = new WP_Query($args);

    // TODO: massage data & append post meta

    return $query->posts;
  }

}