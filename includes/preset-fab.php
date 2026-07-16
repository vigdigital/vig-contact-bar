<?php
/**
 * Preset "Leon Dio (FAB)" — nút tròn nổi góc phải, bấm bung ra các kênh (phone,
 * WhatsApp, Zalo, Messenger, Contact) dạng nút tròn xếp dọc + nhãn hover.
 * 3 kiểu màu: light / colored / bordered. Mặc định màu gold Leon Dio.
 * Self-contained (CSS/JS inline, icon SVG currentColor — không hotlink ngoài).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** SVG icon dùng currentColor để đổi màu theo kiểu. */
function vig_contact_bar_fab_icon( $key ) {
	$icons = array(
		'phone'     => '<svg viewBox="0 0 24 24" width="24" height="24" fill="currentColor"><path d="M6.62 10.79a15.15 15.15 0 006.59 6.59l2.2-2.2a1 1 0 011.11-.27c1.12.45 2.33.69 3.58.69a1 1 0 011 1V20a1 1 0 01-1 1A17 17 0 013 4a1 1 0 011-1h3.5a1 1 0 011 1c0 1.25.24 2.46.69 3.58a1 1 0 01-.27 1.11z"/></svg>',
		'whatsapp'  => '<svg viewBox="0 0 24 24" width="24" height="24" fill="currentColor"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.45 1.32 4.95L2 22l5.25-1.38a9.9 9.9 0 004.79 1.22h.01c5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.82 9.82 0 0012.04 2zm5.8 14.16c-.24.68-1.4 1.3-1.94 1.38-.5.07-1.12.1-1.81-.11-.42-.13-.95-.31-1.64-.6-2.88-1.24-4.76-4.14-4.9-4.33-.14-.19-1.17-1.56-1.17-2.97 0-1.41.74-2.11 1-2.4.26-.28.57-.35.76-.35h.55c.18 0 .42-.07.65.5.24.58.82 2 .89 2.14.07.14.12.31.02.5-.09.19-.14.31-.28.48-.14.17-.29.37-.42.5-.14.14-.28.29-.12.57.16.28.72 1.18 1.54 1.91 1.06.95 1.96 1.24 2.24 1.38.28.14.44.12.6-.07.16-.19.69-.8.87-1.08.18-.28.37-.23.62-.14.25.09 1.61.76 1.89.9.28.14.46.21.53.33.07.12.07.68-.17 1.36z"/></svg>',
		'zalo'      => '<svg viewBox="0 0 24 24" width="23" height="23" fill="currentColor"><path d="M12 3C6.9 3 2.75 6.42 2.75 10.64c0 2.4 1.35 4.54 3.46 5.95-.13.9-.5 1.9-1.17 2.68-.17.2-.03.5.23.47 1.6-.2 2.86-.78 3.7-1.3.72.14 1.47.22 2.28.22 5.1 0 9.25-3.42 9.25-7.64S17.1 3 12 3zm-4.4 9.3H5.55c-.3 0-.5-.23-.5-.5 0-.13.05-.26.14-.36l2.03-2.5H5.7c-.28 0-.5-.22-.5-.5s.22-.5.5-.5h1.9c.3 0 .5.22.5.5 0 .13-.04.25-.13.35l-2.03 2.5H7.6c.28 0 .5.23.5.5s-.22.5-.5.5zm2.2 0c-.28 0-.5-.22-.5-.5V8.44c0-.28.22-.5.5-.5s.5.22.5.5v3.36c0 .28-.22.5-.5.5zm7.15 0c-.2 0-.38-.12-.46-.3-.3.2-.66.32-1.05.32-1.05 0-1.9-.86-1.9-1.92s.85-1.92 1.9-1.92c.39 0 .75.12 1.05.32.08-.18.26-.3.46-.3.28 0 .5.22.5.5v2.8c0 .28-.22.5-.5.5zm-1.5-.98c.5 0 .9-.42.9-.94s-.4-.94-.9-.94-.9.42-.9.94.4.94.9.94z"/></svg>',
		'messenger' => '<svg viewBox="0 0 24 24" width="24" height="24" fill="currentColor"><path d="M12 2C6.48 2 2 6.14 2 11.25c0 2.9 1.46 5.5 3.75 7.15V22l3.42-1.88c.83.23 1.7.35 2.6.35 5.52 0 10-4.14 10-9.25S17.52 2 12 2zm1.09 12.33l-2.43-2.59-4.75 2.59 5.22-5.54 2.5 2.59 4.7-2.59-5.24 5.54z"/></svg>',
		'contact'   => '<svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor"><path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zM6 20V4h7v5h5v11H6z"/><path d="M8 12h8v2H8zm0 4h8v2H8zm0-8h3v2H8z" opacity=".5"/></svg>',
	);
	return $icons[ $key ] ?? '';
}

