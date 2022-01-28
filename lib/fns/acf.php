<?php

namespace redcastle\acf;

/**
 * Displays an ACF Date field.
 *
 * @param      array  $atts {
 *   @type  string  $field        The name of the field.
 *   @type  int     $post_id      The ID of the post with the ACF Date field.
 *   @type  bool    $format_value TRUE returns the value with formatting.
 *   @type  string  $date_format  The format of the date we're returning.
 * }
 *
 * @return     string  The formatted date.
 */
function acf_date_shortcode( $atts ){
  $args = shortcode_atts( array(
    'field'     => '',
    'post_id'   => false,
    'format_value'  => true,
    'date_format'   => '',
  ), $atts );

  $acf_date = get_field( $args['field'], $args['post_id'], $args['format_value'] );
  uber_log('🔔 $acf_date = ' . $acf_date );
  $date = \DateTime::createFromFormat( 'm/d/Y', $acf_date );

  $value = $date->format( $args['date_format'] );
  uber_log('🔔 $date_format = ' . $args['date_format'] . "\n" . '🔔 $value = ' . $value );

  return $value;
}
add_shortcode( 'acf_date', __NAMESPACE__ . '\\acf_date_shortcode' );

/**
 * Displays the time for a class.
 *
 * @param      array  $atts {
 *   @type  int  $post_id The post ID for the class.
 * }
 *
 * @return     string  The class time.
 */
function acf_classtime_shortcode( $atts ){
  $args = shortcode_atts([
    'post_id' => false,
  ], $atts );

  if( ! $args['post_id'] )
    return 'No post_id!';

  $start_time = get_field( 'start_time', $args['post_id'] );
  $end_time = get_field( 'end_time', $args['post_id'] );

  if( stristr( $start_time, 'am' ) && stristr( $end_time, 'am' ) ){
    $start_time = str_replace( ' am', '', $start_time );
  } else if ( stristr( $start_time, 'pm' ) && stristr( $end_time, 'pm' ) ){
    $start_time = str_replace( ' pm', '', $start_time );
  }

  return $start_time . ' - ' . $end_time;
}
add_shortcode( 'acf_classtime', __NAMESPACE__ . '\\acf_classtime_shortcode' );