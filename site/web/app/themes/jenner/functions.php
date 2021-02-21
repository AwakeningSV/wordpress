<?php
/*
Author: Eddie Machado
URL: htp://themble.com/bones/

This is where you can drop your custom functions or
just edit things like thumbnail sizes, header images,
sidebars, comments, ect.
*/

/************* INCLUDE NEEDED FILES ***************/

/*
1. library/bones.php
	- head cleanup (remove rsd, uri links, junk css, ect)
	- enqueueing scripts & styles
	- theme support functions
	- custom menu output & fallbacks
	- related post function
	- page-navi function
	- removing <p> from around images
	- customizing the post excerpt
	- custom google+ integration
	- adding custom fields to user profiles
*/
require_once( 'library/bones.php' ); // if you remove this, bones will break
/*
2. library/custom-post-type.php
	- an example custom post type
	- example custom taxonomy (like categories)
	- example custom taxonomy (like tags)
*/
// require_once( 'library/custom-post-type.php' ); // you can disable this if you like
/*
3. library/admin.php
	- removing some default WordPress dashboard widgets
	- an example custom dashboard widget
	- adding custom login css
	- changing text in footer of admin
*/
// require_once( 'library/admin.php' ); // this comes turned off by default
/*
4. library/translation/translation.php
	- adding support for other languages
*/
// require_once( 'library/translation/translation.php' ); // this comes turned off by default

/************* THUMBNAIL SIZE OPTIONS *************/

// Thumbnail sizes
add_image_size( 'awakening-1952', 1952, 1152, true );
add_image_size( 'awakening-1024', 1024, 576, true );
add_image_size( 'awakening-976', 976, 550, true );
add_image_size( 'awakening-235', 235, 132, true );
//add_image_size( 'bones-thumb-600', 600, 150, true );
//add_image_size( 'bones-thumb-300', 300, 100, true );
/*
to add more sizes, simply copy a line from above
and change the dimensions & name. As long as you
upload a "featured image" as large as the biggest
set width or height, all the other sizes will be
auto-cropped.

To call a different size, simply change the text
inside the thumbnail function.

For example, to call the 300 x 300 sized image,
we would use the function:
<?php the_post_thumbnail( 'bones-thumb-300' ); ?>
for the 600 x 100 image:
<?php the_post_thumbnail( 'bones-thumb-600' ); ?>

You can change the names and dimensions to whatever
you like. Enjoy!
*/

/************* ACTIVE SIDEBARS ********************/

