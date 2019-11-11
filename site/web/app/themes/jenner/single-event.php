<?php get_header(); ?>

			<div id="content">

				<div id="inner-content" class="wrap clearfix">

						<div id="main" class="eightcol first clearfix" role="main">

							<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

							<article id="post-<?php the_ID(); ?>" <?php post_class( 'clearfix' ); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">

								<header class="article-header">

									<h1 class="page-title" itemprop="headline"><?php the_title(); ?></h1>

                                    <p class="byline">
                                        <?php $event_start_date = get_post_meta($post->ID, 'event-start-date', true); ?>
                                        <?php printf('<time class="start" datetime="%1$s">%2$s</time>', date('Y-m-j', $event_start_date), date(get_option('date_format'), $event_start_date)); ?>
                                        <?php $event_end_date = get_post_meta($post->ID, 'event-end-date', true); ?>
                                        <?php if ($event_start_date != $event_end_date) : ?>
                                            &mdash; <?php printf('<time class="end" datetime="%1$s">%2$s</time>', date('Y-m-j', $event_end_date), date(get_option('date_format'), $event_end_date)); ?>
                                        <?php endif; ?>
                                    </p>

								</header> <?php // end article header ?>

								<section class="entry-content clearfix" itemprop="articleBody">
									<?php the_content(); ?>
                                </section> <?php // end article section ?>

							</article> <?php // end article ?>

							<?php endwhile; else : ?>

									<article id="post-not-found" class="hentry clearfix">
										<header class="article-header">
											<h1><?php _e( 'Oops, Event Not Found!', 'bonestheme' ); ?></h1>
										</header>
										<section class="entry-content">
											<p><?php _e( 'Uh Oh. Something is missing. Try double checking things.', 'bonestheme' ); ?></p>
										</section>
										<footer class="article-footer">
												<p><?php _e( 'This is the error message in the page.php template.', 'bonestheme' ); ?></p>
										</footer>
									</article>

							<?php endif; ?>

						</div> <?php // end #main ?>

						<?php get_sidebar(); ?>

				</div> <?php // end #inner-content ?>

			</div> <?php // end #content ?>

<?php get_footer(); ?>
