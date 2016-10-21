<?php

/**
 * Provide a public-facing view for the plugin.
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @author     Amitzy
 * @package    Molongui_Authorship
 * @subpackage Molongui_Authorship/public/views
 * @since      1.2.18
 * @version    1.3.0
 */
?>

<!-- MOLONGUI AUTHORSHIP PLUGIN <?php echo MOLONGUI_AUTHORSHIP_VERSION ?> -->
<!-- <?php echo "https:" . MOLONGUI_AUTHORSHIP_WEB ?> -->
<div id="molongui-author-box-<?php echo $random_id; ?>"
     class="molongui-table molongui-tabs <?php ( ( isset( $is_preview ) and ( $is_preview ) ) ? '' : 'no-js' ); ?> molongui-author-box-container"
     itemscope itemtype="https://schema.org/Person">

	<!-- Authorship box tabs -->

	<div class="molongui-tabs-nav" style="<?php echo ( ( isset( $settings['box_background'] ) and !empty( $settings['box_background'] ) ) ? 'background-color: ' . $settings['box_background'] . ';' : '' );?>">
		<a href="#"
		   class="molongui-tabs-nav-link is-active"
		   style="<?php echo ( ( isset( $settings['box_border'] ) and !empty( $settings['box_border'] ) ) ? ( $settings['box_border'] == 'thin' ? 'border: 1px solid' : 'border: 2px solid;' ) : 'border: 0;' ); ?>
		          <?php echo ( ( isset( $settings['box_border_color'] ) and !empty( $settings['box_border_color'] ) ) ? 'border-color: ' . $settings['box_border_color'] . ';' : '' );?>">
			<i class="molongui-authorship-icon-feather"></i>
			<span><?php echo ( $settings[ 'about_the_author' ] ? $settings[ 'about_the_author' ] : __( 'About the author', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) ); ?></span>
		</a>
		<a href="#"
		   class="molongui-tabs-nav-link"
		   style="<?php echo ( ( isset( $settings['box_border'] ) and !empty( $settings['box_border'] ) ) ? ( $settings['box_border'] == 'thin' ? 'border-bottom: 1px solid' : 'border-bottom: 2px solid;' ) : 'border-bottom: 0;' ); ?>
		          <?php echo ( ( isset( $settings['box_border_color'] ) and !empty( $settings['box_border_color'] ) ) ? 'border-color: ' . $settings['box_border_color'] . ';' : '' );?>">
			<i class="molongui-authorship-icon-docs"></i>
			<span><?php echo ( $settings[ 'related_posts' ] ? $settings[ 'related_posts' ] : __( 'Related posts', MOLONGUI_AUTHORSHIP_TEXT_DOMAIN ) ); ?></span>
		</a>
	</div>

	<div id="molongui-author-box-wrapper"
	     class="mabc-shadow-<?php echo ( ( isset( $settings['box_shadow'] ) and !empty( $settings['box_shadow'] ) ) ? $settings['box_shadow'] : 'left' );?>
				mabc-border-<?php echo ( ( isset( $settings['box_border'] ) and !empty( $settings['box_border'] ) ) ? $settings['box_border'] : 'none' );?>
				mabc-bckg-<?php echo ( ( isset( $settings['box_background'] ) and !empty( $settings['box_background'] ) ) ? 'coloured' : 'none' );?>"
	     style="<?php echo ( ( isset( $settings['box_border_color'] ) and !empty( $settings['box_border_color'] ) ) ? 'border-color: ' . $settings['box_border_color'] . ';' : '' );?>
	            <?php echo ( ( isset( $settings['box_background'] ) and !empty( $settings['box_background'] ) ) ? 'background-color: ' . $settings['box_background'] . ';' : '' );?>">

		<!-- Authorship box tab contents -->

		<div class="molongui-tab is-active">
			<div class="molongui-tab-content">
				<!-- Author thumbnail -->
				<?php include( MOLONGUI_AUTHORSHIP_DIR . '/public/views/parts/author-box-thumbnail.php' ); ?>

				<!-- Author social -->
				<?php include( MOLONGUI_AUTHORSHIP_DIR . '/public/views/parts/author-box-socialmedia.php' ); ?>

				<!-- Author data -->
				<div class="molongui-table-cell molongui-author-box-data">

					<!-- Author name -->
					<?php include( MOLONGUI_AUTHORSHIP_DIR . '/public/views/parts/author-box-title.php' ); ?>

					<!-- Author metadata -->
					<?php include( MOLONGUI_AUTHORSHIP_DIR . '/public/views/parts/tabbed-author-box-meta.php' ); ?>

					<!-- Author bio -->
					<?php include( MOLONGUI_AUTHORSHIP_DIR . '/public/views/parts/author-box-bio.php' ); ?>

				</div><!-- End molongui-author-box-data -->
			</div>
		</div>
		<div class="molongui-tab">
			<div class="molongui-tab-content">
				<!-- Author related posts -->
				<?php include( MOLONGUI_AUTHORSHIP_DIR . '/public/views/parts/tabbed-author-box-related.php' ); ?>
			</div>
		</div>

	</div><!-- End molongui-author-box-wrapper -->