// Sidebars & Widgetizes Areas
function bones_register_sidebars() {
	register_sidebar(array(
		'id' => 'sidebar1',
		'name' => __( 'Sidebar 1', 'bonestheme' ),
		'description' => __( 'The first (primary) sidebar.', 'bonestheme' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="widgettitle">',
		'after_title' => '</h4>',
	));

	/*
	to add more sidebars or widgetized areas, just copy
	and edit the above sidebar code. In order to call
	your new sidebar just use the following code:

	Just change the name to whatever your new
	sidebar's id is, for example:

	register_sidebar(array(
		'id' => 'sidebar2',
		'name' => __( 'Sidebar 2', 'bonestheme' ),
		'description' => __( 'The second (secondary) sidebar.', 'bonestheme' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="widgettitle">',
		'after_title' => '</h4>',
	));

	To call the sidebar in your template, you can just copy
	the sidebar.php file and rename it to your sidebar's name.
	So using the above example, it would be:
	sidebar-sidebar2.php

	*/
} // don't remove this bracket!

/************* COMMENT LAYOUT *********************/

// Comment Layout
function bones_comments( $comment, $args, $depth ) {
   $GLOBALS['comment'] = $comment; ?>
	<li <?php comment_class(); ?>>
		<article id="comment-<?php comment_ID(); ?>" class="clearfix">
			<header class="comment-author vcard">
				<?php
				/*
					this is the new responsive optimized comment image. It used the new HTML5 data-attribute to display comment gravatars on larger screens only. What this means is that on larger posts, mobile sites don't have a ton of requests for comment images. This makes load time incredibly fast! If you'd like to change it back, just replace it with the regular wordpress gravatar call:
					echo get_avatar($comment,$size='32',$default='<path_to_url>' );
				*/
				?>
				<?php // custom gravatar call ?>
				<?php
					// create variable
					$bgauthemail = get_comment_author_email();
				?>
				<img data-gravatar="http://www.gravatar.com/avatar/<?php echo md5( $bgauthemail ); ?>?s=32" class="load-gravatar avatar avatar-48 photo" height="32" width="32" src="<?php echo get_template_directory_uri(); ?>/library/images/nothing.gif" />
				<?php // end custom gravatar call ?>
				<?php printf(__( '<cite class="fn">%s</cite>', 'bonestheme' ), get_comment_author_link()) ?>
				<time datetime="<?php echo comment_time('Y-m-j'); ?>"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>"><?php comment_time(__( 'F jS, Y', 'bonestheme' )); ?> </a></time>
				<?php edit_comment_link(__( '(Edit)', 'bonestheme' ),'  ','') ?>
			</header>
			<?php if ($comment->comment_approved == '0') : ?>
				<div class="alert alert-info">
					<p><?php _e( 'Your comment is awaiting moderation.', 'bonestheme' ) ?></p>
				</div>
			<?php endif; ?>
			<section class="comment_content clearfix">
				<?php comment_text() ?>
			</section>
			<?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
		</article>
	<?php // </li> is added by WordPress automatically ?>
<?php
} // don't remove this bracket!

/************* SEARCH FORM LAYOUT *****************/

// Search Form
function bones_wpsearch($form) {
	$form = '<form role="search" method="get" id="searchform" action="' . home_url( '/' ) . '" >
	<label class="screen-reader-text" for="s">' . __( 'Search for:', 'bonestheme' ) . '</label>
	<input type="text" value="' . get_search_query() . '" name="s" id="s" placeholder="' . esc_attr__( 'Search the Site...', 'bonestheme' ) . '" />
	<input type="submit" id="searchsubmit" value="' . esc_attr__( 'Search' ) .'" />
	</form>';
	return $form;
} // don't remove this bracket!

/****************** ZONINATOR *********************/

add_filter( 'zoninator_zone_max_lock_period', 'z_disable_zoninator_locks' );

if (z_get_zone('home') == '') {
    global $zoninator;
    $zoninator->insert_zone( 'home', 'Home', array('description' => 'Content for the homepage'));
}
if (z_get_zone('leaders-senior') == '') {
    global $zoninator;
    $zoninator->insert_zone( 'leaders-senior', 'Senior Leaders', array('description' => 'Senior Leadership'));
}
if (z_get_zone('leaders-staff') == '') {
    global $zoninator;
    $zoninator->insert_zone( 'leaders-staff', 'Staff Leaders', array('description' => 'Staff Leadership'));
}
if (z_get_zone('leaders-volunteer') == '') {
    global $zoninator;
    $zoninator->insert_zone( 'leaders-volunteer', 'Volunteer Leaders', array('description' => 'Volunteer Leadership'));
}
if (z_get_zone('leaders-intern') == '') {
    global $zoninator;
    $zoninator->insert_zone( 'leaders-intern', 'Intern Leaders and Proteges', array('description' => 'Intern Leadership'));
}
if (z_get_zone('upcoming-livestream') == '') {
    global $zoninator;
    $zoninator->insert_zone( 'upcoming-livestream', 'Upcoming Livestream', array('description' => 'Upcoming live teaching'));
}

function ac_add_zoninator_post_types() {
        $available_post_types = array_values(get_post_types(array('public' => true), 'names'));
        foreach ( $available_post_types as $post_type ) {
                add_post_type_support( $post_type, 'zoninator_zones' );
        }
}
add_action('zoninator_pre_init', 'ac_add_zoninator_post_types');

/** WEBHOOKS **/
function ac_stream_end($actions) {
    $actions[] = ac_stream_end_content();
    return $actions;
}
add_filter('wpwhpro/webhooks/get_webhooks_actions', 'ac_stream_end', 20);

function ac_stream_end_content() {
    $parameter = array();
    $returns = array(
        'success'        => array( 'short_description' => WPWHPRO()->helpers->translate( '(Bool) True if the action was successful, false if not. E.g. array( \'success\' => true )', 'action-stream_end-content' ) ),
        'msg'        => array( 'short_description' => WPWHPRO()->helpers->translate( '(string) A message with more information about the current request. E.g. array( \'msg\' => "This action was successful." )', 'action-stream_end-content' ) ),
    );
    ob_start();
    ?>
    <pre>
    $return_args = array(
        'success' => false,
        'msg' => 'This is a test message'
    );
    </pre>
    <?php
        $returns_code = ob_get_clean();
        ob_start();
    ?>
        <p>
            <?php echo WPWHPRO()->helpers->translate('When this hook fires, metadata is updated on the live teaching post to advance the service time.', 'ac-stream_end-content' ); ?>
        </p>
    <?php
    $description = ob_get_clean();
    return array(
        'action'            => 'stream_end', //required
        'parameter'         => $parameter,
        'returns'           => $returns,
        'returns_code'      => $returns_code,
        'short_description' => WPWHPRO()->helpers->translate('Notifies the upcoming teaching post the stream has ended.', 'ac-stream_end-content' ),
        'description'       => $description
    );
}

function ac_perform_stream_completion() {
    $announcements = z_get_posts_in_zone('upcoming-livestream', array(
        'posts_per_page' => 1,
        'post_type' => 'teaching',
        'post_status' => 'publish'
    ), false);

    if (!isset($announcements[0])) return;
    
    $announcement = $announcements[0];

    $finishes = (int) get_post_meta($announcement->ID, 'stream-completed', true);

    update_post_meta($announcement->ID, 'stream-completed', $finishes + 1, $finishes);
}

function ac_add_webhook_actions($action, $webhook, $api_key) {
    switch ($action) {
        case 'stream_end':
            $response_body = WPWHPRO()->helpers->get_response_body();
			$return_args = array(
				'success' => false
            );

            ac_perform_stream_completion();
            
            $return_args['msg'] = WPWHPRO()->helpers->translate("Acknowledged, thank you!", 'ac-stream_end-success');
            $return_args['success'] = true;
            WPWHPRO()->webhook->echo_response_data( $return_args );
			die();
    }
}

add_action('wpwhpro/webhooks/add_webhooks_actions', 'ac_add_webhook_actions', 20, 3);

function ac_is_sunday_teaching_active($teaching) {
    $teaching_date = (int) get_post_meta($teaching->ID, 'teaching-date', true);

    if (!$teaching_date) return false;

    $teaching_gmt = new DateTime();
    $teaching_gmt->setTimestamp($teaching_date);
    $teaching_day = $teaching_gmt->format('Y-m-d');
    $teaching_local = new DateTime($teaching_day, new DateTimeZone('America/Los_Angeles'));

    $is_sunday = $teaching_local->format('D') == 'Sun';

    if (!$is_sunday) {
        return false;
    }

    $teaching_local->modify('+8 hours');
    $teaching_begin = $teaching_local->getTimestamp();

    $teaching_local->modify('+4 hours');
    $teaching_local->modify('+30 minutes'); // 12:30 PM
    $teaching_end = $teaching_local->getTimestamp();

    return ($teaching_begin < time()) && ($teaching_end > time());
}

function ac_is_sunday_teaching_live($teaching) {
    $teaching_date = (int) get_post_meta($teaching->ID, 'teaching-date', true);

    if (!$teaching_date) return false;

    $teaching_gmt = new DateTime();
    $teaching_gmt->setTimestamp($teaching_date);
    $teaching_day = $teaching_gmt->format('Y-m-d');
    $teaching_local = new DateTime($teaching_day, new DateTimeZone('America/Los_Angeles'));

    $is_sunday = $teaching_local->format('D') == 'Sun';

    if (!$is_sunday) {
        return false;
    }

    $teaching_local->modify('+9 hours');
    $teaching_local->modify('+30 minutes');
    $teaching_begin = $teaching_local->getTimestamp();

    $teaching_local->modify('+1 hours');
    $teaching_local->modify('+15 minutes');
    $teaching_end = $teaching_local->getTimestamp();

    return ($teaching_begin < time()) && ($teaching_end > time());
}

function ac_get_teaching_live_time($teaching) {
    $teaching_date = (int) get_post_meta($teaching->ID, 'teaching-date', true);

    if (!$teaching_date) return false;

    $teaching_gmt = new DateTime();
    $teaching_gmt->setTimestamp($teaching_date);
    $teaching_day = $teaching_gmt->format('Y-m-d');
    $teaching_local = new DateTime($teaching_day, new DateTimeZone('America/Los_Angeles'));

    $completes = (int) get_post_meta($teaching->ID, 'stream-completed', true);

    $is_sunday = $teaching_local->format('D') == 'Sun';

    if ($completes == 0) {
        if ($is_sunday) {
            $teaching_local->modify('+9 hours');
            $teaching_local->modify('+30 minutes');
            return $teaching_local->getTimestamp();
        } else {
            $teaching_local->modify('+18 hours');
            $teaching_local->modify('+30 minutes'); // 6:30 PM
            return $teaching_local->getTimestamp();
        }
    }

    // No more for this post.
    return false;

    /*
    if ($completes == 1) {
        if ($is_sunday) {
            $teaching_local->modify('+11 hours');
            $teaching_local->modify('+15 minutes');
            return $teaching_local->getTimestamp();
        } else {
            // No more for this post.
            return false;
        }
    } else if ($completes == 2 && $teaching_day == '2020-12-24') {
        // Special Christmas Eve service.
        $teaching_local->modify('+15 hours'); // 3 PM
        return $teaching_local->getTimestamp();
    } else {
        // No more for this post.
        return false;
    }
    */
}

/* Exclude videos on the blog page. */

function awakening_exclude_videos_on_blog_index( $query ) {
    if ( $query->is_home() && $query->is_main_query() ) {
        $query->set( 'cat', '-29' ); // exclude Messages category
    }
}
add_action( 'pre_get_posts', 'awakening_exclude_videos_on_blog_index' );

/* Fallback if "WPCustom Category Image" plugin is not installed. */

if (!function_exists('category_image_src')) {
	function category_image_src() {
		return null;
	}
}

/* Check if post has ever had an embedded video via oEmbed. */
function jenner_post_has_oembed($post_id) {
    $metas = get_post_custom_keys($post_id);

    if (empty($metas)) return;

    foreach ($metas as $meta_key) {
        if ('_oembed_' == substr($meta_key, 0, 8)) {
            return true;
        }
    }

    return false;
}

/*
function awakening_sort_teaching($query) {
    //if (is_post_type_archive('teaching') && $query->is_main_query()) {
    if ($query->get('post_type') === 'teaching' && ! $query->get('meta_key')) {
        $query->set('meta_key', 'teaching-date');
	$query->set('order', 'DESC');
        $query->set('orderby', 'meta_value_num');
    }
}
add_action('pre_get_posts', 'awakening_sort_teaching');
*/
?>