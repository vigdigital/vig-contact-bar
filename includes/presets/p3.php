<?php
/**
 * Preset 3 — nút tròn nổi kiểu "Dream Wedding": stack dọc, viền trắng, rung "chuông",
 * toggle điện thoại (thu gọn) ↔ X (mở). Nhãn hiện khi hover, màu theo kênh.
 * Nhận danh sách kênh chung + style; không gắn thương hiệu cụ thể.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function vig_cb_render_p3( array $channels, array $style ): void {
	$pos    = $style['position'];                       // br | bl | bc
	$side   = ( 'bl' === $pos ) ? 'left' : ( ( 'bc' === $pos ) ? 'center' : 'right' );
	$always = ! empty( $style['always_open'] );          // bung sẵn, ẩn toggle
	$ring   = get_option( 'vig_cb_p3_ring', 'soft' );    // strong | soft | off
	$ring   = in_array( $ring, array( 'strong', 'soft', 'off' ), true ) ? $ring : 'soft';

	$wrap_class = 'vcb-p3 vcb-p3-' . $side . ' vcb-p3-ring-' . $ring . ( $always ? ' vcb-p3-static' : '' );
	?>
	<div id="vcb-p3" class="<?php echo esc_attr( $wrap_class ); ?>"
	     style="--vcb-brand:<?php echo esc_attr( $style['color'] ); ?>;<?php echo esc_attr( vig_cb_position_css( $pos ) ); ?>">
		<ul class="vcb-p3-list">
			<?php foreach ( $channels as $ch ) :
				list( $href, $attrs ) = vig_cb_item_link( $ch );
				?>
				<li class="vcb-p3-item" style="--vcb-ch:<?php echo esc_attr( $ch['color'] ); ?>">
					<a href="<?php echo esc_url( $href ); ?>" <?php echo $attrs; // phpcs:ignore WordPress.Security.EscapeOutput ?>
					   class="vcb-p3-url" aria-label="<?php echo esc_attr( $ch['label'] ); ?>" title="<?php echo esc_attr( $ch['label'] ); ?>">
						<span class="vcb-p3-icon"><?php echo $ch['icon']; // phpcs:ignore WordPress.Security.EscapeOutput -- SVG nội bộ ?></span>
						<span class="vcb-p3-label"><?php echo esc_html( $ch['label'] ); ?></span>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
		<?php if ( ! $always ) : ?>
		<button class="vcb-p3-toggle" id="vcbP3Toggle" type="button" aria-label="<?php echo esc_attr( VCB_Channels::trigger_label() ); ?>">
			<svg class="vcb-p3-ic-close" viewBox="0 0 24 24" width="26" height="26" fill="currentColor"><path d="M18.3 5.7 12 12l6.3 6.3-1.4 1.4L10.6 13.4 4.3 19.7 2.9 18.3 9.2 12 2.9 5.7 4.3 4.3l6.3 6.3L16.9 4.3z"/></svg>
			<svg class="vcb-p3-ic-open" viewBox="0 0 24 24" width="26" height="26" fill="currentColor"><path d="M19.56 14.81a10.22 10.22 0 0 1-3.21-.51 1.47 1.47 0 0 0-1.43.3l-2 1.53a11.18 11.18 0 0 1-5-5l1.48-2a1.46 1.46 0 0 0 .36-1.47 10.23 10.23 0 0 1-.51-3.21A1.45 1.45 0 0 0 7.75 3H4.44A1.45 1.45 0 0 0 3 4.44 16.57 16.57 0 0 0 19.56 21 1.45 1.45 0 0 0 21 19.56v-3.3a1.45 1.45 0 0 0-1.44-1.45Z"/></svg>
		</button>
		<?php endif; ?>
	</div>
	<style>
		#vcb-p3{position:fixed;z-index:98;display:flex;flex-direction:column;font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,sans-serif}
		#vcb-p3.vcb-p3-left{align-items:flex-start}
		#vcb-p3.vcb-p3-right{align-items:flex-end}
		#vcb-p3.vcb-p3-center{align-items:center}
		#vcb-p3 .vcb-p3-list{list-style:none;margin:0;padding:0;display:flex;flex-direction:column;gap:14px;transition:opacity .4s ease,transform .4s ease}
		#vcb-p3.vcb-collapsed .vcb-p3-list{opacity:0;transform:translateY(16px);pointer-events:none}
		#vcb-p3 .vcb-p3-url{display:flex;align-items:center;text-decoration:none}
		#vcb-p3.vcb-p3-right .vcb-p3-url{flex-direction:row-reverse}
		#vcb-p3 .vcb-p3-icon{flex:0 0 auto;width:52px;height:52px;border-radius:50%;border:3px solid #fff;color:#fff;
			display:flex;align-items:center;justify-content:center;background:var(--vcb-ch,#333);box-shadow:0 6px 18px rgba(15,23,42,.22)}
		#vcb-p3 .vcb-p3-icon svg{width:28px;height:28px;fill:currentColor}
		#vcb-p3 .vcb-p3-label{margin:0 12px;padding:5px 12px;border-radius:10px;background:#fff;color:#374151;font-size:13px;
			font-weight:700;white-space:nowrap;box-shadow:0 4px 14px rgba(15,23,42,.16);opacity:0;transform:translateY(4px);
			pointer-events:none;transition:.3s}
		#vcb-p3.vcb-p3-right .vcb-p3-label{margin-right:12px;margin-left:0}
		#vcb-p3 .vcb-p3-url:hover .vcb-p3-label{opacity:1;transform:none;background:var(--vcb-ch,#333);color:#fff}
		/* toggle: mở = X nền #737373; thu gọn = điện thoại nền thương hiệu */
		#vcb-p3 .vcb-p3-toggle{position:relative;width:52px;height:52px;margin-top:10px;border:3px solid #fff;border-radius:50%;
			cursor:pointer;overflow:hidden;padding:0;background:#737373;box-shadow:0 6px 18px rgba(15,23,42,.24);transition:background .4s ease}
		#vcb-p3 .vcb-p3-toggle svg{position:absolute;top:50%;left:50%;fill:#fff;transition:transform .45s ease}
		#vcb-p3 .vcb-p3-ic-close{transform:translate(-44%,-50%)}
		#vcb-p3 .vcb-p3-ic-open{transform:translate(-50%,150%)}
		#vcb-p3.vcb-collapsed .vcb-p3-toggle{background:var(--vcb-brand,#dc3545)}
		#vcb-p3.vcb-collapsed .vcb-p3-ic-close{transform:translate(-50%,-250%)}
		#vcb-p3.vcb-collapsed .vcb-p3-ic-open{transform:translate(-50%,-50%)}
		/* rung "chuông" */
		@keyframes vcbP3ring{0%{transform:rotate(0)}10%,20%{transform:scale(.92) rotate(-8deg)}
			30%,50%,70%,90%{transform:scale(1.06) rotate(8deg)}40%,60%,80%{transform:scale(1.06) rotate(-8deg)}100%{transform:rotate(0)}}
		#vcb-p3.vcb-p3-ring-strong .vcb-p3-icon{animation:vcbP3ring 1s linear infinite}
		#vcb-p3.vcb-p3-ring-soft .vcb-p3-icon{animation:vcbP3ring 2.4s linear infinite}
		#vcb-p3.vcb-p3-ring-strong.vcb-collapsed .vcb-p3-toggle{animation:vcbP3ring 1s linear infinite}
		#vcb-p3.vcb-p3-ring-soft.vcb-collapsed .vcb-p3-toggle{animation:vcbP3ring 2.4s linear infinite}
		@media (prefers-reduced-motion:reduce){#vcb-p3 .vcb-p3-icon,#vcb-p3 .vcb-p3-toggle{animation:none!important}}
	</style>
	<?php if ( ! $always ) : ?>
	<script>
		(function(){
			var w=document.getElementById('vcb-p3'),t=document.getElementById('vcbP3Toggle');
			if(!w||!t)return;
			t.addEventListener('click',function(e){e.stopPropagation();w.classList.toggle('vcb-collapsed');});
		})();
	</script>
	<?php endif; ?>
	<?php
}