function vig_contact_bar_render_fab() {
	$new_tab = get_option( 'vig_contact_bar_new_tab', '1' ) === '1';
	$target  = $new_tab ? 'target="_blank" rel="noopener"' : '';

	$color = get_option( 'vig_contact_bar_fab_color', '#c5a059' ); // gold Leon Dio
	$style = get_option( 'vig_contact_bar_fab_style', 'light' );   // light|colored|bordered
	$style = in_array( $style, array( 'light', 'colored', 'bordered' ), true ) ? $style : 'light';

	// Thu thập kênh đã cấu hình.
	$items = array();

	$phone = get_option( 'vig_contact_bar_phone', '' );
	if ( $phone ) {
		$items[] = array( 'k' => 'phone', 'href' => 'tel:' . preg_replace( '/[^0-9+]/', '', $phone ), 'label' => get_option( 'vig_contact_bar_phone_label', 'Gọi ngay' ), 'c' => '#22C55E', 'newtab' => false );
	}
	$wa = get_option( 'vig_contact_bar_whatsapp_number', '' );
	if ( $wa ) {
		$items[] = array( 'k' => 'whatsapp', 'href' => 'https://wa.me/' . preg_replace( '/[^0-9]/', '', $wa ), 'label' => get_option( 'vig_contact_bar_whatsapp_title', 'WhatsApp' ), 'c' => '#25D366', 'newtab' => true );
	}
	$zalo = get_option( 'vig_contact_bar_zalo', '' );
	if ( $zalo ) {
		$zurl    = ( 0 === strpos( $zalo, 'http' ) ) ? $zalo : 'https://zalo.me/' . preg_replace( '/[^0-9]/', '', $zalo );
		$items[] = array( 'k' => 'zalo', 'href' => $zurl, 'label' => get_option( 'vig_contact_bar_zalo_label', 'Zalo' ), 'c' => '#0068FF', 'newtab' => true );
	}
	$ms = get_option( 'vig_contact_bar_messenger', '' );
	if ( $ms ) {
		$murl    = ( 0 === strpos( $ms, 'http' ) ) ? $ms : 'https://m.me/' . ltrim( $ms, '/@' );
		$items[] = array( 'k' => 'messenger', 'href' => $murl, 'label' => get_option( 'vig_contact_bar_messenger_label', 'Messenger' ), 'c' => '#A855F7', 'newtab' => true );
	}
	$contact_url = get_option( 'vig_contact_bar_contact_url', '' );
	if ( $contact_url ) {
		$items[] = array( 'k' => 'contact', 'href' => $contact_url, 'label' => get_option( 'vig_contact_bar_contact_title', 'Liên hệ' ), 'c' => '#0D9488', 'newtab' => true );
	}

	if ( empty( $items ) ) {
		return; // chưa cấu hình kênh nào
	}
	?>
	<div id="vcb-fab" class="vcb-fab vcb-fab-<?php echo esc_attr( $style ); ?>" style="--vcb-brand:<?php echo esc_attr( $color ); ?>">
		<button class="vcb-fab-btn vcb-fab-trigger" id="vcbFabTrigger" aria-label="Liên hệ">
			<svg class="vcb-ic-main" viewBox="0 0 24 24" width="24" height="24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12c0 1.6.38 3.11 1.04 4.45L2 22l5.55-1.04A9.94 9.94 0 0012 22c5.52 0 10-4.48 10-10S17.52 2 12 2z"/></svg>
			<svg class="vcb-ic-close" viewBox="0 0 24 24" width="22" height="22" fill="currentColor"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>
		</button>
		<?php $d = 0.05; foreach ( array_reverse( $items ) as $it ) : ?>
			<a href="<?php echo esc_url( $it['href'] ); ?>" <?php echo $it['newtab'] ? $target : ''; ?> class="vcb-fab-btn vcb-fab-item vcb-ch-<?php echo esc_attr( $it['k'] ); ?>" style="--vcb-ch:<?php echo esc_attr( $it['c'] ); ?>;transition-delay:<?php echo esc_attr( $d ); ?>s">
				<?php echo vig_contact_bar_fab_icon( $it['k'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<span class="vcb-fab-label"><?php echo esc_html( vig_contact_bar_pll( $it['label'] ) ); ?></span>
			</a>
		<?php $d += 0.04; endforeach; ?>
	</div>

	<style>
		#vcb-fab{position:fixed;bottom:30px;right:30px;z-index:98;display:flex;flex-direction:column-reverse;align-items:center;gap:12px;font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,sans-serif}
		.vcb-fab-btn{width:50px;height:50px;border-radius:50%;display:flex;align-items:center;justify-content:center;text-decoration:none;box-shadow:0 4px 15px rgba(15,23,42,.12);transition:all .3s cubic-bezier(.34,1.56,.64,1);position:relative;cursor:pointer;border:1px solid rgba(15,23,42,.05);outline:none;background:#fff}
		.vcb-fab-btn:hover{transform:translateY(-4px);box-shadow:0 8px 25px rgba(15,23,42,.18)}
		.vcb-fab-trigger{background:var(--vcb-brand);color:#fff;border:none;z-index:10}
		.vcb-fab-trigger svg{position:absolute;transition:all .3s}
		.vcb-fab-trigger .vcb-ic-close{opacity:0;transform:rotate(-90deg) scale(.5)}
		#vcb-fab.vcb-open .vcb-fab-trigger{background:#0f172a!important}
		#vcb-fab.vcb-open .vcb-fab-trigger .vcb-ic-main{opacity:0;transform:rotate(90deg) scale(.5)}
		#vcb-fab.vcb-open .vcb-fab-trigger .vcb-ic-close{opacity:1;transform:none}
		.vcb-fab-item{width:46px;height:46px;opacity:0;visibility:hidden;transform:translateY(15px) scale(.8);pointer-events:none;transition:all .3s ease}
		#vcb-fab.vcb-open .vcb-fab-item{opacity:1;visibility:visible;transform:none;pointer-events:auto}
		/* light */
		.vcb-fab-light .vcb-fab-item{background:#fff;border:1px solid #f1f5f9;color:var(--vcb-ch)}
		/* colored */
		.vcb-fab-colored .vcb-fab-item{background:var(--vcb-ch);color:#fff;border:none}
		/* bordered */
		.vcb-fab-bordered .vcb-fab-item{background:#fff;border:2px solid var(--vcb-brand);color:var(--vcb-brand)}
		.vcb-fab-label{position:absolute;right:60px;background:#fff;color:#334155;padding:6px 14px;border-radius:8px;font-size:13px;font-weight:700;white-space:nowrap;box-shadow:0 4px 12px rgba(15,23,42,.12);opacity:0;transform:translateX(10px);transition:.2s;pointer-events:none;border:1px solid #f1f5f9}
		.vcb-fab-btn:hover .vcb-fab-label{opacity:1;transform:none}
		.vcb-fab-trigger::before{content:"";position:absolute;inset:0;border-radius:50%;background:var(--vcb-brand);opacity:.3;z-index:-1;animation:vcbPulse 2s infinite}
		#vcb-fab.vcb-open .vcb-fab-trigger::before{display:none}
		@keyframes vcbPulse{0%{transform:scale(1);opacity:.3}100%{transform:scale(1.7);opacity:0}}
		@media (max-width:767px){#vcb-fab{bottom:20px;right:20px}}
	</style>
	<script>
		(function(){
			var w=document.getElementById('vcb-fab'),t=document.getElementById('vcbFabTrigger');
			if(!w||!t)return;
			t.addEventListener('click',function(e){e.stopPropagation();w.classList.toggle('vcb-open');});
			document.addEventListener('click',function(e){if(!w.contains(e.target))w.classList.remove('vcb-open');});
		})();
	</script>
	<?php
}