</div><!-- End molongui-author-box-container -->


<script type="text/javascript" language="JavaScript">

	<?php
	/**
	 * This script MUST be placed after the HTML markup, so
	 * no wait to 'window.onload' is required.
	 *
	 * By default, 'window.onload' it is fired when the entire
     * page loads, including its content (images, css, scripts,
     * etc.).
	 */
	?>

 	(function()
	{
		'use strict';

		<?php
		/**
		 * tabs
		 *
		 * @description The Tabs component.
		 * @param {Object} options The options hash
		 */
		?>
		var tabs = function(options)
		{
			var el = document.querySelector(options.el);
			var tabNavigationLinks = el.querySelectorAll(options.tabNavigationLinks);
			var tabContentContainers = el.querySelectorAll(options.tabContentContainers);
			var activeIndex = 0;
			var initCalled = false;

			<?php
			/**
			 * init
			 *
			 * @description Initializes the component by removing the no-js class from
			 *   the component, and attaching event listeners to each of the nav items.
			 *   Returns nothing.
			 */
			?>
			var init = function()
			{
				if (!initCalled)
				{
					initCalled = true;
					el.classList.remove('no-js');

					for (var i = 0; i < tabNavigationLinks.length; i++)
					{
						var link = tabNavigationLinks[i];
						handleClick(link, i);
					}
				}
			};

			<?php
			/**
			 * handleClick
			 *
			 * @description Handles click event listeners on each of the links in the
			 *   tab navigation. Returns nothing.
			 * @param {HTMLElement} link The link to listen for events on
			 * @param {Number} index The index of that link
			 */
			?>
			var handleClick = function(link, index)
			{
				link.addEventListener('click', function(e)
				{
					e.preventDefault();
					goToTab(index);
				});
			};

			<?php
			/**
			 * goToTab
			 *
			 * @description Goes to a specific tab based on index. Returns nothing.
			 * @param {Number} index The index of the tab to go to
			 */
			?>
			var goToTab = function(index)
			{
				if (index !== activeIndex && index >= 0 && index <= tabNavigationLinks.length)
				{
					tabNavigationLinks[activeIndex].classList.remove('is-active');
					tabNavigationLinks[index].classList.add('is-active');
					tabContentContainers[activeIndex].classList.remove('is-active');
					tabContentContainers[index].classList.add('is-active');

					/* Handle styles */
					tabNavigationLinks[activeIndex].removeAttribute("style");
					<?php

					if ( isset( $settings['box_border'] ) and !empty( $settings['box_border'] ) )
					{
						echo 'tabNavigationLinks[index].style.borderBottom="1px solid transparent";';

						if ( $settings['box_border'] == 'thin' )
						{
							echo 'tabNavigationLinks[activeIndex].style.borderBottom="1px solid";';
							echo 'tabNavigationLinks[index].style.border="1px solid";';
						}
						else
						{
							echo 'tabNavigationLinks[activeIndex].style.borderBottom="2px solid";';
							echo 'tabNavigationLinks[index].style.border="2px solid";';
						}

					}
					else
					{
						echo 'tabNavigationLinks[activeIndex].style.borderBottom="0";';
						echo 'tabNavigationLinks[index].style.border="0";';
					}

					if ( isset( $settings['box_border_color'] ) and !empty( $settings['box_border_color'] ) )
					{
						echo 'tabNavigationLinks[index].style.borderColor = "'.$settings['box_border_color'].'";';
						echo 'tabNavigationLinks[activeIndex].style.borderColor = "'.$settings['box_border_color'].'";';
					}

					?>

					activeIndex = index;
				}
			};

			<?php
			/**
			 * Returns init and goToTab
			 */
			?>
			return {
				init: init,
				goToTab: goToTab
			};

		};

		<?php
		/**
		 * Attach to global namespace
		 */
		?>
		window.tabs = tabs;

	})();

	var myTabs = tabs({
		el:                   '#molongui-author-box-<?php echo $random_id; ?>.molongui-tabs',
		tabNavigationLinks:   '.molongui-tabs-nav-link',
		tabContentContainers: '.molongui-tab'
	});
	myTabs.init();

</script>