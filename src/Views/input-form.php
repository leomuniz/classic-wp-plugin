<?php
/**
 * Input form HTML view. Used by [classic_wp_display_form] shortcode.
 *
 * @package Classic_WP_Plugin
 */

/**
 * Executes before displaying the form shortcode classic_wp_display_form.
 *
 * @since 1.0.0
 */
do_action( 'classic_wp_plugin_before_display_form' );

?>

<?php if ( false === $vars['inserted'] ) : ?>
	<p class="classic_wp_plugin_error_message"><?php esc_html_e( 'There was an error on your submission. Please try again.', 'classic-wp-plugin' ); ?></p>
<?php endif; ?>

<form action="" method="POST">
	<fieldset>
		<legend><?php esc_html_e( 'Submit a Review', 'classic-wp-plugin' ); ?></legend>
		<?php wp_nonce_field( 'classic_wp_plugin_nonce_' . get_the_ID(), '_wp_nonce' ); ?>

		<label for="name"><?php esc_html_e( 'Name', 'classic-wp-plugin' ); ?>*</label>
		<input type="text" id="name" name="classic_wp_plugin_name" placeholder="<?php esc_attr_e( 'Enter your name', 'classic-wp-plugin' ); ?>" required>

		<label for="email"><?php esc_html_e( 'Email', 'classic-wp-plugin' ); ?>*</label>
		<input type="email" id="email" name="classic_wp_plugin_email" placeholder="<?php esc_attr_e( 'Enter your e-mail', 'classic-wp-plugin' ); ?>" required>

		<label for="rating"><?php esc_html_e( 'Rating', 'classic-wp-plugin' ); ?>* (1 to 5)</label>
		<input type="number" id="rating" name="classic_wp_plugin_rating" min="1" max="5" placeholder="<?php esc_attr_e( 'Enter your rating between 1 and 5', 'classic-wp-plugin' ); ?>" required>

		<label for="comment"><?php esc_html_e( 'Comment', 'classic-wp-plugin' ); ?></label>
		<textarea id="comment" name="classic_wp_plugin_comment" rows="4" placeholder="<?php esc_attr_e( 'Enter your comment', 'classic-wp-plugin' ); ?>"></textarea>
	</fieldset>

	<input type="submit" value="<?php esc_attr_e( 'Submit Review', 'classic-wp-plugin' ); ?>">
</form>

<?php
/**
 * Executes after displaying the form shortcode classic_wp_display_form.
 *
 * @since 1.0.0
 */
do_action( 'classic_wp_plugin_after_display_form' );
