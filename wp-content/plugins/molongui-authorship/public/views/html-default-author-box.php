<?php

/**
 * Provide a public-facing view for the plugin.
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @author     Amitzy
 * @package    Molongui_Authorship
 * @subpackage Molongui_Authorship/public/views
 * @since      1.0.0
 * @version    1.3.0
 */
?>

<!-- MOLONGUI AUTHORSHIP PLUGIN <?php echo MOLONGUI_AUTHORSHIP_VERSION ?> -->
<!-- <?php echo "https:" . MOLONGUI_AUTHORSHIP_WEB ?> -->
<div id="molongui-author-box-<?php echo $random_id; ?>" class="molongui-table molongui-author-box-container" itemscope itemtype="https://schema.org/Person">

	<div id="molongui-author-box-wrapper"
	     class="mabc-shadow-<?php echo ( ( isset( $settings['box_shadow'] ) and !empty( $settings['box_shadow'] ) ) ? $settings['box_shadow'] : 'left' );?>
				mabc-border-<?php echo ( ( isset( $settings['box_border'] ) and !empty( $settings['box_border'] ) ) ? $settings['box_border'] : 'none' );?>
				mabc-bckg-<?php echo ( ( isset( $settings['box_background'] ) and !empty( $settings['box_background'] ) ) ? 'coloured' : 'none' );?>"
		 style="<?php echo ( ( isset( $settings['box_border_color'] ) and !empty( $settings['box_border_color'] ) ) ? 'border-color: ' . $settings['box_border_color'] . ';' : '' );?>
			    <?php echo ( ( isset( $settings['box_background'] ) and !empty( $settings['box_background'] ) ) ? 'background-color: ' . $settings['box_background'] . ';' : '' );?>">

		<!-- Author thumbnail -->

		<?php include( MOLONGUI_AUTHORSHIP_DIR . '/public/views/parts/author-box-thumbnail.php' ); ?>

		<!-- Author social -->
		<?php include( MOLONGUI_AUTHORSHIP_DIR . '/public/views/parts/author-box-socialmedia.php' ); ?>

		<!-- Author data -->

		<div class="molongui-table-cell molongui-author-box-data">

			<!-- Author name -->
			<?php include( MOLONGUI_AUTHORSHIP_DIR . '/public/views/parts/author-box-title.php' ); ?>

			<!-- Author metadata -->
			<?php include( MOLONGUI_AUTHORSHIP_DIR . '/public/views/parts/author-box-meta.php' ); ?>

			<!-- Author bio -->
			<?php include( MOLONGUI_AUTHORSHIP_DIR . '/public/views/parts/author-box-bio.php' ); ?>

			<!-- Author related posts -->
			<?php include( MOLONGUI_AUTHORSHIP_DIR . '/public/views/parts/author-box-related.php' ); ?>

		</div><!-- End molongui-author-box-data -->

	</div><!-- End molongui-author-box-wrapper -->

</div><!-- End molongui-author-box-container -->