            <footer class="footer" role="contentinfo">
                <div class="footerwhite">
                    <div class="wrap">
                        <?php block_template_part( 'Social Icons' ); ?>
                    </div>
                </div>
                <div id="inner-footer" class="wrap clearfix" itemscope itemtype="http://schema.org/NGO">
                    <div class="widget">
                        <?php block_template_part( 'Footer' ); ?>
                    </div>
                    <p class="source-org copyright">
                        <span itemprop="name"><?php bloginfo( 'name' ); ?></span> exists to awaken this generation to new life in Jesus Christ.
                        &bull; &copy; <?php echo date('Y'); ?>
						&bull; <a href="https://awakeningchurch.com/privacy/">Privacy</a>
                    </p>

				</div> <?php // end #inner-footer ?>

			</footer> <?php // end footer ?>

		</div> <?php // end #container ?>

		<?php // all js scripts are loaded in library/bones.php ?>
        <script>
            window.ESV_CROSSREF_OPTIONS = {
                header_font_color: '000000'
            };
        </script>
		<?php wp_footer(); ?>

	</body>

</html> <?php // end page. what a ride! ?>