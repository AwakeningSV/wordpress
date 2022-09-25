<?php get_header(); ?>

			<div id="content">

				<div id="inner-content" class="wrap clearfix">

						<div id="main" class="eightcol first clearfix" role="main">

							<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

							<article id="post-<?php the_ID(); ?>" <?php post_class( 'clearfix' ); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">

								<h1>Leadership</h1>

								<?php
									$terms = get_the_terms($post->id, 'teachers');

									$teacher = new stdClass();
									if (!empty($terms)) {
										$teacher = array_pop($terms);
									}
								?>

								<div class="leaders-g">
									<div class="leaders-u">
										<div class="leaders-inner-u">
											<header class="article-header leaders-header">

												<h2 class="page-title" itemprop="headline"><?php the_title(); ?></h1>

			                                    <p class="byline">
			                                        <?php print get_post_meta($post->ID, 'leader-role', true); ?>
			                                    </p>

											</header> <?php // end article header ?>

											<section class="clearfix" itemprop="articleBody">
												<?php the_content(); ?>
			                                </section> <?php // end article section ?>

											<?php $email = get_post_meta($post->ID, 'leader-email', true); ?>
											<?php if ($email) : ?>
												<p>
													<a href="mailto:<?php echo $email ?>"
														><?php echo $email; ?></a>
												</p>
											<?php endif; ?>
										</div>
									</div>
									<div class="leaders-u">
										<div class="leaders-inner-u">
											<?php the_post_thumbnail('awakening-leader-488'); ?>
										</div>
									</div>
								</div>

								<?php if ($teacher->term_id) : ?>
									<h3>Sermons by <?php echo $teacher->name; ?></h3>
		                            <div class="archive-g">
                                        <?php
                                            $teaching_query = new WP_Query(array(
                                                'post_type' => 'teaching',
                                                'tax_query' => array(
                                                    array(
                                                        'taxonomy' => 'teachers',
                                                        'terms' => $teacher->term_id
                                                    )
                                                ),
                                                'meta_key' => 'teaching-date',
                                                'orderby' => 'meta_value_num',
                                                'order' => 'DESC',
                                                'posts_per_page' => 8
                                            ));

                                            $teaching_posts = $teaching_query->posts;

                                            foreach ($teaching_posts as $post):
                                                include 'teaching-item.php';
                                            endforeach;
                                        ?>
		                            </div>
									<p>
                                        <div class="wp-block-buttons">
                                            <div class="wp-block-button">
                                                <a class="wp-block-button__link wp-element-button" href="<?php echo esc_url(get_term_link($teacher)); ?>">
											        More Sermons by <?php echo $teacher->name; ?>
                                                </a>
                                            </div>
                                        </div>
									</p>
								<?php endif; ?>

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

				</div> <?php // end #inner-content ?>

			</div> <?php // end #content ?>

<?php get_footer(); ?>
