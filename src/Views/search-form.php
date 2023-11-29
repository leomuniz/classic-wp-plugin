<?php
/**
 * Search form to filter the table. Used by [classic_wp_display_list] shortcode.
 *
 * @package Classic_WP_Plugin
 */

?>
<form action="" method="POST">
	<fieldset>
		<legend><?php esc_html_e( 'Search review', 'classic-wp-plugin' ); ?></legend>

		<?php wp_nonce_field( 'classic_wp_plugin_search_nonce_' . get_the_ID(), '_wp_nonce' ); ?>

		<label for="search"><?php esc_html_e( 'Search', 'classic-wp-plugin' ); ?>:</label>
		<input type="text" id="search" name="classic_wp_plugin_q" placeholder="<?php esc_attr_e( 'Enter text', 'classic-wp-plugin' ); ?>" value="<?php echo esc_attr( $vars['search_query'] ); ?>">
	</fieldset>

	<input type="submit" value="<?php esc_attr_e( 'Search', 'classic-wp-plugin' ); ?>">
</form>
