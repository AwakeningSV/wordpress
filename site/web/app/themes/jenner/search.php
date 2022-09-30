<?php get_header(); ?>

			<div id="content">

				<div id="inner-content" class="wrap">

					<div id="main" role="main">
                        <div class="teaching-header">
                            <div>
                                <div class="teaching-taxonomy-header">
                                        <span class="teaching-section-byline"><a href="/">Home</a></span>
                                    <?php if (get_query_var('post_type') === 'teaching'): ?>
                                        <span class="teaching-section-byline"><a href="/teaching/">Sermons</a></span>
                                    <?php endif; ?>
                                    <span class="teaching-section-byline"><?php _e('Search Results', 'bonestheme'); ?></span>
                                </div>
                                <h1 class="archive-title h2">
                                    <span><?php echo esc_attr( wptexturize( '"' . trim( get_search_query() ) . '"' ) ); ?></span>
                                </h1>
                            </div>
                            <?php if (get_query_var('post_type') === 'teaching'): ?>
                                <?php block_template_part( 'teaching-header' ); ?>
                            <?php endif; ?>
                        </div>

                        <?php if (have_posts()): ?>
                            <div class="archive-g">
                                <?php
                                    while (have_posts()):
                                        the_post();
                                        if (
                                            'teaching' === get_post_type() ||
                                            'teachers' === get_post_type() ||
                                            'series' === get_post_type()
                                        ) {
                                            include 'teaching-item.php';
                                        } else {
                                            include 'archive-blog.php';
                                        }
                                    endwhile;
                                ?>
                            </div>
                        <?php
/*
						<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

							<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> role="article">

								<header class="article-header">

									<h3 class="search-title"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
									<p class="byline vcard"><?php
                                        // https://wordpress.stackexchange.com/a/169509
                                        $postType = get_post_type_object(get_post_type());
                                        if ($postType) {
                                            // echo esc_html($postType->labels->singular_name);
                                        }
									?></p>

								</header> <?php // end article header ?>

								<section class="entry-content">
										<?php the_excerpt( '<span class="read-more">' . __( 'Read more &raquo;', 'bonestheme' ) . '</span>' ); ?>

								</section> <?php // end article section ?>

								<footer class="article-footer">

								</footer> <?php // end article footer ?>

							</article> <?php // end article ?>

						<?php endwhile; ?>
*/
                        ?>

								<?php if (function_exists('bones_page_navi')) { ?>
										<?php bones_page_navi(); ?>
								<?php } else { ?>
										<nav class="wp-prev-next">
												<ul class="clearfix">
													<li class="prev-link"><?php next_posts_link( __( '&laquo; Older Entries', 'bonestheme' )) ?></li>
													<li class="next-link"><?php previous_posts_link( __( 'Newer Entries &raquo;', 'bonestheme' )) ?></li>
												</ul>
										</nav>
								<?php } ?>

							<?php else : ?>

									<article id="post-not-found" class="hentry clearfix">
										<header class="article-header">
											<h1><?php _e( 'Nothing found', 'bonestheme' ); ?></h1>
										</header>
										<section class="entry-content">
											<p><?php _e( 'Try your search again.', 'bonestheme' ); ?></p>
										</section>
									</article>

							<?php endif; ?>

						</div> <?php // end #main ?>

					</div> <?php // end #inner-content ?>

			</div> <?php // end #content ?>

<?php get_footer(); ?>
