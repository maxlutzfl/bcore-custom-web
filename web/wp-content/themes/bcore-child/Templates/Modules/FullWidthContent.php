<?php
/**
 * Templates\FullWidthContent.php
 */

class FullWidthContent {

	public function __construct() {
		$this->render();
	}

	public function render() {
		?>
			<section class="full-width-section">
				<div class="section-container">
					<div class="full-width-section__header">
						<h1><?php echo get_page_title(); ?></h1>
					</div>
					<div class="full-width-section__body">
						<?php 

							if (get_the_content()) { 
								the_content(); 
							} 
						?>
					</div>
				</div>
			</section>
		<?php
	}
}
