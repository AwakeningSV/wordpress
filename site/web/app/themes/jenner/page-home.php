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
                                        <section class="entry-content clearfix" itemprop="articleBody">
                                            <?php the_content(); ?>
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

<?php wp_reset_postdata(); ?>

						</div> <?php // end #main ?>

				</div> <?php // end #inner-content ?>

			</div> <?php // end #content ?>

<?php get_footer(); ?>
