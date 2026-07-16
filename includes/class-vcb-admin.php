<?php
/**
 * VCB_Admin — trang cài đặt chia tab: Preset / Content / Style.
 * Hàng cấu hình kênh sinh tự động từ VCB_Channels::registry().
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class VCB_Admin {

	public static function init(): void {
		add_action( 'admin_menu', array( __CLASS__, 'menu' ) );
	}

	public static function menu(): void {
		vig_toolkit_register_parent();
		add_submenu_page( 'vig-toolkit', 'VIG Contact Bar', 'Contact Bar', 'manage_options', 'vig-contact-bar', array( __CLASS__, 'render' ) );
	}

	private static function save(): void {
		check_admin_referer( 'vcb_save' );
		$p = wp_unslash( $_POST );

		// Preset
		update_option( 'vig_cb_preset', in_array( $p['vig_cb_preset'] ?? 'bar', array( 'bar', 'fab' ), true ) ? $p['vig_cb_preset'] : 'bar' );
		update_option( 'vig_cb_fab_style', in_array( $p['vig_cb_fab_style'] ?? 'light', array( 'light', 'colored', 'bordered' ), true ) ? $p['vig_cb_fab_style'] : 'light' );
		update_option( 'vig_cb_position', in_array( $p['vig_cb_position'] ?? 'br', array( 'br', 'bl', 'bc' ), true ) ? $p['vig_cb_position'] : 'br' );

		// Style
		update_option( 'vig_cb_color', sanitize_hex_color( $p['vig_cb_color'] ?? '' ) ?: '#2563eb' );
		update_option( 'vig_cb_show_text_desktop', isset( $p['vig_cb_show_text_desktop'] ) ? '1' : '0' );
		update_option( 'vig_cb_show_text_mobile', isset( $p['vig_cb_show_text_mobile'] ) ? '1' : '0' );
		update_option( 'vig_cb_new_tab', isset( $p['vig_cb_new_tab'] ) ? '1' : '0' );
		update_option( 'vig_cb_tawkto_hide', isset( $p['vig_cb_tawkto_hide'] ) ? '1' : '0' );

		// Content — mỗi kênh
		foreach ( VCB_Channels::registry() as $k => $def ) {
			update_option( "vig_cb_{$k}_on", isset( $p[ "vig_cb_{$k}_on" ] ) ? '1' : '0' );
			$val = $p[ "vig_cb_{$k}_value" ] ?? '';
			update_option( "vig_cb_{$k}_value", 'code' === $def['value_type'] ? $val : sanitize_text_field( $val ) );
			update_option( "vig_cb_{$k}_label", sanitize_text_field( $p[ "vig_cb_{$k}_label" ] ?? '' ) );
			update_option( "vig_cb_{$k}_order", (int) ( $p[ "vig_cb_{$k}_order" ] ?? 0 ) );
		}
		update_option( 'vig_cb_contact_shortcode', $p['vig_cb_contact_shortcode'] ?? '' );

		echo '<div class="updated"><p>Đã lưu.</p></div>';
	}

	public static function render(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		if ( isset( $_POST['vcb_save'] ) ) {
			self::save();
		}

		$preset    = get_option( 'vig_cb_preset', 'bar' );
		$fab_style = get_option( 'vig_cb_fab_style', 'light' );
		$position  = get_option( 'vig_cb_position', 'br' );
		$color     = get_option( 'vig_cb_color', '#2563eb' );
		?>
		<div class="wrap vcb-wrap">
			<h1>VIG Contact Bar</h1>
			<h2 class="nav-tab-wrapper">
				<a href="#preset"  class="nav-tab nav-tab-active" data-tab="preset">Preset</a>
				<a href="#content" class="nav-tab" data-tab="content">Content</a>
				<a href="#style"   class="nav-tab" data-tab="style">Style</a>
			</h2>

			<form method="post">
				<?php wp_nonce_field( 'vcb_save' ); ?>

				<!-- TAB: PRESET -->
				<div class="vcb-panel" data-panel="preset">
					<table class="form-table">
						<tr><th scope="row">Kiểu hiển thị</th><td>
							<?php foreach ( vig_cb_presets() as $key => $pdef ) : ?>
								<label style="margin-right:18px;"><input type="radio" name="vig_cb_preset" value="<?php echo esc_attr( $key ); ?>" <?php checked( $preset, $key ); ?>> <?php echo esc_html( $pdef['label'] ); ?></label>
							<?php endforeach; ?>
							<p class="description">Bar = thanh pill dọc có nhãn. FAB = nút tròn nổi bung ra các kênh.</p>
						</td></tr>
						<tr><th scope="row">Vị trí</th><td>
							<label style="margin-right:16px;"><input type="radio" name="vig_cb_position" value="br" <?php checked( $position, 'br' ); ?>> Dưới phải</label>
							<label style="margin-right:16px;"><input type="radio" name="vig_cb_position" value="bl" <?php checked( $position, 'bl' ); ?>> Dưới trái</label>
							<label><input type="radio" name="vig_cb_position" value="bc" <?php checked( $position, 'bc' ); ?>> Dưới giữa</label>
						</td></tr>
						<tr><th scope="row">Kiểu màu (FAB)</th><td>
							<label style="margin-right:16px;"><input type="radio" name="vig_cb_fab_style" value="light" <?php checked( $fab_style, 'light' ); ?>> Nền sáng, icon màu</label>
							<label style="margin-right:16px;"><input type="radio" name="vig_cb_fab_style" value="colored" <?php checked( $fab_style, 'colored' ); ?>> Nền màu, icon trắng</label>
							<label><input type="radio" name="vig_cb_fab_style" value="bordered" <?php checked( $fab_style, 'bordered' ); ?>> Viền màu</label>
							<p class="description">Chỉ áp dụng cho preset FAB.</p>
						</td></tr>
					</table>
				</div>

				<!-- TAB: CONTENT -->
				<div class="vcb-panel" data-panel="content" style="display:none;">
					<p class="description">Bật kênh cần dùng, điền giá trị. Thứ tự nhỏ hiện trước. Kênh nào để trống sẽ tự ẩn.</p>
					<table class="widefat striped" style="max-width:860px;">
						<thead><tr><th style="width:60px;">Bật</th><th style="width:120px;">Kênh</th><th>Giá trị</th><th style="width:170px;">Nhãn</th><th style="width:70px;">Thứ tự</th></tr></thead>
						<tbody>
						<?php $i = 0; foreach ( VCB_Channels::registry() as $k => $def ) :
							$on    = VCB_Channels::opt( $k, 'on', '0' );
							$value = VCB_Channels::opt( $k, 'value', '' );
							$label = VCB_Channels::opt( $k, 'label', '' );
							$order = VCB_Channels::opt( $k, 'order', $i );
							?>
							<tr>
								<td><input type="checkbox" name="vig_cb_<?php echo esc_attr( $k ); ?>_on" value="1" <?php checked( $on, '1' ); ?>></td>
								<td><strong><?php echo esc_html( $def['label'] ); ?></strong></td>
								<td>
									<?php if ( 'code' === $def['value_type'] ) : ?>
										<textarea name="vig_cb_<?php echo esc_attr( $k ); ?>_value" rows="3" style="width:100%;font-family:monospace;"><?php echo esc_textarea( $value ); ?></textarea>
									<?php else : ?>
										<input type="text" name="vig_cb_<?php echo esc_attr( $k ); ?>_value" value="<?php echo esc_attr( $value ); ?>" class="regular-text" placeholder="<?php echo esc_attr( $def['hint'] ); ?>">
									<?php endif; ?>
									<p class="description" style="margin:2px 0 0;"><?php echo esc_html( $def['hint'] ); ?></p>
									<?php if ( 'contact' === $k ) : ?>
										<p style="margin:6px 0 0;"><label>Form modal (shortcode):<br><input type="text" name="vig_cb_contact_shortcode" value="<?php echo esc_attr( VCB_Channels::opt( 'contact', 'shortcode', '' ) ); ?>" class="regular-text" placeholder='[contact-form-7 id="123"]'></label>
										<span class="description">Có shortcode → mở form trong popup thay vì mở link.</span></p>
									<?php endif; ?>
									<?php if ( 'tawkto' === $k ) : ?>
										<p style="margin:6px 0 0;"><label><input type="checkbox" name="vig_cb_tawkto_hide" value="1" <?php checked( get_option( 'vig_cb_tawkto_hide', '1' ), '1' ); ?>> Ẩn bong bóng chat mặc định của Tawk.to</label></p>
									<?php endif; ?>
								</td>
								<td><input type="text" name="vig_cb_<?php echo esc_attr( $k ); ?>_label" value="<?php echo esc_attr( $label ); ?>" placeholder="<?php echo esc_attr( $def['label'] ); ?>" style="width:100%;"></td>
								<td><input type="number" name="vig_cb_<?php echo esc_attr( $k ); ?>_order" value="<?php echo esc_attr( $order ); ?>" style="width:60px;"></td>
							</tr>
						<?php $i++; endforeach; ?>
						</tbody>
					</table>
				</div>

				<!-- TAB: STYLE -->
				<div class="vcb-panel" data-panel="style" style="display:none;">
					<table class="form-table">
						<tr><th scope="row">Màu thương hiệu</th><td>
							<input type="color" name="vig_cb_color" value="<?php echo esc_attr( $color ); ?>">
							<span class="description">Màu nút chính / hiệu ứng.</span>
						</td></tr>
						<tr><th scope="row">Hiện chữ (nhãn) trên nút</th><td>
							<label style="display:block;margin-bottom:6px;"><input type="checkbox" name="vig_cb_show_text_desktop" value="1" <?php checked( get_option( 'vig_cb_show_text_desktop', '1' ), '1' ); ?>> Trên <strong>Desktop</strong></label>
							<label style="display:block;"><input type="checkbox" name="vig_cb_show_text_mobile" value="1" <?php checked( get_option( 'vig_cb_show_text_mobile', '1' ), '1' ); ?>> Trên <strong>Mobile</strong></label>
							<p class="description">Áp dụng cho preset Bar (FAB luôn hiện nhãn khi hover).</p>
						</td></tr>
						<tr><th scope="row">Mở link ở tab mới</th><td>
							<label><input type="checkbox" name="vig_cb_new_tab" value="1" <?php checked( get_option( 'vig_cb_new_tab', '1' ), '1' ); ?>> Bật (áp dụng WhatsApp/Zalo/Messenger/Liên hệ)</label>
						</td></tr>
					</table>
				</div>

				<p class="submit"><button type="submit" name="vcb_save" value="1" class="button button-primary">Lưu thay đổi</button></p>
			</form>
		</div>
		<script>
		(function(){
			var tabs=document.querySelectorAll('.vcb-wrap .nav-tab'),panels=document.querySelectorAll('.vcb-wrap .vcb-panel');
			tabs.forEach(function(t){ t.addEventListener('click',function(e){
				e.preventDefault();
				tabs.forEach(function(x){x.classList.remove('nav-tab-active');}); t.classList.add('nav-tab-active');
				panels.forEach(function(p){ p.style.display = (p.getAttribute('data-panel')===t.getAttribute('data-tab'))?'':'none'; });
			});});
		})();
		</script>
		<?php
	}
}
