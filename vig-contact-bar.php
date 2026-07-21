<?php
/**
 * Plugin Name: VIG Contact Bar
 * Plugin URI:  https://vigdigital.com
 * Description: Thanh liên hệ nổi đa kênh (Phone, WhatsApp, Zalo, Messenger, Tawk.to, Form) với nhiều preset (Bar / FAB). Cấu trúc theo channel registry, dễ mở rộng. Responsive.
 * Version:     1.3.1
 * Author:      VIG Digital
 * Author URI:  https://vigdigital.com
 * License:     GPL-2.0-or-later
 * Text Domain: vig-contact-bar
 * Update URI:  https://github.com/vigdigital/vig-contact-bar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'VIG_CONTACT_BAR_VERSION', '1.3.1' );

// Menu chung + tự-update (dùng chung mọi plugin VIG).
require_once __DIR__ . '/includes/vig-admin-menu.php';
require_once __DIR__ . '/includes/vig-update-checker.php';
vig_setup_updates( __FILE__, 'vig-contact-bar', 'vigdigital', true );

// Core: channels + render + presets + admin.
require_once __DIR__ . '/includes/class-vcb-channels.php';
require_once __DIR__ . '/includes/i18n.php';
require_once __DIR__ . '/includes/render.php';
require_once __DIR__ . '/includes/presets/bar.php';
require_once __DIR__ . '/includes/presets/fab.php';
require_once __DIR__ . '/includes/class-vcb-admin.php';

VCB_Admin::init();

// Migrate options cũ (vig_contact_bar_*) sang model mới — chạy 1 lần.
add_action( 'init', array( 'VCB_Channels', 'maybe_migrate' ), 1 );

// Polylang: đăng ký nhãn kênh để dịch.
add_action( 'init', 'vig_cb_register_strings' );
function vig_cb_register_strings() {
	if ( ! function_exists( 'pll_register_string' ) ) {
		return;
	}
	foreach ( VCB_Channels::registry() as $k => $def ) {
		$label = VCB_Channels::opt( $k, 'label', '' );
		pll_register_string( "vig_cb_{$k}", '' !== $label ? $label : $def['label'], 'VIG Contact Bar' );
	}
}
