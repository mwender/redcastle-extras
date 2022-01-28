# Red Castle Extras #
**Contributors:** [thewebist](https://profiles.wordpress.org/thewebist/)  
**Tags:** enhancers, elementor, shortcodes  
**Requires at least:** 4.5  
**Tested up to:** 5.9  
**Requires PHP:** 7.4  
**Stable tag:** 1.2.0  
**License:** GPLv2 or later  
**License URI:** https://www.gnu.org/licenses/gpl-2.0.html  

Enhancers for the Red Castle website.

## Description ##

The plugin adds various enhancements to the Red Castle website.

## Shortcodes ##

Various custom shortcodes:

### `acf_date` Shortcode ###

Display ACF Date fields with `[acf_date/]`.

```
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
```

### `acf_classtime` Shortcode ###

Display the class times for a class.

```
/**
 * Displays the time for a class.
 *
 * @param      array  $atts {
 *   @type  int  $post_id The post ID for the class.
 * }
 *
 * @return     string  The class time.
 */
```

### `class_calendar` Shortcode ###

Display a calendar of upcoming classes.

```
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
```

### `get_course_title` Shortcode ###

Retrieve the title of a course with `[get_course_title post_id="" /]`.

```
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
```

## Changelog ##

### 1.2.0 ###
* Adding `[get_course_title]` shortcode.

### 1.1.0 ###
* Querying `[class_calendar]` classes by `start_date`.
* Adding `title_format` attribute option to `[class_calendar]`,

### 1.0.1 ###
* Checking for `composer.json` before adding Composer warning.

### 1.0.0 ###
* Initial release.
