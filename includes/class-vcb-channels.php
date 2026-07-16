<?php
/**
 * VCB_Channels — registry các kênh liên hệ (nguồn dữ liệu chung cho mọi preset).
 * Thêm 1 kênh mới = thêm 1 dòng trong registry() + 1 icon. Preset chỉ việc lặp qua enabled().
 *
 * Option model (namespace vig_cb_*):
 *   vig_cb_preset            'bar' | 'fab'
 *   vig_cb_<key>_on          '1'|'0'   (bật kênh)
 *   vig_cb_<key>_value       giá trị (số / username / mã nhúng / url)
 *   vig_cb_<key>_label       nhãn hiển thị
 *   vig_cb_<key>_order       thứ tự (số nhỏ hiện trước)
 *   vig_cb_contact_shortcode shortcode form (kênh contact — mở modal)
 *   Style: vig_cb_color, vig_cb_position(br|bl|bc), vig_cb_fab_style(light|colored|bordered),
 *          vig_cb_show_text_desktop, vig_cb_show_text_mobile, vig_cb_new_tab, vig_cb_tawkto_hide
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class VCB_Channels {

	/** @return array<string,array> registry định nghĩa từng kênh. */
	public static function registry(): array {
		return array(
			'tawkto'    => array( 'label' => 'Chat trực tuyến', 'color' => '#1553a9', 'behavior' => 'tawk',    'value_type' => 'code', 'hint' => 'Dán mã nhúng Tawk.to' ),
			'phone'     => array( 'label' => 'Gọi điện',        'color' => '#22C55E', 'behavior' => 'link',    'value_type' => 'text', 'hint' => 'Số điện thoại' ),
			'whatsapp'  => array( 'label' => 'WhatsApp',        'color' => '#25D366', 'behavior' => 'link',    'value_type' => 'text', 'hint' => 'Số quốc tế, vd 84901234567' ),
			'zalo'      => array( 'label' => 'Zalo',            'color' => '#0068FF', 'behavior' => 'link',    'value_type' => 'text', 'hint' => 'Số điện thoại hoặc link zalo.me' ),
			'messenger' => array( 'label' => 'Messenger',       'color' => '#A855F7', 'behavior' => 'link',    'value_type' => 'text', 'hint' => 'Username hoặc link m.me' ),
			'contact'   => array( 'label' => 'Liên hệ',         'color' => '#0D9488', 'behavior' => 'contact', 'value_type' => 'text', 'hint' => 'URL trang liên hệ (hoặc dùng form modal bên dưới)' ),
		);
	}

	public static function opt( string $key, string $field, $default = '' ) {
		return get_option( "vig_cb_{$key}_{$field}", $default );
	}

	/** Thứ tự kênh theo option order (số nhỏ trước), giữ thứ tự registry khi bằng nhau. */
	public static function order(): array {
		$keys  = array_keys( self::registry() );
		$ranks = array();
		foreach ( $keys as $i => $k ) {
			$o           = self::opt( $k, 'order', '' );
			$ranks[ $k ] = ( '' === $o ) ? $i : (int) $o;
		}
		asort( $ranks );
		return array_keys( $ranks );
	}

	/**
	 * Danh sách kênh ĐANG BẬT + đã resolve href/label/icon/color, theo thứ tự.
	 * @return array<int,array{key:string,behavior:string,href:string,newtab:bool,label:string,color:string,icon:string}>
	 */
	public static function enabled(): array {
		$reg     = self::registry();
		$new_tab = get_option( 'vig_cb_new_tab', '1' ) === '1';
		$out     = array();

		foreach ( self::order() as $k ) {
			if ( ! isset( $reg[ $k ] ) || self::opt( $k, 'on', '0' ) !== '1' ) {
				continue;
			}
			$def      = $reg[ $k ];
			$value    = trim( (string) self::opt( $k, 'value', '' ) );
			$behavior = $def['behavior'];
			$href     = '#';
			$newtab   = false;

			if ( 'link' === $behavior ) {
				if ( '' === $value ) {
					continue;
				}
				$href   = self::build_href( $k, $value );
				$newtab = $new_tab && 'phone' !== $k;
			} elseif ( 'tawk' === $behavior ) {
				if ( '' === $value ) {
					continue; // chưa có mã nhúng
				}
			} elseif ( 'contact' === $behavior ) {
				$sc = trim( (string) self::opt( 'contact', 'shortcode', '' ) );
				if ( '' === $sc && '' === $value ) {
					continue;
				}
				$href   = ( '' !== $value ) ? $value : '#';
				$newtab = ( '' === $sc && '' !== $value ) ? $new_tab : false;
			}

			$label = trim( (string) self::opt( $k, 'label', '' ) );
			if ( '' === $label ) {
				$label = $def['label'];
			}

			$out[] = array(
				'key'      => $k,
				'behavior' => $behavior,
				'href'     => $href,
				'newtab'   => $newtab,
				'label'    => vig_cb_pll( $label ),
				'color'    => $def['color'],
				'icon'     => self::icon( $k ),
			);
		}
		return $out;
	}

	private static function build_href( string $k, string $v ): string {
		switch ( $k ) {
			case 'phone':
				return 'tel:' . preg_replace( '/[^0-9+]/', '', $v );
			case 'whatsapp':
				return 'https://wa.me/' . preg_replace( '/[^0-9]/', '', $v );
			case 'zalo':
				return ( 0 === strpos( $v, 'http' ) ) ? $v : 'https://zalo.me/' . preg_replace( '/[^0-9]/', '', $v );
			case 'messenger':
				return ( 0 === strpos( $v, 'http' ) ) ? $v : 'https://m.me/' . ltrim( $v, '/@' );
			default:
				return $v;
		}
	}

	/** Icon SVG (currentColor để đổi màu theo kiểu). */
	public static function icon( string $k ): string {
		$icons = array(
			'tawkto'    => '<svg viewBox="0 0 24 24" width="23" height="23" fill="currentColor"><path d="M12 3C6.5 3 2 6.9 2 11.7c0 2.4 1.2 4.6 3.1 6.1L4 22l4.5-2.2c1.1.3 2.3.5 3.5.5 5.5 0 10-3.9 10-8.6S17.5 3 12 3z"/></svg>',
			'phone'     => '<svg viewBox="0 0 24 24" width="24" height="24" fill="currentColor"><path d="M6.62 10.79a15.15 15.15 0 006.59 6.59l2.2-2.2a1 1 0 011.11-.27c1.12.45 2.33.69 3.58.69a1 1 0 011 1V20a1 1 0 01-1 1A17 17 0 013 4a1 1 0 011-1h3.5a1 1 0 011 1c0 1.25.24 2.46.69 3.58a1 1 0 01-.27 1.11z"/></svg>',
			'whatsapp'  => '<svg viewBox="0 0 24 24" width="24" height="24" fill="currentColor"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.45 1.32 4.95L2 22l5.25-1.38a9.9 9.9 0 004.79 1.22h.01c5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.82 9.82 0 0012.04 2zm5.8 14.16c-.24.68-1.4 1.3-1.94 1.38-.5.07-1.12.1-1.81-.11-.42-.13-.95-.31-1.64-.6-2.88-1.24-4.76-4.14-4.9-4.33-.14-.19-1.17-1.56-1.17-2.97 0-1.41.74-2.11 1-2.4.26-.28.57-.35.76-.35h.55c.18 0 .42-.07.65.5.24.58.82 2 .89 2.14.07.14.12.31.02.5-.09.19-.14.31-.28.48-.14.17-.29.37-.42.5-.14.14-.28.29-.12.57.16.28.72 1.18 1.54 1.91 1.06.95 1.96 1.24 2.24 1.38.28.14.44.12.6-.07.16-.19.69-.8.87-1.08.18-.28.37-.23.62-.14.25.09 1.61.76 1.89.9.28.14.46.21.53.33.07.12.07.68-.17 1.36z"/></svg>',
			'zalo'      => '<svg viewBox="0 0 24 24" width="23" height="23" fill="currentColor"><path d="M12 3C6.9 3 2.75 6.42 2.75 10.64c0 2.4 1.35 4.54 3.46 5.95-.13.9-.5 1.9-1.17 2.68-.17.2-.03.5.23.47 1.6-.2 2.86-.78 3.7-1.3.72.14 1.47.22 2.28.22 5.1 0 9.25-3.42 9.25-7.64S17.1 3 12 3zm-4.4 9.3H5.55c-.3 0-.5-.23-.5-.5 0-.13.05-.26.14-.36l2.03-2.5H5.7c-.28 0-.5-.22-.5-.5s.22-.5.5-.5h1.9c.3 0 .5.22.5.5 0 .13-.04.25-.13.35l-2.03 2.5H7.6c.28 0 .5.23.5.5s-.22.5-.5.5zm2.2 0c-.28 0-.5-.22-.5-.5V8.44c0-.28.22-.5.5-.5s.5.22.5.5v3.36c0 .28-.22.5-.5.5zm7.15 0c-.2 0-.38-.12-.46-.3-.3.2-.66.32-1.05.32-1.05 0-1.9-.86-1.9-1.92s.85-1.92 1.9-1.92c.39 0 .75.12 1.05.32.08-.18.26-.3.46-.3.28 0 .5.22.5.5v2.8c0 .28-.22.5-.5.5zm-1.5-.98c.5 0 .9-.42.9-.94s-.4-.94-.9-.94-.9.42-.9.94.4.94.9.94z"/></svg>',
			'messenger' => '<svg viewBox="0 0 24 24" width="24" height="24" fill="currentColor"><path d="M12 2C6.48 2 2 6.14 2 11.25c0 2.9 1.46 5.5 3.75 7.15V22l3.42-1.88c.83.23 1.7.35 2.6.35 5.52 0 10-4.14 10-9.25S17.52 2 12 2zm1.09 12.33l-2.43-2.59-4.75 2.59 5.22-5.54 2.5 2.59 4.7-2.59-5.24 5.54z"/></svg>',
			'contact'   => '<svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor"><path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zM6 20V4h7v5h5v11H6z"/><path d="M8 12h8v2H8zm0 4h8v2H8zm0-8h3v2H8z" opacity=".5"/></svg>',
		);
		return $icons[ $k ] ?? '';
	}

	/** Migrate 1 lần từ options cũ (vig_contact_bar_*) sang model mới (vig_cb_*). */
	public static function maybe_migrate(): void {
		if ( get_option( 'vig_cb_migrated' ) ) {
			return;
		}
		$map_val = array(
			'tawkto'    => 'vig_contact_bar_tawkto_code',
			'whatsapp'  => 'vig_contact_bar_whatsapp_number',
			'phone'     => 'vig_contact_bar_phone',
			'zalo'      => 'vig_contact_bar_zalo',
			'messenger' => 'vig_contact_bar_messenger',
			'contact'   => 'vig_contact_bar_contact_url',
		);
		foreach ( $map_val as $k => $old ) {
			$v = get_option( $old, '' );
			if ( '' !== $v && '#' !== $v ) {
				update_option( "vig_cb_{$k}_value", $v );
				update_option( "vig_cb_{$k}_on", '1' );
			}
		}
		$sc = get_option( 'vig_contact_bar_contact_shortcode', '' );
		if ( '' !== $sc ) {
			update_option( 'vig_cb_contact_shortcode', $sc );
			update_option( 'vig_cb_contact_on', '1' );
		}
		// nhãn cũ
		foreach ( array( 'tawkto', 'whatsapp', 'contact' ) as $k ) {
			$t = get_option( "vig_contact_bar_{$k}_title", '' );
			if ( '' !== $t ) {
				update_option( "vig_cb_{$k}_label", $t );
			}
		}
		// preset + style
		$old_preset = get_option( 'vig_contact_bar_preset', 'hotline' );
		update_option( 'vig_cb_preset', ( 'fab-leondio' === $old_preset ) ? 'fab' : 'bar' );
		update_option( 'vig_cb_fab_style', get_option( 'vig_contact_bar_fab_style', 'light' ) );
		update_option( 'vig_cb_color', get_option( 'vig_contact_bar_fab_color', '#2563eb' ) );
		update_option( 'vig_cb_show_text_desktop', get_option( 'vig_contact_bar_show_text_desktop', '1' ) );
		update_option( 'vig_cb_show_text_mobile', get_option( 'vig_contact_bar_show_text_mobile', '1' ) );
		update_option( 'vig_cb_new_tab', get_option( 'vig_contact_bar_new_tab', '1' ) );
		update_option( 'vig_cb_tawkto_hide', get_option( 'vig_contact_bar_tawkto_hide_default', '1' ) );

		update_option( 'vig_cb_migrated', '1' );
	}
}
