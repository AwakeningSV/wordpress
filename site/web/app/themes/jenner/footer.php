            <footer class="footer" role="contentinfo">
                <div class="footerwhite">
                    <div class="wrap">
                        <?php get_sidebar() ?>
                    </div>
                </div>
                <div id="inner-footer" class="wrap clearfix" itemscope itemtype="http://schema.org/NGO">

                    <div class="pure-g-r" id="conclusion">
                        <div class="pure-u-9-24">
                            <div class="inner-conclusion">
                                <h2>Services</h2>
                                <ul>
                                    <li>Sundays 9:30 AM <a href="/live/">online</a></li>
                                    <li>Sundays 11:00 AM <a href="https://awakening.churchcenter.com/registrations">at Del Mar</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="pure-u-9-24">
                            <div class="inner-conclusion">
                                <h2>Location</h2>
                                <p><a href="/live">Join our online services</a> every Sunday.</p>
                                <p><a href="https://awakening.churchcenter.com/registrations">Registration is required</a> for Del Mar services.</p>
                                <address>
                                    <span itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
                                        Del Mar High School<br>
                                        <span itemprop="streetAddress">1224 Del Mar Ave</span><br>
                                        <span itemprop="addressLocality">San Jose</span> <span itemprop="addressRegion">CA</span> <span itemprop="postalCode">95128</span>
                                    </span>
                                </address>
                            </div>
                        </div>
                        <div class="pure-u-6-24">
                            <div class="inner-conclusion">
                                <h2>Contact</h2>
                                <address>
                                    <span itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
                                        PO Box <span itemprop="postOfficeBoxNumber">8597</span><br>
                                        <span itemprop="addressLocality">San Jose</span> <span itemprop="addressRegion">CA</span> <span itemprop="postalCode">95155</span>
                                    </span>
                                </address>
                                <p><a href="https://awakening.churchcenter.com/people/forms/10660" data-open-in-church-center-modal="true">Contact Us</a></p>
                                <p>Email <a itemprop="email" href="mailto:ask@awakeningchurch.com">ask@awakeningchurch.com</a></p>
                            </div>
                        </div>
                                           </div>

					<nav role="navigation">
					</nav>

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