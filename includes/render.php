<?php
/**
 * Front-end render: dispatch theo preset + phần dùng chung (Tawk.to, modal contact, JS chung).
 * Thêm preset mới = thêm 1 entry vào vig_cb_presets() + 1 file render trong presets/.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Bản dịch Polylang nếu có. */
function vig_cb_pll( $s ) {
	return function_exists( 'pll__' ) ? pll__( $s ) : $s;
}

/** Registry preset (layout). @return array<string,array{label:string,render:callable}> */
function vig_cb_presets(): array {
	return array(
		'bar' => array( 'label' => 'Thanh (Bar)',   'render' => 'vig_cb_render_bar' ),
		'fab' => array( 'label' => 'Nút nổi (FAB)',  'render' => 'vig_cb_render_fab' ),
	);
}

/** Style hiện tại (dùng chung cho mọi preset). */
function vig_cb_style(): array {
	return array(
		'color'        => get_option( 'vig_cb_color', '#2563eb' ),
		'position'     => in_array( get_option( 'vig_cb_position', 'br' ), array( 'br', 'bl', 'bc' ), true ) ? get_option( 'vig_cb_position', 'br' ) : 'br',
		'fab_style'    => in_array( get_option( 'vig_cb_fab_style', 'light' ), array( 'light', 'colored', 'bordered' ), true ) ? get_option( 'vig_cb_fab_style', 'light' ) : 'light',
		'text_desktop' => get_option( 'vig_cb_show_text_desktop', '1' ) === '1',
		'text_mobile'  => get_option( 'vig_cb_show_text_mobile', '1' ) === '1',
		'always_open'  => get_option( 'vig_cb_always_open', '0' ) === '1',
	);
}

/** CSS vị trí (offset cạnh) cho wrapper. */
function vig_cb_position_css( string $pos ): string {
	switch ( $pos ) {
		case 'bl':
			return 'bottom:24px;left:24px;right:auto;';
		case 'bc':
			return 'bottom:0;left:50%;right:auto;transform:translateX(-50%);';
		default:
			return 'bottom:24px;right:24px;left:auto;';
	}
}

/** href + attribute cho 1 kênh (xử lý tawk/modal đặc biệt). @return array{0:string,1:string} */
function vig_cb_item_link( array $ch ): array {
	if ( 'tawk' === $ch['behavior'] ) {
		return array( '#', 'data-vcb="tawk"' );
	}
	if ( 'contact' === $ch['behavior'] && '' !== trim( (string) VCB_Channels::opt( 'contact', 'shortcode', '' ) ) ) {
		return array( '#', 'data-vcb="modal"' );
	}
	return array( $ch['href'], $ch['newtab'] ? 'target="_blank" rel="noopener"' : '' );
}

/* ------------------------------------------------------------------ Footer dispatch */

add_action( 'wp_footer', 'vig_cb_footer' );
function vig_cb_footer(): void {
	vig_cb_inject_tawk(); // nạp Tawk nếu kênh tawkto có mã (độc lập với preset)

	$channels = VCB_Channels::enabled();
	if ( empty( $channels ) ) {
		return;
	}

	vig_cb_contact_modal(); // modal form nếu kênh contact dùng shortcode

	$presets = vig_cb_presets();
	$preset  = get_option( 'vig_cb_preset', 'bar' );
	$fn      = isset( $presets[ $preset ] ) ? $presets[ $preset ]['render'] : 'vig_cb_render_bar';
	call_user_func( $fn, $channels, vig_cb_style() );

	vig_cb_shared_js();
}

/** JS chung: trigger Tawk + mở/đóng modal (dùng data-vcb). */
function vig_cb_shared_js(): void {
	?>
	<script>
	(function(){
		document.addEventListener('click', function(e){
			var t = e.target.closest ? e.target.closest('[data-vcb]') : null;
			if(!t) return;
			var a = t.getAttribute('data-vcb');
			if(a === 'tawk'){
				e.preventDefault();
				if(typeof Tawk_API === 'undefined') return;
				if(Tawk_API.isChatMaximized && Tawk_API.isChatMaximized()){ Tawk_API.minimize && Tawk_API.minimize(); }
				else if(Tawk_API.maximize){ Tawk_API.maximize(); } else if(Tawk_API.toggle){ Tawk_API.toggle(); }
			} else if(a === 'modal'){
				e.preventDefault();
				var m = document.getElementById('vcb-modal'); if(m) m.style.display='block';
			} else if(a === 'modal-close'){
				var m = document.getElementById('vcb-modal'); if(m) m.style.display='none';
			}
		});
	})();
	</script>
	<?php
}

/* ------------------------------------------------------------------ Tawk.to */

