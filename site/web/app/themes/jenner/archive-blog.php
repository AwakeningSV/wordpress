                                <div class="archive-blog archive-u">
                                    <article id="post-<?php the_ID(); ?>" <?php post_class( 'archive-inner-u' ); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">
                                        <a href="<?php the_permalink(); ?>">
                                            <?php if (has_post_thumbnail()): ?>
                                                <?php the_post_thumbnail('medium'); ?>
                                            <?php else: ?>
                                               <img src="https://awakening.azureedge.net/wordpressproduction/2018/08/blog-thumb.jpg" width="300" />
                                            <?php endif; ?>
                                        </a>

                                        <p class="archive-h2">
                                            <a href="<?php the_permalink(); ?>">
                                                <?php the_title(); ?>
                                            </a>
                                        </p>
                                    
                                        <?php if (get_post_type() === 'post' && !in_category('messages')) : ?>
                                            <p class="archive-date">
                                                <?php printf('<time class="updated" datetime="%1$s" pubdate>%2$s</time>', get_the_time('Y-m-j'), get_the_time(get_option('date_format'))); ?>
                                            </p>
                                        <?php endif; ?>
                                            <div class="archive-excerpt">
                                                <?php the_excerpt(); ?>
                                        </div>
                                    </article> <?php // end article ?>
                                </div>
