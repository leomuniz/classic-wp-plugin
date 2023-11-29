<?php
/**
 * Classic WP Plugin
 *
 * @package           classic-wp-plugin
 * @author            Léo Muniz
 * @copyright         2023 Léo Muniz
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name: Classic WP Plugin
 * Plugin URI: https://leomuniz.dev
 * Description: A classic style plugin with a custom table, shortcodes to insert and retrieve data and public REST API endpoints.
 * Version: 1.0.0
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * Author: Leo Muniz
 * Author URI: https://leomuniz.dev
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: classic-wp-plugin
 * Domain Path: /languages
 */

namespace ClassicWPPlugin;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Plugin version constant.
 *
 * @since 1.0.0
 * @const string
 */
define( __NAMESPACE__ . '\VERSION', '1.0.0' );

/**
 * Plugin folder constant.
 *
 * @since 1.0.0
 * @const string
 */
define( __NAMESPACE__ . '\DIR', __DIR__ );

/**
 * Plugin folder URL.
 *
 * @since 1.0.0
 * @const string
 */
define( __NAMESPACE__ . '\URL', plugin_dir_url( __FILE__ ) );

require_once 'includes/constants.php';
require_once DIR . '/vendor/autoload.php';

Core\Plugin::get_instance();
