            <footer class="footer" role="contentinfo">
                <div class="footerwhite">
                    <div class="wrap">
                        <?php get_sidebar() ?>
                    </div>
                </div>
                <div id="inner-footer" class="wrap clearfix" itemscope itemtype="http://schema.org/NGO">
                    <?php get_sidebar('footer') ?>
                    <p class="source-org copyright">
                        <span itemprop="name"><?php bloginfo( 'name' ); ?></span> exists to awaken this generation to new life in Jesus Christ.
                        &bull; &copy; <?php echo date('Y'); ?>
						&bull; <a href="https://awakeningchurch.com/privacy/">Privacy</a>
                    </p>

				</div> <?php // end #inner-footer ?>

			</footer> <?php // end footer ?>

		</div> <?php // end #container ?>

		<?php // all js scripts are loaded in library/bones.php ?>
		<?php wp_footer(); ?>

	</body>

</html> <?php // end page. what a ride! ?>