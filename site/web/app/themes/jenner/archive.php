<?php get_header(); ?>

			<div id="content">

				<div id="inner-content" class="wrap clearfix">

						<div id="main" class="eightcol first clearfix" role="main">

							<?php if (is_category()) { ?>
								<h1 class="archive-title h2">
									<span><?php single_cat_title(); ?>
								</h1>

							<?php } elseif (is_tag()) { ?>
								<h1 class="archive-title h2">
									<span><?php _e( 'Posts Tagged:', 'bonestheme' ); ?></span> <?php single_tag_title(); ?>
								</h1>

							<?php } elseif (is_author()) {
								global $post;
								$author_id = $post->post_author;
							?>
								<h1 class="archive-title h2">

									<span><?php _e( 'Posts by:', 'bonestheme' ); ?></span> <?php the_author_meta('display_name', $author_id); ?>

								</h1>
							<?php } elseif (is_day()) { ?>
								<h1 class="archive-title h2">
									<span><?php _e( 'Daily Archives:', 'bonestheme' ); ?></span> <?php the_time('l, F j, Y'); ?>
								</h1>

							<?php } elseif (is_month()) { ?>
									<h1 class="archive-title h2">
										<span><?php _e( 'Monthly Archives:', 'bonestheme' ); ?></span> <?php the_time('F Y'); ?>
									</h1>

							<?php } elseif (is_year()) { ?>
									<h1 class="archive-title h2">
										<span><?php _e( 'Yearly Archives:', 'bonestheme' ); ?></span> <?php the_time('Y'); ?>
									</h1>

                            <?php } elseif (is_post_type_archive('teaching')) { ?>
                                    <div class="teaching-header" data-count="<?php echo wp_count_posts('teaching')->publish; ?>">
                                        <h1 class="archive-title h2">
                                            <span><?php echo post_type_archive_title(); ?></span>
                                        </h1>
                                        <?php block_template_part( 'teaching-header' ); ?>
                                    </div>

                            <?php } elseif (get_query_var('term')) {
                                $term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
                                $features = get_taxonomy(get_query_var('taxonomy'));
                            ?>
                                <?php
                                    if ('teachers' === get_query_var('taxonomy')) {
                                ?>
                                    <div class="teaching-taxonomy-header">
                                        <span class="teaching-section-byline"><a href="/">Home</a></span>
                                        <span class="teaching-section-byline"><a href="/teaching/">Sermons</a></span>
                                        <span class="teaching-section-byline"><?php _e('Teachers', 'bonestheme'); ?></span>
                                    </div>
                                    <h1 class="archive-title h2">
                                        <span><?php echo $term->name; ?></span>
                                    </h1>
                                <?php } else { ?>
                                        <h1 class="archive-title h2">
                                            <span><?php echo $term->name; ?> <?php echo $features->labels->singular_name; ?></span>
                                        </h1>
                                <?php }  ?>
							<?php }  ?>

                            <?php if (have_posts()): ?>
                                <div class="archive-g">
                                    <?php
                                        while (have_posts()):
                                            the_post();
                                            if (
                                                is_post_type_archive('teaching') ||
                                                'teachers' === get_query_var('taxonomy') ||
                                                'series' === get_query_var('taxonomy')
                                            ) {
                                                include 'teaching-item.php';
                                            } else {
                                                include 'archive-blog.php';
                                            }
                                        endwhile;
                                    ?>
                                </div>

                                <?php if ( function_exists( 'bones_page_navi' ) ) { ?>
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
										<section class="entry-content">
                                            <p>
                                                <?php _e( 'We have no items to display, but you are invited to explore our blog.', 'bonestheme' ); ?>
                                            </p>
                                            <p>
                                                <a href="/blog/" class="button button-arrow">Start reading</a>
                                            </p>
										</section>
									</article>

							<?php endif; ?>

						</div> <?php // end #main ?>

								</div> <?php // end #inner-content ?>

			</div> <?php // end #content ?>

<?php get_footer(); ?>