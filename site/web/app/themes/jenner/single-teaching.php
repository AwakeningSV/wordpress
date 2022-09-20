<?php get_header(); ?>

			<div id="content">

				<div id="inner-content" class="wrap clearfix">

						<div id="main" class="eightcol first clearfix" role="main">

							<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                            <?php
                                $series = get_the_terms($post->ID, 'series');
                                $series = $series[0];

                                if ($series) {
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
                                }
                            ?>

							<article id="post-<?php the_ID(); ?>" <?php post_class( 'clearfix' ); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">
<?php

$post_content = get_the_content();
$video_pattern = '/https?:\/\/[^\s"]+\s*$/im';

// Find a more speciifc video URL by looking for oEmbed URLs.
// We only use a single oEmbed in teaching posts, so we only need the first URL match.
if (preg_match($video_pattern, $post_content, $matches)) {
    // If we found a valid oEmbed, save it as the video URL.
    if (wp_oembed_get($matches[0])) {
        // Apply Fluid Video Embed filtering (do not use wp_oembed_get HTML directly)
        $video_html = apply_filters( 'the_content', $matches[0] );
    }
    $post_content = preg_replace($video_pattern, '', $post_content);
    $post_content = apply_filters( 'the_content', $post_content );
}
?>

                                <?php if ($video_html): ?>
                                    <div class="no-print teaching-video">
                                        <?php echo $video_html; ?>
                                    </div>
                                <?php endif; ?>

								<header class="article-header">

									<h1 class="page-title" itemprop="headline"><?php the_title(); ?></h1>

                                    <p class="byline">
                                        <?php the_terms( $post->ID, 'teachers', '', ', ', ' &bull; ' ); ?>
                                        <?php if ($series): ?>
                                            Part <? echo $series_position ?> of
                                            <?php the_terms( $post->ID, 'series', '', ', ', ' ' ); ?>
                                            &bull;
                                        <?php endif; ?>
                                        <?php $event_presented_date = get_post_meta($post->ID, 'teaching-date', true); ?>
                                        <?php $live_time = ac_get_teaching_live_time($post); ?>
                                        <?php printf('<time class="presented" datetime="%1$s">%2$s</time>', date('Y-m-j', $event_presented_date), date(get_option('date_format'), $event_presented_date)); ?>
                                    </p>

								</header>

                                <?php if ($video_html): ?>
                                    <div class="teaching-content">
                                        <?php echo $post_content; ?>
                                    </div>
                                <?php endif; ?>
                                <?php if ($post->content == '') : ?>
                                    <?php if ($event_presented_date > time() && $live_time) : ?>
                                        <p>This event will be presented
                                        <?php printf('<time class="presented" datetime="%1$s">%2$s</time>', date('Y-m-j', $event_presented_date), date(get_option('date_format'), $event_presented_date)); ?>.
                                        Check back here to watch live online or <a href="/visit/">plan a visit</a>.</p>
                                    <?php endif; ?>
                                <?php endif; ?>

								<?php
								    $scriptures = get_the_terms($post->ID, 'bible');
									$terms = get_the_terms($post->ID, 'series');

									$term = new stdClass();
									if (!empty($terms)) {
										$term = array_pop($terms);
									}
								?>

                                <div class="no-print">
                                <?php block_template_part( 'teaching-sidebar' ); ?>
                                </div>
 
                                <?php
                                    $teaching_notes = get_post_meta($post->ID, 'teaching-notes', true);
                                ?>

                                <div class="teaching-meta">
                                    <?php
                                        $audio_episode = powerpress_get_enclosure_data($post->ID, 'podcast');
                                        $video_episode = powerpress_get_enclosure_data($post->ID, 'video');
                                    ?>
                                    <?php if (!empty($audio_episode)) : ?>
                                        <h2>Listen</h2>
                                        <?php the_powerpress_content(); ?>
                                    <?php endif; ?>
                                    <?php if (!empty($audio_episode) || !empty($video_episode)) : ?>
                                        <h2>Download</h2>
                                        <ul>
                                            <?php if (!empty($audio_episode)) : ?>
                                                <li>
                                                    <a href="<?php echo esc_url($audio_episode['url']); ?>" rel="nofollow">
                                                        Audio MP3
                                                    </a>
                                                    <span class="teaching-podcast-meta">
                                                        <?php echo powerpress_readable_duration($audio_episode['duration']); ?>
                                                        /
                                                        <?php echo powerpress_byte_size($audio_episode['size']); ?>
                                                    </span>
                                                </li>
                                            <?php endif; ?>
                                            <?php if (!empty($video_episode)) : ?>
                                                <li>
                                                    <a href="<?php echo esc_url($video_episode['url']); ?>" rel="nofollow">
                                                        Video MP4
                                                    </a>
                                                    <span class="teaching-podcast-meta">
                                                        <?php echo powerpress_readable_duration($video_episode['duration']); ?>
                                                        /
                                                        <?php echo powerpress_byte_size($video_episode['size']); ?>
                                                    </span>
                                                </li>
                                            <?php endif; ?>
                                        </ul>
                                    <?php endif; ?>
                                    <?php if (!empty($scriptures)) : ?>
                                        <h2>Scripture</h2>
                                        <ul>
                                            <?php foreach($scriptures as $reference) : ?>
                                                <li><?php echo $reference->name; ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php endif; ?>
                                </div>

							<?php if ($term->name) : ?>
								<div class="teaching-more">
									<h3>More from <?php echo $term->name; ?></h3>
									<p><?php echo $term->description; ?></p>
									<?php

									// TODO: Filter this post out!

									$event_query = new WP_Query(array(
									    'post_type' => 'teaching',
										'tax_query' => array(
											array(
												'taxonomy' => 'series',
												'terms' => $term->term_id
											)
										),
									    'meta_key' => 'teaching-date',
									    'orderby' => 'meta_value_num',
									    'order' => 'ASC',
									    'posts_per_page' => -1
									));

									$event_posts = $event_query->posts;

									foreach(array_chunk($event_posts, 4) as $posts):
									?>
		                                <div class="archive-g">
		                                    <?php
		                                        foreach ($posts as $post):
		                                            include 'teaching-item.php';
		                                        endforeach;
		                                    ?>
		                                </div>
									<?php endforeach; ?>
								</div>
                            <?php else: ?>
                                <div style="height:24px" aria-hidden="true" class="wp-block-spacer"></div>
							<?php endif; ?>

                            <div class="no-print">
                                <?php block_template_part( 'teaching-footer' ); ?>
                            </div>

                                <?php if ($teaching_notes) : ?>
                                    <div id="teaching-notes">
                                        <h3>Teaching Notes</h3>
                                    </div>
                                    <?php echo wpautop($teaching_notes); ?>
                                <?php endif; ?>

							<?php endwhile; ?>

							</article> <?php // end article ?>

                            <?php else : ?>

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