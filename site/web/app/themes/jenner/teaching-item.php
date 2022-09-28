<div class="archive-u teaching-u">
    <article id="post-<?php the_ID(); ?>" <?php post_class( 'archive-inner-u' ); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">
        <a href="<?php the_permalink(); ?>">
            <?php if (has_post_thumbnail()): ?>
                <?php the_post_thumbnail('awakening-235'); ?>
            <?php else: ?>
                <?php /* Default featured image from the Podcast Page, ID 2057 */ ?>
                <?php echo get_the_post_thumbnail(2057, 'awakening-235'); ?>
            <?php endif; ?>
        </a>
        <?php
            $series = get_the_terms($post->ID, 'series');
            $series = $series[0];
        ?>
        <?php if ($series) : ?>
            <?php
            if (is_search()) {
                $series_position = '';
            } else {
                $query = new WP_Query(array(
                    'series' => $series->slug,
                    'posts_per_page' => -1,
                    'post_status' => 'publish',
                    'meta_key' => 'teaching-date',
                    'orderby' => 'meta_value_num',
                    'order' => 'ASC',
                    'date_query' => array(
                        // Series may repeat by year, so only count
                        // posts in this year. This will not work if
                        // series continue over a year boundary,
                        // but this is unlikely to ever occur.
                        'year' => get_the_date('Y', $post->ID)
                    ),
                    'fields' => 'ids' // get only post ids
                ));

                $series_position = array_search($post->ID, $query->posts) + 1;
                // $series_total = $query->post_count;
            }
            ?>
            <p class="archive-date">
                Part <? echo $series_position ?> of
                <?php the_terms( $post->ID, 'series', '', ', ', ' ' ); ?>
            </p>
        <?php endif; ?>

        <p class="archive-h2">
            <a href="<?php the_permalink(); ?>">
                <?php the_title(); ?>
            </a>
        </p>
        <?php
            $event_presented_date = get_post_meta($post->ID, 'teaching-date', true);
            $event_content = get_post_field('post_content', $post->ID, 'raw');
            $live_time = ac_get_teaching_live_time($post);
            $event_is_live = ac_is_teaching_live($post);
            $event_is_over = !$event_is_live && $live_time < time();
        ?>
        <p class="archive-date">
            <?php the_terms( $post->ID, 'teachers', '', ', ', ' ' ); ?>
        </p>

        <?php if ($event_presented_date > time()) : ?>
            <p class="archive-badge archive-badge-upcoming">
                <a href="<?php the_permalink(); ?>">
                    Upcoming
                    <?php /* printf('<time class="presented" datetime="%1$s">%2$s</time>', date('Y-m-j', $event_presented_date), date('M j', $event_presented_date)); */ ?>
                </a>
            </p>
<p class="archive-date">
                <?php printf('<time class="presented" datetime="%1$s">%2$s</time>', date('Y-m-j', $event_presented_date), date(get_option('date_format'), $event_presented_date)); ?>
            </p>
        <?php else : ?>
            <?php if (!$live_time): ?>
                <?php if (!jenner_post_has_oembed($post->ID)) : ?>
                    <p class="archive-badge archive-badge-upcoming">
                        <a href="<?php the_permalink(); ?>">
                            Coming Soon
                        </a>
                    </p>
                <?php endif; ?>
            <?php elseif ($event_is_live && !$event_is_over) : ?>
                <p class="archive-badge archive-badge-live">
                    <a href="<?php the_permalink(); ?>">
                        Watch Live Now
                    </a>
                </p>
            <?php elseif (!$event_is_over) : ?>
            <p class="archive-badge archive-badge-upcoming">
                <a href="<?php the_permalink(); ?>" style="background: #24BFD0;">
                    <!-- Tomorrow -->
<?php
$tz = date_default_timezone_get();
date_default_timezone_set('America/Los_Angeles');
?>
                    <?php printf('<time class="presented" datetime="%1$s">%2$s</time>', date('Y-m-j', $live_time), date('g:ia', $live_time) . ' PT'); ?>
<?php
date_default_timezone_set($tz);
?>
                </a>
            </p>
            <?php endif; ?>
            <p class="archive-date">
                <?php printf('<time class="presented" datetime="%1$s">%2$s</time>', date('Y-m-j', $event_presented_date), date(get_option('date_format'), $event_presented_date)); ?>
            </p>
        <?php endif; ?>
    </article> <?php // end article ?>
</div>