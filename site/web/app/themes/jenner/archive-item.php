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
                                        <?php if (get_post_type() === 'page') : ?>
                                            <p class="archive-excerpt">
                                                <?php the_excerpt(); ?>
                                            </p>
                                        <?php endif; ?>
                                        <?php if (get_post_type() === 'post' && !in_category('messages')) : ?>
                                            <p class="archive-date">
                                                <?php printf('<time class="updated" datetime="%1$s" pubdate>%2$s</time>', get_the_time('Y-m-j'), get_the_time(get_option('date_format'))); ?>
                                            </p>
                                        <?php endif; ?>
                                    </article> <?php // end article ?>
                                </div>
