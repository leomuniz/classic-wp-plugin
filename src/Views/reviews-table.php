<?php
/**
 * Reviews table. Used by [classic_wp_display_list] shortcode.
 *
 * @package Classic_WP_Plugin
 */

?>
<table>
	<thead>
		<tr>
			<th><?php esc_html_e( 'Name', 'classic-wp-plugin' ); ?></th>
			<th><?php esc_html_e( 'Rating', 'classic-wp-plugin' ); ?></th>
			<th><?php esc_html_e( 'Comment', 'classic-wp-plugin' ); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php if ( empty( $vars['reviews'] ) ) : ?>
			<tr>
				<td colspan="3">No reviews found!</td>
			</tr>
		<?php else : ?>
			<?php foreach ( $vars['reviews'] as $review ) : ?>
				<tr>
					<td><?php echo esc_html( $review->name ); ?></td>
					<td><?php echo esc_html( $review->rating ); ?></td>
					<td><?php echo esc_html( $review->comment ); ?></td>
				</tr>
			<?php endforeach; ?>
		<?php endif; ?>
	</tbody>
</table>