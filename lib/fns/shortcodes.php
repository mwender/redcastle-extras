<?php

namespace redcastle\shortcodes;

/**
 * Returns content related to the next session of a course.
 *
 * @param      array  $atts {
 *   @type  int     $post_id       The Post ID of the Course.
 *   @type  string  $date_format   The format of the date to be returned. Default `l, F j, Y`.
 *   @type  string  $field         The name of the ACF field we are returning. Default `start_date`.
 *   @type  bool    $format_value  Whether or not to format the date value when calling `get_field()`. Default `true`.
 *   @type  string  $return_content  The content we're returning (i.e. "link" or "date"). Default "date".
 * }
 *
 * @return     string  Session start string.
 */
function next_session_start( $atts ){
  $args = shortcode_atts([
    'post_id'         => null,
    'date_format'     => 'l, F j, Y',
    'field'           => 'start_date',
    'format_value'    => true,
    'return_content'  => 'date',
  ], $atts );

  $next_session_start_string = '';


  if( is_null( $args['post_id'] ) ){
    global $post;
    $args['post_id'] = $post->ID;
  }
  if( ! is_numeric( $args['post_id'] ) )
    return '<p>ERROR: Please provide a numeric Post ID if you are specifying the <code>post_id</code> attribute for this shortcode.</p>';

  $date_now = date( 'Y-m-d H:i:s' );
  $query_args = [
    'post_type'       => 'class',
    'order'           => 'ASC',
    'posts_per_page'  => 1,
    'meta_query'      => [
      [
        'key' => 'start_date',
        'compare' => '>=',
        'value'   => $date_now,
        'type'    => 'DATE',
      ],
      [
        'key'     => 'course',
        'compare' => '=',
        'value'   => $args['post_id'],
        'type'    => 'NUMERIC',
      ]
    ],
  ];
  $classes = new \WP_Query( $query_args );
  if( $classes->have_posts() ){
    while ( $classes->have_posts() ) :
      $classes->the_post();
      $next_session_post_id = get_the_ID();
      $permalink = get_permalink( $next_session_post_id );
    endwhile;

    switch ( $args['return_content'] ) {
      case 'link':
        $return_content = $permalink;
        break;

      default:
        $acf_date = get_field( $args['field'], $next_session_post_id, $args['format_value'] );
        $date = \DateTime::createFromFormat( 'm/d/Y', $acf_date );
        $return_content = $date->format( $args['date_format'] );
        break;
    }
  } else {
    $return_content = 'No classes found!';
  }
  wp_reset_postdata();

  return $return_content;
}
add_shortcode( 'next_session_start', __NAMESPACE__ . '\\next_session_start' );