<?php
/**
 * Plugin Name: TDD Exercise
 * Description: Test Driven Development in WordPress.
 * Version: 1.0.0
 * Author: Suleman
 * Author URI: https://suleman-help.me
 * Text Domain: tdd-plugin
 *
 * @package TDD
 */

namespace Suleman\TDD;

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

// site-level auto-loading.
if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
}

$suleman_primary_category = new Primary_Category( __FILE__ );
