<?php
/* Welcome to Bones :)
This is the core Bones file where most of the
main functions & features reside. If you have
any custom functions, it's best to put them
in the functions.php file.

Developed by: Eddie Machado
URL: http://themble.com/bones/
*/

/*********************
LAUNCH BONES
Let's fire off all the functions
and tools. I put it up here so it's
right up top and clean.
*********************/

// we're firing all out initial functions at the start
add_action("after_setup_theme", "bones_ahoy", 16);

function bones_ahoy()
{
    // launching operation cleanup
    add_action("init", "bones_head_cleanup");
    // remove WP version from RSS
    add_filter("the_generator", "bones_rss_version");
    // remove pesky injected css for recent comments widget
    add_filter("wp_head", "bones_remove_wp_widget_recent_comments_style", 1);
    // clean up comment styles in the head
    add_action("wp_head", "bones_remove_recent_comments_style", 1);
    // clean up gallery output in wp
    add_filter("gallery_style", "bones_gallery_style");

    // enqueue base scripts and styles
    add_action("wp_enqueue_scripts", "bones_scripts_and_styles", 999);
    // ie conditional wrapper

    // launching this stuff after theme setup
    bones_theme_support();

    // adding the bones search form (created in functions.php)
    add_filter("get_search_form", "bones_wpsearch");

    // cleaning up random code around images
    add_filter("the_content", "bones_filter_ptags_on_images");
    // cleaning up excerpt
    add_filter("excerpt_more", "bones_excerpt_more");
} /* end bones ahoy */

/*********************
WP_HEAD GOODNESS
The default wordpress head is
a mess. Let's clean it up by
removing all the junk we don't
need.
*********************/

function bones_head_cleanup()
{
    // category feeds
    // remove_action( 'wp_head', 'feed_links_extra', 3 );
    // post and comment feeds
    // remove_action( 'wp_head', 'feed_links', 2 );
    // EditURI link
    remove_action("wp_head", "rsd_link");
    // windows live writer
    remove_action("wp_head", "wlwmanifest_link");
    // index link
    remove_action("wp_head", "index_rel_link");
    // previous link
    remove_action("wp_head", "parent_post_rel_link", 10, 0);
    // start link
    remove_action("wp_head", "start_post_rel_link", 10, 0);
    // links for adjacent posts
    remove_action("wp_head", "adjacent_posts_rel_link_wp_head", 10, 0);
    // WP version
    remove_action("wp_head", "wp_generator");
    // remove WP version from css
    add_filter("style_loader_src", "bones_remove_wp_ver_css_js", 9999);
    // remove Wp version from scripts
    add_filter("script_loader_src", "bones_remove_wp_ver_css_js", 9999);
} /* end bones head cleanup */

// remove WP version from RSS
function bones_rss_version()
{
    return "";
}

// remove WP version from scripts
function bones_remove_wp_ver_css_js($src)
{
    if (strpos($src, "ver=")) {
        $src = remove_query_arg("ver", $src);
    }
    return $src;
}

// remove injected CSS for recent comments widget
function bones_remove_wp_widget_recent_comments_style()
{
    if (has_filter("wp_head", "wp_widget_recent_comments_style")) {
        remove_filter("wp_head", "wp_widget_recent_comments_style");
    }
}

// remove injected CSS from recent comments widget
function bones_remove_recent_comments_style()
{
    global $wp_widget_factory;
    if (isset($wp_widget_factory->widgets["WP_Widget_Recent_Comments"])) {
        remove_action("wp_head", [
            $wp_widget_factory->widgets["WP_Widget_Recent_Comments"],
            "recent_comments_style",
        ]);
    }
}

// remove injected CSS from gallery
function bones_gallery_style($css)
{
    return preg_replace("!<style type='text/css'>(.*?)</style>!s", "", $css);
}

/*********************
SCRIPTS & ENQUEUEING
*********************/

/**
 * Serve theme styles via a hashed filename instead of WordPress' default style.css.
 *
 * Checks for a hashed filename as a value in a JSON object.
 * If it exists: the hashed filename is enqueued in place of style.css.
 * Fallback: the default style.css will be passed through.
 *
 * See: https://danielshaw.co.nz/wordpress-cache-busting-json-hash-map/
 *
 * @param string $css is WordPress’ required, known location for CSS: style.css
 */

function get_path_from_manifest($css)
{
    $map = get_template_directory() . "/build/manifest.json";
    static $hash = null;

    if (null === $hash) {
        $hash = file_exists($map)
            ? json_decode(file_get_contents($map), true)
            : [];
    }

    if (array_key_exists($css, $hash)) {
        return $hash[$css];
    }

    return $css;
}

function jenner_add_script_attributes($tag, $handle)
{
    $async_handles = ["jenner-main", "pcogiving"];

    if (in_array($handle, $async_handles)) {
        $tag = str_replace(" src=", " async src=", $tag);
    }

    $versioned_handles = ["jenner-main"];

    if (in_array($handle, $versioned_handles)) {
        // https://turbo.hotwired.dev/reference/attributes
        $tag = str_replace(" src=", ' data-turbo-track="reload" src=', $tag);
    }

    return $tag;
}

add_filter("script_loader_tag", "jenner_add_script_attributes", 10, 3);

function jenner_add_style_attributes($tag, $handle)
{
    $versioned_handles = ["jenner-fonts", "jenner-stylesheet"];

    if (in_array($handle, $versioned_handles)) {
        // https://turbo.hotwired.dev/reference/attributes
        $tag = str_replace(" href=", ' data-turbo-track="reload" href=', $tag);
    }

    return $tag;
}

add_filter("style_loader_tag", "jenner_add_style_attributes", 10, 3);

