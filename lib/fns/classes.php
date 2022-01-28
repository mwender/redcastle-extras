<?php

namespace redcastle\classes;

/**
 * Auto-populate the post title with ACF field values.
 *
 * @param      mixed   $value    The value
 * @param      int     $post_id  The post ID
 * @param      string  $field    The field
 *
 * @return     string  The post title.
 */
function update_postdata( $value, $post_id, $field ) {

  $start_date = get_field( 'start_date', $post_id );
  $start_date_object = new \DateTime( $start_date );

  $start_time = get_field( 'start_time', $post_id );
  $end_time = get_field( 'end_time', $post_id );

  $course = get_field( 'course', $post_id );
  $course_name = $course->post_title;

  if( stristr( $start_time, 'am' ) && stristr( $end_time, 'am' ) ){
    $start_time = str_replace( ' am', '', $start_time );
  } else if ( stristr( $start_time, 'pm' ) && stristr( $end_time, 'pm' ) ){
    $start_time = str_replace( ' pm', '', $start_time );
  }

  $title = $start_date_object->format( 'D, M j, Y' ) . ' ('. $start_time .' - ' . $end_time . ') - '. $course_name;

  $slug = sanitize_title( $title );

  $postdata = array(
    'ID'          => $post_id,
    'post_title'  => $title,
    'post_type'   => 'class',
    'post_name'   => $slug,
  );

  wp_update_post( $postdata );

  return $value;

}
add_filter('acf/update_value/name=start_date', __NAMESPACE__ . '\\update_postdata', 10, 3);
add_filter('acf/update_value/name=start_time', __NAMESPACE__ . '\\update_postdata', 10, 3);
add_filter('acf/update_value/name=end_time', __NAMESPACE__ . '\\update_postdata', 10, 3);
add_filter('acf/update_value/name=course', __NAMESPACE__ . '\\update_postdata', 10, 3);

/**
 * Display a calender of classes
 *
 * @param      array  $atts {
 *   @type  bool   $remove_title TRUE removes the course title from the class title.
 *   @type  string $date_format  The PHP date format for the class date.
 *   @type  string $title_format Specify the format of a class's title. Current options are "fulltitle", "dateonly", or empty for the default.
 * }
 *
 * @return     string  The HTML for the calendar of classes.
 */
function class_calendar( $atts ){
  $args = shortcode_atts([
    'remove_title' => true,
    'date_format'  => 'F j, Y',
    'title_format' => null,
  ], $atts );

   if ( $args['remove_title'] === 'false' ) $args['remove_title'] = false; // just to be sure...
  $args['remove_title'] = (bool) $args['remove_title'];

  $date_now = date( 'Y-m-d H:i:s' );

  $query_args = [
    'post_type'   => 'class',
    'order'       => 'ASC',
    'meta_query'  => [
      [
        'key' => 'start_date',
        'compare' => '>=',
        'value'   => $date_now,
        'type'    => 'DATE',
      ],
    ],
  ];
  $classes = new \WP_Query( $query_args );
  if( $classes ){
    $html = [ '<ul>' ];

    while( $classes->have_posts() ):
      $classes->the_post();
      $post_id = get_the_ID();

      $start_date = get_field( 'start_date', $post_id );
      $start_date_object = new \DateTime( $start_date );

      $start_time = get_field( 'start_time', $post_id );
      $end_time = get_field( 'end_time', $post_id );

      $course = get_field( 'course', $post_id );
      $course_name = $course->post_title;

      if( stristr( $start_time, 'am' ) && stristr( $end_time, 'am' ) ){
        $start_time = str_replace( ' am', '', $start_time );
      } else if ( stristr( $start_time, 'pm' ) && stristr( $end_time, 'pm' ) ){
        $start_time = str_replace( ' pm', '', $start_time );
      }

      switch ( $args['title_format'] ) {
        case 'fulltitle':
          $class_title = get_the_title();
          break;

        case 'dateonly':
          $class_title = $start_date_object->format( $args['date_format'] );
          break;

        default:
          $class_title = $start_date_object->format( $args['date_format'] ) . ' <span>('. $start_time .' - ' . $end_time . ')</span>';
          break;
      }
      $html[] = '<li>' . $class_title . '</li>';

    endwhile;

    $html[] = '</ul>';
  }
  wp_reset_postdata();
  //return '<p>testing</p>';
  return implode( "\n", $html );
}
add_shortcode( 'class_calendar', __NAMESPACE__ . '\\class_calendar' );
