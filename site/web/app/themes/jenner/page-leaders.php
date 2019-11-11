<?php
/*
Template Name: Leadership
*/
?>

<?php get_header(); ?>

			<div id="content">

				<div id="inner-content" class="wrap clearfix">

						<div id="main" class="leadership" role="main">

					<?php the_content(); ?>

<?php
$leaders = z_get_posts_in_zone('leaders-senior', array(
    'posts_per_page' => -1,
    'post_type' => 'any',
    'post_status' => 'publish'
), false);

foreach(array_chunk($leaders, 4) as $posts):
?>
                            <div class="leader-section leader-zone">
                                <div class="archive-g">
                                    <?php
                                        foreach ($posts as $post):
                                            include 'item-leaders.php';
                                        endforeach;
                                    ?>
                                </div>
                            </div>
<?php endforeach; ?>

<?php
$leaders = z_get_posts_in_zone('leaders-staff', array(
    'posts_per_page' => -1,
    'post_type' => 'any',
    'post_status' => 'publish'
), false);

foreach(array_chunk($leaders, 4) as $posts):
?>
                            <div class="leader-section leader-zone">
                                <div class="archive-g">
                                    <?php
                                        foreach ($posts as $post):
                                            include 'item-leaders.php';
                                        endforeach;
                                    ?>
                                </div>
                            </div>
<?php endforeach; ?>

<?php
$leaders = z_get_posts_in_zone('leaders-volunteer', array(
    'posts_per_page' => -1,
    'post_type' => 'any',
    'post_status' => 'publish'
), false);

foreach(array_chunk($leaders, 4) as $posts):
?>
                            <div class="leader-section leader-zone">
                                <div class="archive-g">
                                    <?php
                                        foreach ($posts as $post):
                                            include 'item-leaders.php';
                                        endforeach;
                                    ?>
                                </div>
                            </div>
<?php endforeach; ?>

						</div> <?php // end #main ?>

				</div> <?php // end #inner-content ?>

			</div> <?php // end #content ?>

<?php get_footer(); ?>
