<?php

class BOULDRNS_ROUTE extends WP_REST_Controller {
  // Endpoints
  public function register_routes() {
    $namespace = 'bouldrns/v1';
    $base = 'problems';
    $taxonomy = 'areas';

    // Get all problems
    register_rest_route( $namespace, '/' . $base,
      array(
        'methods' => WP_REST_Server::READABLE,
        'callback' => array( $this, 'get_problems')
      )
    );

    // Get single Problem
    register_rest_route( $namespace, '/' . $base . '/(?P<id>\d+)',
      array(
        'methods' => WP_REST_Server::READABLE,
        'callback' => array( $this, 'get_single_problem')
      )
    );

    // Get problems from area
    register_rest_route( $namespace, '/' . $taxonomy . '/(?P<id>[\d]+)',
      array(
        'methods' => WP_REST_Server::READABLE,
        'callback' => array( $this, 'get_single_area')
      )
    );
  }
  // Endpoints

  // Get all Problems
  public function get_problems() {
    $args = array (
      'post_type' => array( 'bouldrns_problem' ),
      'posts_per_page' => -1,
      'order' => 'ASC',
      'orderby' => 'title',
      'post_status' => 'publish'
    );

    $query = new WP_Query( $args );
    $posts = $query->posts;
    $formatted_posts = array();

    foreach ($posts as $post) {
      $temp_problem = array(
        'problem_name' => $post->post_title,
        'problem_grade' => get_post_meta($post->ID, 'grade', true),
        'problem_description' => $post->post_content,
        'problem_rating' => get_post_meta($post->ID, 'rating', true),
        'last_modified' => $post->post_modified
      );

      array_push($formatted_posts, $temp_problem);
    }

    // TODO: massage data & append post meta

    return $formatted_posts;
  }

  public function get_single_problem( WP_REST_Request $request ) {
    $id = $request->get_param('id');
    $post = get_post($id);

    // TODO: massage data & append post meta

    return $post;
  }

  public function get_single_area( WP_REST_Request $request ) {
    $id = $request->get_param('id');

    wp_reset_query();

    $args = array(
        'post_type' => 'bouldrns_problem',
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