function vig_cb_inject_tawk(): void {
	if ( VCB_Channels::opt( 'tawkto', 'on', '0' ) !== '1' ) {
		return;
	}
	$code = (string) VCB_Channels::opt( 'tawkto', 'value', '' );
	if ( '' === trim( $code ) ) {
		return;
	}
	$hide        = get_option( 'vig_cb_tawkto_hide', '1' ) === '1';
	$autoopen    = get_option( 'vig_cb_tawkto_autoopen', '0' ) === '1';
	$chat_bottom = (int) get_option( 'vig_cb_tawkto_chat_bottom', '0' ); // >0 = hạ khung chat sát đáy (px)
	?>
	<script type="text/javascript">
		var Tawk_API = Tawk_API || {};
		Tawk_API.customStyle = { visibility: {
			desktop: { position: 'br', xOffset: 90, yOffset: 20 },
			mobile:  { position: 'br', xOffset: 0,  yOffset: 0 }
		}};
		(function(){
			var HIDE = <?php echo $hide ? 'true' : 'false'; ?>;
			var AUTOOPEN = <?php echo $autoopen ? 'true' : 'false'; ?>;
			var CHAT_BOTTOM = <?php echo max( 0, $chat_bottom ); ?>;   // px cách đáy; 0 = không đụng

			function hideBubble(){
				var f = document.querySelectorAll('iframe');
				for(var i=0;i<f.length;i++){
					var s = f[i].getAttribute('style') || '';
					if(s.indexOf('max-width:64px')>-1 || s.indexOf('max-width: 64px')>-1 || s.indexOf('max-height:45px')>-1 || s.indexOf('max-height: 45px')>-1){
						f[i].style.setProperty('display','none','important');
					}
				}
			}
			// Hạ khung chat MAXIMIZED xuống sát đáy. Tawk đặt vị trí bằng inline !important
			// nên phải ép qua JS. Bỏ qua bong bóng nhỏ + bỏ qua mobile fullscreen (khỏi phá layout).
			function lowerChat(){
				if(!CHAT_BOTTOM) return;
				var vh = window.innerHeight, f = document.querySelectorAll('iframe');
				for(var i=0;i<f.length;i++){
					var r = f[i].getBoundingClientRect();
					if(r.width>=260 && r.height>=300 && r.height < vh*0.92){
						f[i].style.setProperty('bottom', CHAT_BOTTOM+'px','important');
						f[i].style.setProperty('top','auto','important');
					}
				}
			}
			// Tự mở 1 LẦN mỗi phiên: chuyển trang trong cùng phiên không bung lại; khách tự đóng thì tôn trọng.
			function wantAutoOpen(){
				if(!AUTOOPEN) return false;
				try {
					if(sessionStorage.getItem('vcb_chat_opened')) return false;
					sessionStorage.setItem('vcb_chat_opened','1');
				} catch(e){}
				return true;
			}
			function afterMaximize(){
				if(HIDE){ setTimeout(hideBubble,300); }
				setTimeout(lowerChat,150); setTimeout(lowerChat,600);
			}

			Tawk_API.onLoad = function(){
				if(wantAutoOpen()){
					if(Tawk_API.maximize) Tawk_API.maximize();
					return;
				}
				if(HIDE){ if(Tawk_API.hideWidget) Tawk_API.hideWidget(); hideBubble(); }
			};
			Tawk_API.onChatMaximized = afterMaximize;

			if(HIDE){
				document.addEventListener('DOMContentLoaded', function(){
					hideBubble();
					new MutationObserver(hideBubble).observe(document.body,{childList:true,subtree:true,attributes:true,attributeFilter:['style']});
				});
			}
			if(CHAT_BOTTOM){ window.addEventListener('resize', function(){ setTimeout(lowerChat,200); }); }
		})();
	</script>
	<style>.tawk-min-container{display:none!important;}</style>
	<?php
	echo $code; // phpcs:ignore WordPress.Security.EscapeOutput -- mã nhúng do admin nhập
}

/* ------------------------------------------------------------------ Contact modal */

function vig_cb_contact_modal(): void {
	if ( VCB_Channels::opt( 'contact', 'on', '0' ) !== '1' ) {
		return;
	}
	$sc = trim( (string) VCB_Channels::opt( 'contact', 'shortcode', '' ) );
	if ( '' === $sc ) {
		return;
	}
	?>
	<div id="vcb-modal" class="vcb-modal">
		<div class="vcb-modal-overlay" data-vcb="modal-close"></div>
		<div class="vcb-modal-content">
			<span class="vcb-modal-close" data-vcb="modal-close">&times;</span>
			<div class="vcb-modal-body"><?php echo do_shortcode( $sc ); ?></div>
		</div>
	</div>
	<style>
		.vcb-modal{position:fixed;inset:0;z-index:1000000;display:none}
		.vcb-modal-overlay{position:absolute;inset:0;background:rgba(0,0,0,.45);backdrop-filter:blur(4px)}
		.vcb-modal-content{position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);background:#fff;padding:30px 24px 24px;border-radius:16px;width:90%;max-width:480px;box-shadow:0 10px 30px rgba(0,0,0,.25);z-index:1000001}
		.vcb-modal-close{position:absolute;top:8px;right:14px;font-size:28px;color:#a0aec0;cursor:pointer;line-height:1}
		.vcb-modal-body{max-height:75vh;overflow-y:auto}
	</style>
	<?php
}
