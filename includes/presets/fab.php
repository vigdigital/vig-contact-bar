<?php
/**
 * Preset FAB — nút tròn nổi, bấm bung ra các kênh (nút tròn + nhãn hover). 3 kiểu màu.
 * Nhận danh sách kênh chung + style; không gắn với thương hiệu cụ thể.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function vig_cb_render_fab( array $channels, array $style ): void {
	$pos        = $style['position'];
	$always     = ! empty( $style['always_open'] );
	$label_side = ( 'bl' === $pos ) ? 'left:60px' : 'right:60px';
	// always → thêm class hiện nút thẳng (vcb-fab-static) + vcb-open để tái dùng CSS bung.
	$wrap_class = 'vcb-fab vcb-fab-' . $style['fab_style'] . ( $always ? ' vcb-fab-static vcb-open' : '' );
	?>
	<div id="vcb-fab" class="<?php echo esc_attr( $wrap_class ); ?>"
	     style="--vcb-brand:<?php echo esc_attr( $style['color'] ); ?>;<?php echo esc_attr( vig_cb_position_css( $pos ) ); ?>">
		<?php if ( ! $always ) : ?>
		<button class="vcb-fab-btn vcb-fab-trigger" id="vcbFabTrigger" aria-label="<?php echo esc_attr( VCB_Channels::trigger_label() ); ?>">
			<svg class="vcb-ic-main" viewBox="0 0 24 24" width="24" height="24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12c0 1.6.38 3.11 1.04 4.45L2 22l5.55-1.04A9.94 9.94 0 0012 22c5.52 0 10-4.48 10-10S17.52 2 12 2z"/></svg>
			<svg class="vcb-ic-close" viewBox="0 0 24 24" width="22" height="22" fill="currentColor"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>
		</button>
		<?php endif; ?>
		<?php
		$d = 0.05;
		foreach ( array_reverse( $channels ) as $ch ) :
			list( $href, $attrs ) = vig_cb_item_link( $ch );
			?>
			<a href="<?php echo esc_url( $href ); ?>" <?php echo $attrs; // phpcs:ignore WordPress.Security.EscapeOutput ?>
			   class="vcb-fab-btn vcb-fab-item" aria-label="<?php echo esc_attr( $ch['label'] ); ?>"
			   style="--vcb-ch:<?php echo esc_attr( $ch['color'] ); ?>;transition-delay:<?php echo esc_attr( $d ); ?>s">
				<?php echo $ch['icon']; // phpcs:ignore WordPress.Security.EscapeOutput -- SVG nội bộ ?>
				<span class="vcb-fab-label" style="<?php echo esc_attr( $label_side ); ?>"><?php echo esc_html( $ch['label'] ); ?></span>
			</a>
			<?php
			$d += 0.04;
		endforeach;
		?>
	</div>
	<style>
		#vcb-fab{position:fixed;z-index:98;display:flex;flex-direction:column-reverse;align-items:center;gap:12px;font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,sans-serif}
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
		.vcb-fab-light .vcb-fab-item{background:#fff;border:1px solid #f1f5f9;color:var(--vcb-ch)}
		.vcb-fab-colored .vcb-fab-item{background:var(--vcb-ch);color:#fff;border:none}
		.vcb-fab-bordered .vcb-fab-item{background:#fff;border:2px solid var(--vcb-brand);color:var(--vcb-brand)}
		.vcb-fab-label{position:absolute;background:#fff;color:#334155;padding:6px 14px;border-radius:8px;font-size:13px;font-weight:700;white-space:nowrap;box-shadow:0 4px 12px rgba(15,23,42,.12);opacity:0;transform:translateX(10px);transition:.2s;pointer-events:none;border:1px solid #f1f5f9}
		.vcb-fab-btn:hover .vcb-fab-label{opacity:1;transform:none}
		.vcb-fab-trigger::before{content:"";position:absolute;inset:0;border-radius:50%;background:var(--vcb-brand);opacity:.3;z-index:-1;animation:vcbPulse 2s infinite}
		#vcb-fab.vcb-open .vcb-fab-trigger::before{display:none}
		@keyframes vcbPulse{0%{transform:scale(1);opacity:.3}100%{transform:scale(1.7);opacity:0}}
		/* always-open: nút hiện ngay, không delay staggered */
		.vcb-fab-static .vcb-fab-item{transition-delay:0s!important}
	</style>
	<?php if ( ! $always ) : ?>
	<script>
		(function(){
			var w=document.getElementById('vcb-fab'),t=document.getElementById('vcbFabTrigger');
			if(!w||!t)return;
			t.addEventListener('click',function(e){e.stopPropagation();w.classList.toggle('vcb-open');});
			document.addEventListener('click',function(e){if(!w.contains(e.target))w.classList.remove('vcb-open');});
		})();
	</script>
	<?php endif; ?>
	<?php
}
