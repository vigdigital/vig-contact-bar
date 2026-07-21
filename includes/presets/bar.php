<?php
/**
 * Preset Bar — thanh pill dọc (icon + nhãn), có nút thu gọn. Nhãn ẩn được theo thiết bị.
 * always_open = bung sẵn, KHÔNG render nút trigger (không cần bấm).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function vig_cb_render_bar( array $channels, array $style ): void {
	$pos     = $style['position'];
	$always  = ! empty( $style['always_open'] );
	$classes = 'vcb-bar vcb-pos-' . $pos . ' vcb-open';
	if ( $always ) {
		$classes .= ' vcb-always';   // luôn bung, ẩn nút trigger
	}
	if ( ! $style['text_desktop'] ) {
		$classes .= ' vcb-hide-text-desktop';
	}
	if ( ! $style['text_mobile'] ) {
		$classes .= ' vcb-hide-text-mobile';
	}
	$trigger = VCB_Channels::trigger_label();
	?>
	<div id="vcb-bar" class="<?php echo esc_attr( $classes ); ?>" style="--vcb-brand:<?php echo esc_attr( $style['color'] ); ?>;<?php echo esc_attr( vig_cb_position_css( $pos ) ); ?>">
		<div class="vcb-bar-list">
			<?php foreach ( $channels as $ch ) : list( $href, $attrs ) = vig_cb_item_link( $ch ); ?>
				<a href="<?php echo esc_url( $href ); ?>" <?php echo $attrs; // phpcs:ignore WordPress.Security.EscapeOutput ?>
				   class="vcb-bar-item" style="--vcb-ch:<?php echo esc_attr( $ch['color'] ); ?>">
					<span class="vcb-bar-ic"><?php echo $ch['icon']; // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
					<span class="vcb-bar-tx"><?php echo esc_html( $ch['label'] ); ?></span>
				</a>
			<?php endforeach; ?>
		</div>
		<?php if ( ! $always ) : ?>
			<button class="vcb-bar-toggle" id="vcbBarToggle" aria-label="<?php echo esc_attr( $trigger ); ?>">
				<svg class="vcb-bar-toggle-ic" viewBox="0 0 24 24" width="22" height="22" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12c0 1.6.38 3.11 1.04 4.45L2 22l5.55-1.04A9.94 9.94 0 0012 22c5.52 0 10-4.48 10-10S17.52 2 12 2z"/></svg>
				<span class="vcb-bar-toggle-tx"><?php echo esc_html( $trigger ); ?></span>
			</button>
		<?php endif; ?>
	</div>
	<style>
		#vcb-bar{position:fixed;z-index:98;display:flex;flex-direction:column;gap:10px;font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,sans-serif}
		#vcb-bar.vcb-pos-br{align-items:flex-end}
		#vcb-bar.vcb-pos-bl{align-items:flex-start}
		#vcb-bar.vcb-pos-bc{align-items:center}
		.vcb-bar-list{display:flex;flex-direction:column;gap:8px;transition:opacity .25s ease,transform .25s ease}
		#vcb-bar:not(.vcb-open) .vcb-bar-list{opacity:0;transform:translateY(12px);pointer-events:none}
		.vcb-bar-item{display:inline-flex;align-items:center;gap:10px;background:#fff;color:#1e293b;text-decoration:none;padding:8px 14px 8px 10px;border-radius:999px;box-shadow:0 4px 14px rgba(15,23,42,.12);border:1px solid #eef2f6;font-size:14px;font-weight:600;transition:transform .2s ease,box-shadow .2s ease}
		.vcb-bar-item:hover{transform:translateY(-2px);box-shadow:0 8px 22px rgba(15,23,42,.18)}
		.vcb-bar-ic{width:34px;height:34px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;flex-shrink:0;background:var(--vcb-ch);color:#fff}
		.vcb-bar-toggle{display:inline-flex;align-items:center;gap:8px;background:var(--vcb-brand);color:#fff;border:none;cursor:pointer;padding:10px 16px;border-radius:999px;box-shadow:0 6px 18px rgba(15,23,42,.2);font-size:14px;font-weight:700}
		.vcb-bar-toggle-ic{transition:transform .3s ease}
		#vcb-bar:not(.vcb-open) .vcb-bar-toggle-ic{transform:rotate(0)}
		@media (min-width:768px){#vcb-bar.vcb-hide-text-desktop .vcb-bar-tx{display:none}#vcb-bar.vcb-hide-text-desktop .vcb-bar-item{padding:8px}}
		@media (max-width:767px){#vcb-bar.vcb-hide-text-mobile .vcb-bar-tx{display:none}#vcb-bar.vcb-hide-text-mobile .vcb-bar-item{padding:8px}}
	</style>
	<?php if ( ! $always ) : ?>
	<script>
		(function(){
			var b=document.getElementById('vcb-bar'),t=document.getElementById('vcbBarToggle');
			if(!b||!t)return;
			if(localStorage.getItem('vcb_bar')==='closed') b.classList.remove('vcb-open');
			t.addEventListener('click',function(){
				b.classList.toggle('vcb-open');
				localStorage.setItem('vcb_bar', b.classList.contains('vcb-open')?'open':'closed');
			});
		})();
	</script>
	<?php endif; ?>
	<?php
}
