<?php 
/**
 * GoogleAnalytics.php
 */
namespace Config\Core;
use Config\BcoreConfig;

class GoogleAnalytics extends BcoreConfig {

	public function __construct() {
		add_action('wp_footer', array($this, 'render'));
	}

	public function render() {
		?>
			<script>
				(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
				(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
				m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
				})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
				ga('create', '', 'auto');
				ga('send', 'pageview');
			</script>
		<?php

	}
}