function bones_scripts_and_styles()
{
    global $wp_styles; // call global $wp_styles variable to add conditional wrapper around ie stylesheet the WordPress way
    if (!is_admin()) {
        // register main stylesheet
        wp_register_style(
            "jenner-stylesheet",
            get_stylesheet_directory_uri() .
                "/" .
                get_path_from_manifest("build/frontend.css"),
            ["jenner-fonts"],
            "",
            "all"
        );

        // wp_register_style( 'jenner-gfonts', 'https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,700,700i,|Oswald:400', array(), '', 'all' );
        wp_register_style(
            "jenner-fonts",
            get_stylesheet_directory_uri() .
                "/" .
                get_path_from_manifest("build/fonts.css"),
            [],
            "",
            "all"
        );

        //adding scripts file in the footer
        wp_register_script(
            "jenner-main",
            get_stylesheet_directory_uri() .
                "/" .
                get_path_from_manifest("build/main.js"),
            ["jquery-core"],
            "",
            false // in head but will be async, see jenner_add_script_attributes
        );

        // https://planning.center/blog/2017/11/embedded-giving
        wp_register_script(
            "pcogiving",
            "https://js.churchcenter.com/modal/v1",
            [],
            "",
            false // in head but will be async, see jenner_add_script_attributes
        );

        // enqueue styles and scripts
        wp_enqueue_style("jenner-stylesheet");
        wp_enqueue_style("jenner-fonts");
        wp_enqueue_script("jenner-main");
        wp_enqueue_script("pcogiving");
    }
}

/*********************
THEME SUPPORT
*********************/

// Adding WP 3+ Functions & Theme Support
function bones_theme_support()
{
    add_theme_support("block-template-parts");

    add_theme_support("editor-styles");
    add_editor_style(get_path_from_manifest("build/editor.css"));

    // wp thumbnails (sizes handled in functions.php)
    add_theme_support("post-thumbnails");

    // default thumb size
    set_post_thumbnail_size(230, 130, true);

    // rss thingy
    add_theme_support("automatic-feed-links");

    // to add header image support go here: http://themble.com/support/adding-header-background-image-support/

    // adding post format support
    add_theme_support("post-formats", [
        "aside", // title less blurb
        "gallery", // gallery of images
        "link", // quick link to other site
        "image", // an image
        "quote", // a quick quote
        "status", // a Facebook like status update
        "video", // video
        "audio", // audio
        "chat", // chat transcript
    ]);

    // wide and full width images
    add_theme_support("align-wide");
} /* end bones theme support */

/*********************
RELATED POSTS FUNCTION
*********************/

// Related Posts Function (call using bones_related_posts(); )
function bones_related_posts()
{
    echo '<ul id="bones-related-posts">';
    global $post;
    $tags = wp_get_post_tags($post->ID);
    if ($tags) {
        foreach ($tags as $tag) {
            $tag_arr .= $tag->slug . ",";
        }
        $args = [
            "tag" => $tag_arr,
            "numberposts" => 5 /* you can change this to show more */,
            "post__not_in" => [$post->ID],
        ];
        $related_posts = get_posts($args);
        if ($related_posts) {
            foreach ($related_posts as $post):
                setup_postdata($post); ?>
	           	<li class="related_post"><a class="entry-unrelated" href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></li>
	        <?php
            endforeach;
        } else {
             ?>
            <?php echo '<li class="no_related_post">' .
                __("No Related Posts Yet!", "bonestheme") .
                "</li>"; ?>
		<?php
        }
    }
    wp_reset_query();
    echo "</ul>";
} /* end bones related posts function */

/*********************
PAGE NAVI
*********************/

// Numeric Page Navi (built into the theme by default)
function bones_page_navi()
{
    global $wp_query;
    $bignum = 999999999;
    if ($wp_query->max_num_pages <= 1) {
        return;
    }

    echo '<nav class="pagination pure-paginator">';

    echo paginate_links([
        "base" => str_replace(
            $bignum,
            "%#%",
            esc_url(get_pagenum_link($bignum))
        ),
        "format" => "",
        "current" => max(1, get_query_var("paged")),
        "total" => $wp_query->max_num_pages,
        "prev_text" => "&larr;",
        "next_text" => "&rarr;",
        "type" => "list",
        "end_size" => 3,
        "mid_size" => 3,
    ]);

    echo "</nav>";
} /* end page navi */

/*********************
RANDOM CLEANUP ITEMS
*********************/

// remove the p from around imgs (http://css-tricks.com/snippets/wordpress/remove-paragraph-tags-from-around-images/)
function bones_filter_ptags_on_images($content)
{
    return preg_replace(
        "/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU",
        '\1\2\3',
        $content
    );
}

// This removes the annoying […] to a Read More link
function bones_excerpt_more($more)
{
    global $post;
    // edit here if you like
    return '...  <a class="excerpt-read-more" href="' .
        get_permalink($post->ID) .
        '" title="' .
        __("Read", "bonestheme") .
        get_the_title($post->ID) .
        '">' .
        __("Read more &raquo;", "bonestheme") .
        "</a>";
}

/*
 * This is a modified the_author_posts_link() which just returns the link.
 *
 * This is necessary to allow usage of the usual l10n process with printf().
 */
function bones_get_the_author_posts_link()
{
    global $authordata;
    if (!is_object($authordata)) {
        return false;
    }
    $link = sprintf(
        '<a href="%1$s" title="%2$s" rel="author">%3$s</a>',
        get_author_posts_url($authordata->ID, $authordata->user_nicename),
        esc_attr(sprintf(__("Posts by %s"), get_the_author())), // No further l10n needed, core will take care of this one
        get_the_author()
    );
    return $link;
}

?>
