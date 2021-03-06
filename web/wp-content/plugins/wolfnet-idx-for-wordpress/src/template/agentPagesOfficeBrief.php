<?php

/**
 *
 * @title         agentPagesOfficeBrief.php
 * @copyright     Copyright (c) 2016, WolfNet Technologies, LLC
 *
 *                This program is free software; you can redistribute it and/or
 *                modify it under the terms of the GNU General Public License
 *                as published by the Free Software Foundation; either version 2
 *                of the License, or (at your option) any later version.
 *
 *                This program is distributed in the hope that it will be useful,
 *                but WITHOUT ANY WARRANTY; without even the implied warranty of
 *                MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *                GNU General Public License for more details.
 *
 *                You should have received a copy of the GNU General Public License
 *                along with this program; if not, write to the Free Software
 *                Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

?>

<div class="wolfnet_aoItem">

	<a href="<?php echo $officeLink; ?>">
		<div class="wolfnet_aoImage"
		 style="background-image: url('<?php echo $office['medium_url']; ?>');">
			<img src="<?php echo $office['medium_url']; ?>" />
		</div>
	</a>

	<div class="wolfnet_aoInfo">

		<div class="wolfnet_aoContact">

			<div class="wolfnet_aoTitle">
				<?php echo '<a href="' . $officeLink . '">' . $office['name'] . '</a>'; ?>
			</div>

			<hr />

			<div class="wolfnet_aoSubTitle">
				<?php
					if (strlen($office['address_1']) > 0) {
						echo $office['address_1'] . ' ' . $office['address_2']
							. '<br />'
							. $office['city'] . ', ' . $office['state'] . ' '
							. $office ['postal_code'];
					}
				?>
			</div>

		</div>

		<ul class="wolfnet_aoLinks">

			<?php

				if (strlen($office['phone_number']) > 0) {
					echo '<li><span class="wnt-icon wnt-icon-phone"></span> '
						. '<span class="wnt-visuallyhidden">Office phone:</span> '
						. $office['phone_number'] . '</li>';
				}

				if (strlen($office['fax_number']) > 0) {
					echo '<li><span  class="wnt-icon wnt-icon-fax"></span> '
						. '<span class="wnt-visuallyhidden">Office fax:</span> '
						. $office['fax_number'] . '</li>';
				}

			?>

			<li>
				<span class="wnt-icon wnt-icon-mail"></span>
				<a href="<?php echo $contactLink; ?>">Contact Us</a>
			</li>
			<li>
				<span class="wnt-icon wnt-icon-location"></span>
				<a target="_blank" href="<?php echo $searchLink; ?>">Search All Area Listings</a>
			</li>

		</ul>

		<div class="wolfnet_aoActions wolfnet_clearfix">
			<div class="wolfnet_aoAction">
				<a class="wnt-btn wnt-btn-secondary" href="<?php echo $officeLink; ?>">Meet Our Agents</a>
			</div>
			<div class="wolfnet_aoAction">
				<a class="wnt-btn wnt-btn-primary" target="_blank" href="<?php echo $searchResultLink; ?>">Featured Listings</a>
			</div>
		</div>

	</div>

</div>
