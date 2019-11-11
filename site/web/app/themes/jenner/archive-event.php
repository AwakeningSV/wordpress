<?php get_header(); ?>

			<div id="content">

				<div id="inner-content" class="wrap clearfix">

						<div id="main" class="eightcol first clearfix" role="main">

                            <h1 class="archive-title h2">
                                Events
                            </h1>

                            <?php if (have_posts()): ?>
                                <div class="archive-g">
                                    <?php
                                        while (have_posts()):
                                            the_post();
                                            include 'event-item.php';
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
