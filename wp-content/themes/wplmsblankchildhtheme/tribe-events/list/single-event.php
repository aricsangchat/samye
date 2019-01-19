<?php
/**
 * List View Single Event
 * This file contains one event in the list view
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe-events/list/single-event.php
 *
 * @version 4.6.19
 *
 */
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

// Setup an array of venue details for use later in the template
$venue_details = tribe_get_venue_details();

// The address string via tribe_get_venue_details will often be populated even when there's
// no address, so let's get the address string on its own for a couple of checks below.
$venue_address = tribe_get_address();

// Venue
$has_venue_address = ( ! empty( $venue_details['address'] ) ) ? ' location' : '';

// Organizer
$organizer = tribe_get_organizer();

$event_year        = tribe_get_start_date( $post, false, 'Y' );
$event_month       = tribe_get_start_date( $post, false, 'M' );
$event_day       = tribe_get_start_date( $post, false, 'd' );

?>

<div class="event-item-wrapper">
	<div class="row">
		<div class="col-xs-12 col-md-4 col-lg-3">
			<div class="event-image" style="background-image: url(<?php the_post_thumbnail_url( 'full') ?>)"><div class="date"><p class="month"><?php echo $event_month; ?> <?php echo $event_day; ?></p><p class="year"><?php echo $event_year; ?></p></div></div>
		</div>
		<div class="col-xs-12 col-md-8 col-lg-9">
			<div class="event-info">
				<p class="category"><?php echo get_the_tag_list('',' | '); ?></p>
				<h2 class="event-title"><a href="<?php echo esc_url( tribe_get_event_link() ); ?>"><?php the_title(); ?></a></h2>
				<p class="excerpt"><?php echo excerpt(40) ?></p>
				<p class="location"><span>Location: </span><?php
				$address_delimiter = empty( $venue_address ) ? ' ' : ', ';

				// These details are already escaped in various ways earlier in the process.
				echo implode( $address_delimiter, $venue_details );
			?></p>
				<p class="time"><span>Time: </span><?php echo tribe_events_event_schedule_details() ?></p>
				<a class="primary-btn" href="<?php echo esc_url( tribe_get_event_link() ); ?>">Find Out More</a>
			</div>
		</div>
	</div>
</div>
<?php
do_action( 'tribe_events_after_the_content' );