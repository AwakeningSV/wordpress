<?php
/*
Template Name: Events
*/
?>

<?php get_header(); ?>
			<div id="content">

				<div id="inner-content" class="wrap clearfix">

						<div id="main" role="main">

							<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

							<article id="post-<?php the_ID(); ?>" <?php post_class( 'clearfix' ); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">

								<header class="article-header">

									<h1 class="page-title" itemprop="headline"><?php the_title(); ?></h1>

								</header> <?php // end article header ?>

								<section class="entry-content clearfix" itemprop="articleBody">
									<?php the_content(); ?>
                                </section> <?php // end article section ?>

							</article> <?php // end article ?>

							<?php endwhile; endif; ?>
<?php

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
    'posts_per_page' => 12
));

$event_posts = $event_query->posts;
							
foreach(array_chunk($event_posts, 4) as $posts):
?>
                            <div class="home-section home-zone">
                                <div class="archive-g">
                                    <?php
                                        foreach ($posts as $post):
                                            include 'event-item.php';
                                        endforeach;
                                    ?>
                                </div>
                            </div>
<?php endforeach; ?>
<?php wp_reset_postdata(); ?>

                            <?php if (!count($posts)) : ?>

									<article id="post-not-found" class="hentry clearfix">
										<header class="article-header">
											<h1><?php _e( 'No events scheduled.', 'bonestheme' ); ?></h1>
										</header>
										<section class="entry-content">
                                            <p>
                                                <?php _e( 'No events are on our calendar right now, but you are invited to worship with us on Sunday.', 'bonestheme' ); ?>
                                                <a href="/visit/" class="button button-arrow">Visit us</a>
                                            </p>
                                        </section>
									</article>

							<?php endif; ?>

						</div> <?php // end #main ?>

						<?php get_sidebar(); ?>

				</div> <?php // end #inner-content ?>

			</div> <?php // end #content ?>

<?php get_footer(); ?>
