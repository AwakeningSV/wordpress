                                <div class="archive-u">
                                    <article id="post-<?php the_ID(); ?>" <?php post_class( 'archive-inner-u' ); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">
                                        <a href="<?php the_permalink(); ?>">
                                            <?php if (has_post_thumbnail()): ?>
                                                <?php the_post_thumbnail('awakening-235'); ?>
                                            <?php else: ?>
                                                <?php echo get_the_post_thumbnail(37, 'awakening-235'); ?>
                                            <?php endif; ?>
                                        </a>
                                        <p class="archive-h2">
                                            <a href="<?php the_permalink(); ?>">
                                                <?php the_title(); ?>
                                            </a>
                                        </p>
                                        <div class="archive-excerpt"></div>
                                        <?php the_excerpt(); ?>
                                        <p class="archive-date">
                                            <?php $event_start_date = get_post_meta($post->ID, 'event-start-date', true); ?>
                                            <?php printf('<time class="start" datetime="%1$s">%2$s</time>', date('Y-m-j', $event_start_date), date(get_option('date_format'), $event_start_date)); ?>
                                            <?php $event_end_date = get_post_meta($post->ID, 'event-end-date', true); ?>
                                            <?php if ($event_start_date != $event_end_date) : ?>
                                                &mdash; <?php printf('<time class="end" datetime="%1$s">%2$s</time>', date('Y-m-j', $event_end_date), date(get_option('date_format'), $event_end_date)); ?>
                                            <?php endif; ?>

                                        </p>
                                    </article> <?php // end article ?>
                                </div>
