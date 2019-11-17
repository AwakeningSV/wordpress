<?php
/*
Template Name: Home
*/
?>

<?php get_header(); ?>

			<div id="content">

				<div id="inner-content" class="wrap clearfix">

						<div id="main" class="home" role="main">

                            <?php
                                $posts = z_get_posts_in_zone('home', array(
                                    'posts_per_page' => 1,
                                    'post_type' => 'any',
                                    'post_status' => 'publish'
                                ), false);
                                foreach ($posts as $post) :
                            ?>

                            <article id="post-<?php the_ID(); ?>" <?php post_class( 'home-hero clearfix' ); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">
                                <?php
                                    // Only show live video on the homepage if the featured post
                                    // has a live_time in the past and the live shortcode is present
                                    $show_live_video = false;
                                    if (has_shortcode($post->post_content, 'live')) {
                                        $live_start = (int) get_post_meta($post->ID, 'live_time', true);
                                        $show_live_video = $live_start && ($live_start - 7200) < time();
                                    }
                                    if (!$show_live_video) :
                                ?>
                                    <?php if (has_post_thumbnail()): ?>
                                        <a href="<?php the_permalink(); ?>">
                                            <?php the_post_thumbnail('awakening-1952'); ?>
                                        </a>
                                    <?php else: ?>
                                        <header class="article-header">
                                            <a href="<?php the_permalink(); ?>">
                                                <h1 class="entry-title single-title" itemprop="headline"><?php the_title(); ?></h1>
                                            </a>
                                        </header>
                                        <section class="entry-content clearfix" itemprop="articleBody">
                                            <?php the_excerpt(); ?>
                                        </section> <?php // end article section ?>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <?php echo do_shortcode('[live height="550"]'); ?>
                                <?php endif; ?>
							</article> <?php // end article ?>

                            <?php endforeach; wp_reset_postdata(); ?>

                            <?php if (!count($posts)) : ?>

									<article id="post-not-found" class="hentry clearfix">
										<header class="article-header">
											<h1><?php _e( 'Something went wrong.', 'bonestheme' ); ?></h1>
										</header>
										<section class="entry-content">
											<p><?php _e( 'Uh Oh. Something is missing. Try double checking things.', 'bonestheme' ); ?></p>
										</section>
									</article>

							<?php endif; ?>
<?php
$zone_posts = z_get_posts_in_zone('home', array(
    'posts_per_page' => 4,
    'offset' => 1,
    'post_type' => 'any',
    'post_status' => 'publish'
), false);

$event_query = new WP_Query(array(
    'post_type' => 'event',
    'meta_key' => 'event-start-date',
    'meta_query' => array(
        array(
            'key' => 'event-end-date',
            'value' => strtotime('-1 day'),
            'compare' => '>='
        )
    ),
    'orderby' => 'meta_value_num',
    'order' => 'ASC',
    'posts_per_page' => 8
));

$event_posts = $event_query->posts;

// Note: magical things are happening for teaching posts!
// See pre_get_posts filter in teaching plugin

$teaching_latest_query = new WP_Query(array(
    'post_type' => 'teaching',
    'meta_key' => 'teaching-date',
    'orderby' => 'meta_value_num',
    'order' => 'DESC',
    'posts_per_page' => 4
));

$teaching_latest_posts = $teaching_latest_query->posts;

$teaching_latest_posts = array_reverse($teaching_latest_posts);

$home_posts = array();

foreach(array_chunk($zone_posts, 4) as $posts):
?>
                            <div class="home-section home-zone">
                                <div class="archive-g">
                                    <?php
                                        foreach ($posts as $post):
                                            include 'archive-item.php';
                                        endforeach;
                                    ?>
                                </div>
                            </div>
<?php endforeach; ?>

                            <div class="home-section">
                                <div class="home-section-header">
                                    <h2>Sunday Gatherings</h2>
                                </div>
<?php
foreach(array_chunk($teaching_latest_posts, 4) as $posts):
?>
                                <div class="archive-g">
                                    <?php
                                        foreach ($posts as $post):
                                            include 'teaching-item.php';
                                        endforeach;
                                    ?>
                                </div>
<?php endforeach; ?>
                            </div>

                            <div class="home-section">
                                <div class="home-section-header">
                                    <h2>Upcoming Events</h2>
                                </div>
<?php
foreach(array_chunk($event_posts, 4) as $posts):
?>
                                <div class="archive-g">
                                    <?php
                                        foreach ($posts as $post):
						include 'event-item.php';
                                        endforeach;
                                    ?>
                                </div>
<?php endforeach; ?>
                            </div>
<?php wp_reset_postdata(); ?>

						</div> <?php // end #main ?>

						<?php get_sidebar(); ?>

				</div> <?php // end #inner-content ?>

			</div> <?php // end #content ?>

<?php get_footer(); ?>