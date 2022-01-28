<?php

namespace redcastle\courses;

/**
 * Gets the course title.
 *
 * @param      array  $atts {
 *   @type  int  $post_id  The Course ID.
 *   @type  int  $class_id The Class ID.
 * }
 *
 * @return     string  The course title.
 */
function get_course_title( $atts ){
  $args = shortcode_atts([
    'post_id'   => null,
    'class_id'  => null,
    'tag'       => 'h1',
  ], $atts );

  if( is_null( $args['post_id'] ) && is_null( $args['class_id'] ) )
    return 'No Post/Class ID!';

  if( $args['class_id'] ){
    $course = get_post_meta( $args['class_id'], 'course', true );
    if( ! is_object( $course ) )
      $course = get_post( $course );
    $title = $course->post_title;
  } else {
    $title = get_the_title( $args['post_id'] );
  }

  return '<' . $args['tag'] . '>' . $title . '</' . $args['tag'] . '>';
}
add_shortcode( 'get_course_title', __NAMESPACE__ . '\\get_course_title' );