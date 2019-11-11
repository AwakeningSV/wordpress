<div class="archive-u">
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'archive-inner-u' ); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">
		<a href="<?php the_permalink(); ?>">
			<?php if (has_post_thumbnail()): ?>
				<?php the_post_thumbnail('awakening-leader-235'); ?>
			<?php else : ?>
				<div class="leader-no-photo">
					<p>Photo Coming Soon</p>
				</div>
			<?php endif; ?>
		</a>

		<p class="archive-h2">
			<a href="<?php the_permalink(); ?>">
				<?php the_title(); ?>
			</a>
		</p>
		<p class="archive-date">
			<?php echo get_post_meta($post->ID, 'leader-role', true); ?>
		</p>
	</article> <?php // end article ?>
</div>
