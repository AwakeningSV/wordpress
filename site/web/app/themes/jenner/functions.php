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

function ac_is_teaching_active($teaching) {
    $teaching_date = (int) get_post_meta($teaching->ID, 'teaching-date', true);

    if (!$teaching_date) return false;

    $teaching_gmt = new DateTime();
    $teaching_gmt->setTimestamp($teaching_date);
    $teaching_day = $teaching_gmt->format('Y-m-d');
    $teaching_local = new DateTime($teaching_day, new DateTimeZone('America/Los_Angeles'));

    $is_sunday = $teaching_local->format('D') == 'Sun';

    if ($is_sunday) {
        $teaching_local->modify('+8 hours');
        $teaching_begin = $teaching_local->getTimestamp();

        $teaching_local->modify('+4 hours');
        $teaching_local->modify('+30 minutes'); // 12:30 PM
        $teaching_end = $teaching_local->getTimestamp();
    } else {
        // Christmas Eve
        $teaching_local->modify('+16 hours');
        $teaching_local->modify('+30 minutes'); // 4:30 PM
        $teaching_begin = $teaching_local->getTimestamp();

        // Allow for 1 hour 30 minutes after last service: 6:30 PM
        $teaching_local->modify('+2 hours');
        $teaching_end = $teaching_local->getTimestamp();
    }


    return ($teaching_begin < time()) && ($teaching_end > time());
}

function ac_is_teaching_live($teaching) {
    $teaching_date = (int) get_post_meta($teaching->ID, 'teaching-date', true);

    if (!$teaching_date) return false;

    $teaching_gmt = new DateTime();
    $teaching_gmt->setTimestamp($teaching_date);
    $teaching_day = $teaching_gmt->format('Y-m-d');
    $teaching_local = new DateTime($teaching_day, new DateTimeZone('America/Los_Angeles'));

    $is_sunday = $teaching_local->format('D') == 'Sun';

    if ($is_sunday) {
        $teaching_local->modify('+9 hours');
        $teaching_local->modify('+30 minutes');
        $teaching_begin = $teaching_local->getTimestamp();

        // Allow for 1 hour 15 minutes after last service: 12:30 PM
        $teaching_local->modify('+3 hours');
        $teaching_end = $teaching_local->getTimestamp();
    } else {
        // Christmas Eve
        $teaching_local->modify('+17 hours'); // 5 PM
        $teaching_begin = $teaching_local->getTimestamp();

        // Allow for 1 hour 30 minutes after last service: 6:30 PM
        $teaching_local->modify('+1 hour');
        $teaching_local->modify('+30 minutes');
        $teaching_end = $teaching_local->getTimestamp();
    }

    return ($teaching_begin < time()) && ($teaching_end > time());
}

function ac_get_teaching_live_time($teaching) {
    $teaching_date = (int) get_post_meta($teaching->ID, 'teaching-date', true);

    if (!$teaching_date) return false;

    $teaching_gmt = new DateTime();
    $teaching_gmt->setTimestamp($teaching_date);
    $teaching_day = $teaching_gmt->format('Y-m-d');
    $teaching_local = new DateTime($teaching_day, new DateTimeZone('America/Los_Angeles'));

    $service = ac_get_service_count($teaching);

    $is_sunday = $teaching_local->format('D') == 'Sun';

    if ($service === 1) {
        if ($is_sunday) {
            $teaching_local->modify('+9 hours');
            $teaching_local->modify('+30 minutes');
            return $teaching_local->getTimestamp();
        } else {
            // Special Christmas Eve service.
            $teaching_local->modify('+17 hours'); // 5 PM
            return $teaching_local->getTimestamp();
        }
    } else if ($service === 2) {
        if ($is_sunday) {
            $teaching_local->modify('+11 hours');
            $teaching_local->modify('+15 minutes');
            return $teaching_local->getTimestamp();
        }
    }

    // No more for this post.
    return false;
}

