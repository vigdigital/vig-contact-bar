<?php
/**
 * i18n admin — dịch chuỗi giao diện cài đặt theo NGÔN NGỮ ADMIN (get_user_locale via
 * VCB_Channels::is_en()). Nguồn là tiếng Việt; site/admin tiếng Anh → tra bảng EN.
 * Không dùng gettext/.mo để khỏi build — đồng bộ với pattern is_en() của plugin.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Dịch 1 chuỗi admin sang tiếng Anh nếu admin dùng tiếng Anh; ngược lại giữ nguyên tiếng Việt. */
function vcb_t( string $vi ): string {
	if ( ! VCB_Channels::is_en() ) {
		return $vi;
	}
	$map = vcb_i18n_map();
	return $map[ $vi ] ?? $vi;
}

/** Bảng dịch VI → EN cho toàn bộ giao diện admin. */
function vcb_i18n_map(): array {
	return array(
		// Chung
		'Đã lưu.'        => 'Saved.',
		'Lưu thay đổi'   => 'Save changes',

		// Tab Preset
		'Kiểu hiển thị'  => 'Display style',
		'Bar = thanh pill dọc có nhãn. FAB = nút tròn nổi bung ra các kênh.' => 'Bar = vertical pill list with labels. FAB = floating round button that expands into channels.',
		'Vị trí'         => 'Position',
		'Dưới phải'      => 'Bottom right',
		'Dưới trái'      => 'Bottom left',
		'Dưới giữa'      => 'Bottom center',
		'Kiểu màu (FAB)' => 'Color style (FAB)',
		'Nền sáng, icon màu'  => 'Light background, colored icon',
		'Nền màu, icon trắng' => 'Colored background, white icon',
		'Viền màu'            => 'Colored border',
		'Chỉ áp dụng cho preset FAB.' => 'Applies to the FAB preset only.',
		'Hiện sẵn tất cả nút' => 'Show all buttons',
		'Bung sẵn, <strong>ẩn nút "Liên hệ"</strong> (khách thấy ngay các kênh, không cần bấm)' => 'Expanded by default, <strong>hide the "Contact" button</strong> (visitors see every channel with no click)',
		'Áp dụng cho cả Bar lẫn FAB. Tắt = giữ nút trigger như cũ.' => 'Applies to both Bar and FAB. Off = keep the trigger button as before.',

		// Tab Content
		'Bật kênh cần dùng, điền giá trị. Thứ tự nhỏ hiện trước. Kênh nào để trống sẽ tự ẩn.' => 'Enable the channels you need and fill in the value. Lower order shows first. Empty channels are hidden automatically.',
		'Bật'     => 'On',
		'Kênh'    => 'Channel',
		'Giá trị' => 'Value',
		'Nhãn'    => 'Label',
		'Thứ tự'  => 'Order',
		'Form modal (shortcode):' => 'Modal form (shortcode):',
		'Có shortcode → mở form trong popup thay vì mở link.' => 'With a shortcode the form opens in a popup instead of a link.',
		'Ẩn bong bóng chat mặc định của Tawk.to' => "Hide Tawk.to's default chat bubble",
		'Tự mở khung chat' => 'Auto-open chat',
		'khi khách vào (1 lần mỗi phiên)' => 'when a visitor arrives (once per session)',
		'Chuyển trang trong cùng phiên không bung lại; khách tự đóng thì thôi.' => 'It will not reopen while navigating in the same session, and stays closed once the visitor closes it.',

		// Tab Style
		'Màu thương hiệu'      => 'Brand color',
		'Màu nút chính / hiệu ứng.' => 'Main button / effect color.',
		'Hiện chữ (nhãn) trên nút'  => 'Show text (labels) on buttons',
		'Trên'                 => 'On',
		'Áp dụng cho preset Bar (FAB luôn hiện nhãn khi hover).' => 'Applies to the Bar preset (FAB always shows labels on hover).',
		'Mở link ở tab mới'    => 'Open links in a new tab',
		'Bật (áp dụng WhatsApp/Zalo/Messenger/Liên hệ)' => 'Enabled (applies to WhatsApp/Zalo/Messenger/Contact)',
	);
}
