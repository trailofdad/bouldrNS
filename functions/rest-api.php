<?php

class BOULDRNS_ROUTE extends WP_REST_Controller {
  // Endpoints
  public function register_routes() {
    $namespace = 'bouldrns/v1';
    $base = 'problems';

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

    // TODO: massage data & append post meta

    return $posts;
  }

  public function get_single_problem( WP_REST_Request $request ) {
    $id = $request->get_param('id');
    $post = get_post($id);

    // TODO: massage data & append post meta

    return $post;
  }

}