// Should we show a video for the first or second service?
function ac_get_service_count($teaching) {
    $teaching_date = (int) get_post_meta($teaching->ID, 'teaching-date', true);

    if (!$teaching_date) return 1;

    $teaching_gmt = new DateTime();
    $teaching_gmt->setTimestamp($teaching_date);
    $teaching_day = $teaching_gmt->format('Y-m-d');
    $teaching_local = new DateTime($teaching_day, new DateTimeZone('America/Los_Angeles'));

    // Second service is 11:15 AM, but show next service about 15 minutes before.
    $teaching_local->modify('+11 hours');
    $second_service_start = $teaching_local->getTimestamp();

    $now = time();

    if ($now > $second_service_start) return 2;

    return 1;
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


/**
 * Extend WordPress search to include custom fields.
 * See: https://adambalee.com/search-wordpress-by-custom-fields-without-a-plugin/
 */

/**
 * Join posts and postmeta tables
 *
 * http://codex.wordpress.org/Plugin_API/Filter_Reference/posts_join
 */
function cf_search_join( $join ) {
    global $wpdb;

    if ( is_search() && strpos( $join, $wpdb->postmeta ) === false) {
        $join .=' LEFT JOIN '.$wpdb->postmeta. ' ON '. $wpdb->posts . '.ID = ' . $wpdb->postmeta . '.post_id ';
    }

    if ( is_search() ) {
        $join .= " LEFT JOIN {$wpdb->term_relationships} tr ON {$wpdb->posts}.ID = tr.object_id INNER JOIN {$wpdb->term_taxonomy} tt ON tt.term_taxonomy_id=tr.term_taxonomy_id INNER JOIN {$wpdb->terms} t ON t.term_id = tt.term_id";
    }

    return $join;
}
add_filter('posts_join', 'cf_search_join' );

/**
 * Modify the search query with posts_where
 *
 * http://codex.wordpress.org/Plugin_API/Filter_Reference/posts_where
 */
function cf_search_where( $where ) {
    global $pagenow, $wpdb;

    if ( is_search() ) {
        // Exclude blog posts.
        $where .= " AND (" . $wpdb->posts . ".post_type != 'post')";
        // Search terms for teaching posts, e.g. scripture references, teachers.
        $where .= " OR (t.name LIKE '%".get_search_query()."%' AND {$wpdb->posts}.post_status = 'publish' AND " . $wpdb->posts . ".post_type = 'teaching')";
        // Search meta_value (sermon notes).
        $where = preg_replace(
            "/\(\s*".$wpdb->posts.".post_title\s+LIKE\s*(\'[^\']+\')\s*\)/",
            "(".$wpdb->posts.".post_title LIKE $1) OR (".$wpdb->postmeta.".meta_value LIKE $1)", $where );
    }

    return $where;
}
add_filter( 'posts_where', 'cf_search_where' );

/**
 * Prevent duplicates
 *
 * http://codex.wordpress.org/Plugin_API/Filter_Reference/posts_distinct
 */
function cf_search_distinct( $where ) {
    if ( is_search() ) {
        return "DISTINCT";
    }

    return $where;
}
add_filter( 'posts_distinct', 'cf_search_distinct' );

function ac_get_podcast_media_url($file_prefix, $teaching_year, $teaching_month, $teaching_day, $file_suffix) {
    $filename = "${file_prefix}${teaching_year}-${teaching_month}-${teaching_day}.${file_suffix}";

    return "https://awakeningmedia.azureedge.net/podcasts/${teaching_year}/${teaching_month}/${filename}";
}

function ac_backfill_podcast_episode($post_id, $is_video) {
    $should_replace_existing_enclosure = false;

    $meta_key = $is_video
        ? '_video:enclosure'
        : 'enclosure';

    $previous_enclosure = get_post_meta($post_id, $meta_key, true);

    if ($previous_enclosure) {
        $should_replace_existing_enclosure = (
            // media.awakeningchurch.com no longer exists, backfill if found
            strpos($previous_enclosure, '://media.awakeningchurch.com') !== false ||
            // backfill if old directory is used
            // some of these correctly redirect and play if they are
            // at awakeningchurch.com/podcast/ but the duration is not
            // available due to the redirect, so we should backfill
            // some of these are media.awakeningchurch.com/podcast/ which
            // will not work at all, so we should backfill
            // note the missing "s" before the slash
            strpos($previous_enclosure, '/podcast/2') !== false ||
            // backfill if media URL is not secure
            strpos($previous_enclosure, 'http://') === 0
        );

        if (!$should_replace_existing_enclosure) return;
    } else if ($is_video) {
        // If there is no previous video enclosure, do nothing.
        return;
    }

    $teaching_date = (int) get_post_meta($post_id, 'teaching-date', true);
    if (!$teaching_date) return;

    $teaching_gmt = new DateTime();
    $teaching_gmt->setTimestamp($teaching_date);
    $teaching_local = new DateTime($teaching_gmt->format('Y-m-d'), new DateTimeZone('America/Los_Angeles'));
    $teaching_year = $teaching_local->format('Y');
    $teaching_month = $teaching_local->format('m');
    $teaching_day = $teaching_local->format('d');

    $is_sunday = $teaching_local->format('D') == 'Sun';

    if (!$is_sunday && !$should_replace_existing_enclosure) return;

    $teaching_local->modify('+1 day');
    $day_after = $teaching_local->getTimestamp();

    $now = time();

    if ($now < $day_after) return;

    $file_prefix = $is_video ? 'awakening_video_' : 'awakening_';
    $file_suffix = $is_video ? 'mp4' : 'mp3';

    if ($previous_enclosure) {
        if (strpos($previous_enclosure, 'm4v') !== false) {
            $file_suffix = 'm4v';
        }
    }

    require_once(WP_PLUGIN_DIR . '/powerpress/powerpressadmin.php');

    $media_url = ac_get_podcast_media_url($file_prefix, $teaching_year, $teaching_month, $teaching_day, $file_suffix);
    $content_type = '';
    $info = powerpress_get_media_info_local($media_url, $content_type, 0, '');

    if ($info['error']) {
        if (!$is_video) return;

        $file_suffix = $file_suffix === 'mp4' ? 'm4v' : 'mp4';
        $media_url = ac_get_podcast_media_url($file_prefix, $teaching_year, $teaching_month, $teaching_day, $file_suffix);
        $info = powerpress_get_media_info_local($media_url, $content_type, 0, '');
        if ($info['error']) {
            return;
        }
    }

    $extra = array();
    $extra['duration'] = powerpress_readable_duration($info['duration'], true);

    $enclosure = $media_url . "\n" . $info['length'] . "\n" . $content_type . "\n" . serialize($extra);

    if ($previous_enclosure) {
        update_post_meta($post_id, $meta_key, $enclosure, $previous_enclosure);
    } else {
        add_post_meta($post_id, $meta_key, $enclosure, true);
    }
}

function ac_teaching_sort_filter($wp_query) {
    if (
        $wp_query->is_main_query() &&
        (
            is_post_type_archive('teaching') ||
            is_tax('series') || is_tax('teachers')
        )
    ) {
        $wp_query->set( 'meta_key', 'teaching-date' );
        $wp_query->set( 'orderby', 'meta_value_num' );
        $wp_query->set( 'order',
            is_tax('series')
                ? 'ASC'
                : 'DESC'
        );

        return $wp_query;
    }
}

add_filter( 'pre_get_posts', 'ac_teaching_sort_filter' );

function ac_get_podcast_artwork($post) {
    $episode = powerpress_get_enclosure_data($post->ID, 'podcast');
    if (empty($episode)) return '';

    $teaching_date = (int) get_post_meta($post->ID, 'teaching-date', true);
    if (!$teaching_date) return;

    $date_format = 'Y-m-d';

    $teaching_gmt = new DateTime();
    $teaching_gmt->setTimestamp($teaching_date);
    $teaching_local = new DateTime($teaching_gmt->format($date_format), new DateTimeZone('America/Los_Angeles'));
    $desired_date = $teaching_local->format($date_format);

    $feed = fetch_feed('https://awakeningmedia.azureedge.net/podcasts/awakening_podcast.rss');

    if (is_wp_error($feed)) return;

    $items = array();

    foreach ($feed->get_items() as $item) {
        if ($enclosure = $item->get_enclosure()) {
            $date = $item->get_date($date_format);
            $image = $item->get_item_tags(SIMPLEPIE_NAMESPACE_ITUNES, 'image')[0]['attribs']['']['href'];

            if (!$image) continue;

            // Rewrite image basename to HTTPS CDN with no redirects.
            if (preg_match('/images\/(.*)$/', $image, $matches)) {
                $image = "https://awakeningmedia.azureedge.net/podcasts/" . $matches[0];
            } else {
                continue;
            }

            $items[$date] = array(
                'image' => $image
            );
        }
    }

    if ($items[$desired_date]) {
        return $items[$desired_date]['image'];
    }

    return '';
}

?>