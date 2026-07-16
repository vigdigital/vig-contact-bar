<?php

/**
 * Plugin Name: VIG Contact Bar
 * Plugin URI: https://vigdigital.com
 * Description: Thanh liên hệ nổi (Contact Bar): nhiều preset (Hotline, Leon Dio FAB) — Chat Tawk.to, WhatsApp, Zalo, Messenger, Phone, Contact Form. Responsive Desktop & Mobile.
 * Version: 1.1.1
 * Author: VIG Digital
 * Author URI: https://vigdigital.com
 * License: GPL-2.0-or-later
 * Text Domain: vig-contact-bar
 * Update URI: https://github.com/vigdigital/vig-contact-bar
 */

if (!defined('ABSPATH')) {
    exit;
}

// Định nghĩa các icon mặc định trích xuất trực tiếp từ hình ảnh của quý khách (đã mã hóa dạng Base64 để tối ưu tốc độ tải)
define('VIG_CONTACT_BAR_DEFAULT_PARROT', 'data:image/png;base64,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');

define('VIG_CONTACT_BAR_DEFAULT_WHATSAPP', "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' enable-background='new 0 0 152 152' height='512' viewBox='0 0 152 152' width='512'%3E%3Cpath id='Icon' d='m76 11c-35.9 0-65 29.1-65 65 0 13.6 4.4 27 12.3 38l-8.1 24.2 25-8c29.9 19.8 70.3 11.5 90-18.5s11.5-70.3-18.5-90c-10.5-6.9-23-10.7-35.7-10.7zm34.6 92-7 6.8c-7.3 7.3-26.6-.6-43.7-17.9s-24.7-36.4-17.8-43.5l7-7c2.8-2.6 7-2.6 9.9 0l10.2 10.2c2.8 2.6 2.8 6.8.2 9.6-.8.8-1.6 1.3-2.8 1.6-3.4 1.1-5.4 4.5-4.4 8 1.8 7.6 11.7 17.1 19 19 3.4.6 6.8-1.1 8-4.4 1.1-3.6 5-5.5 8.6-4.4 1.1.3 1.9 1 2.8 1.8l10.2 10.2c2.2 2.8 2.2 7.2-.2 10z' fill='%234daf50'/%3E%3C/svg%3E");

define('VIG_CONTACT_BAR_DEFAULT_CONTACT', "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' height='24' viewBox='0 0 24 24' width='24'%3E%3Cpath clip-rule='evenodd' d='m3.25 4c0-.41421.33579-.75.75-.75h16c.4142 0 .75.33579.75.75v16c0 .4142-.3358.75-.75.75h-6c-.4142 0-.75-.3358-.75-.75s.3358-.75.75-.75h5.25v-14.5h-14.5v5.25c0 .4142-.33579.75-.75.75s-.75-.3358-.75-.75zm9.2197 2.46967c.2929-.29289.7677-.29289 1.0606 0l4 4.00003c.2929.2929.2929.7677 0 1.0606l-8.99997 9c-.14065.1407-.33142.2197-.53033.2197h-4c-.41421 0-.75-.3358-.75-.75v-4c0-.1989.07902-.3897.21967-.5303zm-1.409 3.53033 2.9393 2.9393 1.9393-1.9393-2.9393-2.93934zm1.8786 4-2.9393-2.9393-5.25 5.25v2.9393h2.93934z' fill='%23fff' fill-rule='evenodd'/%3E%3C/svg%3E");

define('VIG_CONTACT_BAR_DEFAULT_TOGGLE_DOWN', "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 640 640'%3E%3Cpath d='M297.4 470.6C309.9 483.1 330.2 483.1 342.7 470.6L534.7 278.6C547.2 266.1 547.2 245.8 534.7 233.3C522.2 220.8 501.9 220.8 489.4 233.3L320 402.7L150.6 233.4C138.1 220.9 117.8 220.9 105.3 233.4C92.8 245.9 92.8 266.2 105.3 278.7L297.3 470.7z' fill='%23fff'/%3E%3C/svg%3E");

define('VIG_CONTACT_BAR_DEFAULT_TOGGLE_RIGHT', "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 640 640'%3E%3Cpath d='M471.1 297.4C483.6 309.9 483.6 330.2 471.1 342.7L279.1 534.7C266.6 547.2 246.3 547.2 233.8 534.7C221.3 522.2 221.3 501.9 233.8 489.4L403.2 320L233.9 150.6C221.4 138.1 221.4 117.8 233.9 105.3C246.4 92.8 266.7 92.8 279.2 105.3L471.2 297.3z' fill='%23fff'/%3E%3C/svg%3E");

// Menu cha "VIG Toolkit" dùng chung cho các plugin VIG
require_once __DIR__ . '/includes/vig-admin-menu.php';

// Tự-update qua GitHub Releases (inert cho tới khi vendor PUC)
require_once __DIR__ . '/includes/vig-update-checker.php';
vig_setup_updates( __FILE__, 'vig-contact-bar' );

// Preset "Leon Dio (FAB)"
require_once __DIR__ . '/includes/preset-fab.php';

// Đăng ký chuỗi tiêu đề cho Polylang (đa ngôn ngữ). Guard: không có Polylang thì bỏ qua.
add_action('init', 'vig_contact_bar_register_strings');
function vig_contact_bar_register_strings() {
    if ( ! function_exists('pll_register_string') ) {
        return;
    }
    $g = 'VIG Contact Bar';
    pll_register_string('vig_cb_tawkto_title',   get_option('vig_contact_bar_tawkto_title',   'Chat withTawk.to'), $g);
    pll_register_string('vig_cb_whatsapp_title', get_option('vig_contact_bar_whatsapp_title', 'WhatsApp'),         $g);
    pll_register_string('vig_cb_contact_title',  get_option('vig_contact_bar_contact_title',  'Contact form'),     $g);
    pll_register_string('vig_cb_phone_label',     get_option('vig_contact_bar_phone_label',     'Gọi ngay'),   $g);
    pll_register_string('vig_cb_zalo_label',      get_option('vig_contact_bar_zalo_label',      'Zalo'),       $g);
    pll_register_string('vig_cb_messenger_label', get_option('vig_contact_bar_messenger_label', 'Messenger'),  $g);
}
// Trả bản dịch chuỗi theo ngôn ngữ hiện tại nếu có Polylang, không thì trả nguyên.
function vig_contact_bar_pll( $s ) {
    return function_exists('pll__') ? pll__( $s ) : $s;
}

// Đăng ký trang cài đặt vào submenu "VIG Toolkit"
add_action('admin_menu', 'vig_contact_bar_add_admin_menu');
function vig_contact_bar_add_admin_menu()
{
    vig_toolkit_register_parent();
    add_submenu_page(
        'vig-toolkit',
        'VIG Contact Bar',   // page title
        'Contact Bar',       // nhãn submenu
        'manage_options',
        'vig-contact-bar',
        'vig_contact_bar_settings_page'
    );
}

// HTML trang quản trị của Plugin
function vig_contact_bar_settings_page()
{
    if (!current_user_can('manage_options')) {
        return;
    }

    // Xử lý lưu dữ liệu khi nhấn Submit
    if (isset($_POST['vig_contact_bar_save'])) {
        check_admin_referer('vig_contact_bar_save_options');

        update_option('vig_contact_bar_tawkto_code', wp_unslash($_POST['vig_contact_bar_tawkto_code']));
        update_option('vig_contact_bar_tawkto_hide_default', isset($_POST['vig_contact_bar_tawkto_hide_default']) ? '1' : '0');
        update_option('vig_contact_bar_whatsapp_number', sanitize_text_field($_POST['vig_contact_bar_whatsapp_number']));
        update_option('vig_contact_bar_contact_shortcode', wp_unslash($_POST['vig_contact_bar_contact_shortcode']));
        update_option('vig_contact_bar_new_tab', isset($_POST['vig_contact_bar_new_tab']) ? '1' : '0');

        update_option('vig_contact_bar_tawkto_title', sanitize_text_field($_POST['vig_contact_bar_tawkto_title']));
        update_option('vig_contact_bar_tawkto_icon', esc_url_raw($_POST['vig_contact_bar_tawkto_icon']));

        update_option('vig_contact_bar_whatsapp_title', sanitize_text_field($_POST['vig_contact_bar_whatsapp_title']));
        update_option('vig_contact_bar_whatsapp_icon', esc_url_raw($_POST['vig_contact_bar_whatsapp_icon']));

        update_option('vig_contact_bar_contact_title', sanitize_text_field($_POST['vig_contact_bar_contact_title']));
        update_option('vig_contact_bar_contact_icon', esc_url_raw($_POST['vig_contact_bar_contact_icon']));
        update_option('vig_contact_bar_contact_use_modal', isset($_POST['vig_contact_bar_contact_use_modal']) ? '1' : '0');
        update_option('vig_contact_bar_contact_url', esc_url_raw($_POST['vig_contact_bar_contact_url']));

        update_option('vig_contact_bar_show_text_desktop', isset($_POST['vig_contact_bar_show_text_desktop']) ? '1' : '0');
        update_option('vig_contact_bar_show_text_mobile', isset($_POST['vig_contact_bar_show_text_mobile']) ? '1' : '0');

        // Preset + kênh cho FAB
        $preset_in = sanitize_text_field($_POST['vig_contact_bar_preset'] ?? 'hotline');
        update_option('vig_contact_bar_preset', in_array($preset_in, array('hotline', 'fab-leondio'), true) ? $preset_in : 'hotline');
        update_option('vig_contact_bar_fab_style', sanitize_text_field($_POST['vig_contact_bar_fab_style'] ?? 'light'));
        update_option('vig_contact_bar_fab_color', sanitize_hex_color($_POST['vig_contact_bar_fab_color'] ?? '') ?: '#c5a059');
        update_option('vig_contact_bar_phone', sanitize_text_field($_POST['vig_contact_bar_phone'] ?? ''));
        update_option('vig_contact_bar_phone_label', sanitize_text_field($_POST['vig_contact_bar_phone_label'] ?? ''));
        update_option('vig_contact_bar_zalo', sanitize_text_field($_POST['vig_contact_bar_zalo'] ?? ''));
        update_option('vig_contact_bar_zalo_label', sanitize_text_field($_POST['vig_contact_bar_zalo_label'] ?? ''));
        update_option('vig_contact_bar_messenger', sanitize_text_field($_POST['vig_contact_bar_messenger'] ?? ''));
        update_option('vig_contact_bar_messenger_label', sanitize_text_field($_POST['vig_contact_bar_messenger_label'] ?? ''));

        echo '<div class="updated"><p>Settings saved successfully!</p></div>';
    }

    // Lấy các giá trị đã cấu hình
    $tawkto_code = get_option('vig_contact_bar_tawkto_code', '');
    $tawkto_hide = get_option('vig_contact_bar_tawkto_hide_default', '1');
    $whatsapp_num = get_option('vig_contact_bar_whatsapp_number', '');
    $contact_shortcode = get_option('vig_contact_bar_contact_shortcode', '');
    $new_tab = get_option('vig_contact_bar_new_tab', '1');

    $tawkto_title = get_option('vig_contact_bar_tawkto_title', 'Chat withTawk.to');
    $tawkto_icon = get_option('vig_contact_bar_tawkto_icon', '');

    $whatsapp_title = get_option('vig_contact_bar_whatsapp_title', 'WhatsApp');
    $whatsapp_icon = get_option('vig_contact_bar_whatsapp_icon', '');

    $contact_title = get_option('vig_contact_bar_contact_title', 'Contact form');
    $contact_icon = get_option('vig_contact_bar_contact_icon', '');
    $contact_use_modal = get_option('vig_contact_bar_contact_use_modal', '1');
    $contact_url = get_option('vig_contact_bar_contact_url', '');

    $show_text_desktop = get_option('vig_contact_bar_show_text_desktop', '1');
    $show_text_mobile  = get_option('vig_contact_bar_show_text_mobile', '1');

    $preset          = get_option('vig_contact_bar_preset', 'hotline');
    $fab_style       = get_option('vig_contact_bar_fab_style', 'light');
    $fab_color       = get_option('vig_contact_bar_fab_color', '#c5a059');
    $phone           = get_option('vig_contact_bar_phone', '');
    $phone_label     = get_option('vig_contact_bar_phone_label', 'Gọi ngay');
    $zalo            = get_option('vig_contact_bar_zalo', '');
    $zalo_label      = get_option('vig_contact_bar_zalo_label', 'Zalo');
    $messenger       = get_option('vig_contact_bar_messenger', '');
    $messenger_label = get_option('vig_contact_bar_messenger_label', 'Messenger');

?>
    <div class="wrap">
        <h1>VIG Contact Bar Settings</h1>
        <form method="post" action="">
            <?php wp_nonce_field('vig_contact_bar_save_options'); ?>
            <table class="form-table">
                <!-- Preset selector -->
                <tr valign="top">
                    <th scope="row">Giao diện (Preset)</th>
                    <td>
                        <select name="vig_contact_bar_preset">
                            <option value="hotline" <?php selected($preset, 'hotline'); ?>>Hotline (thanh thu gọn — mặc định)</option>
                            <option value="fab-leondio" <?php selected($preset, 'fab-leondio'); ?>>Leon Dio (FAB — nút tròn bung kênh)</option>
                        </select>
                        <p class="description">Chọn kiểu hiển thị của thanh liên hệ ở front-end.</p>
                    </td>
                </tr>

                <!-- FAB (Leon Dio) settings -->
                <tr><td colspan="2"><hr /><h2>Preset "Leon Dio (FAB)" — kênh &amp; màu</h2>
                    <p class="description">Áp dụng khi chọn preset "Leon Dio (FAB)". WhatsApp &amp; Contact dùng chung ô cấu hình bên dưới.</p></td></tr>
                <tr valign="top">
                    <th scope="row">Kiểu màu</th>
                    <td>
                        <label style="margin-right:16px"><input type="radio" name="vig_contact_bar_fab_style" value="light" <?php checked($fab_style, 'light'); ?>> Nền sáng, icon màu</label>
                        <label style="margin-right:16px"><input type="radio" name="vig_contact_bar_fab_style" value="colored" <?php checked($fab_style, 'colored'); ?>> Nền màu, icon trắng</label>
                        <label><input type="radio" name="vig_contact_bar_fab_style" value="bordered" <?php checked($fab_style, 'bordered'); ?>> Viền màu thương hiệu</label>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Màu thương hiệu</th>
                    <td><input type="color" name="vig_contact_bar_fab_color" value="<?php echo esc_attr($fab_color); ?>"> <span class="description">Nút chính + hiệu ứng. Mặc định gold Leon Dio.</span></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Điện thoại (gọi)</th>
                    <td>
                        <input type="text" name="vig_contact_bar_phone" value="<?php echo esc_attr($phone); ?>" class="regular-text" placeholder="0901234567">
                        <p class="description">Nhãn: <input type="text" name="vig_contact_bar_phone_label" value="<?php echo esc_attr($phone_label); ?>"></p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Zalo</th>
                    <td>
                        <input type="text" name="vig_contact_bar_zalo" value="<?php echo esc_attr($zalo); ?>" class="regular-text" placeholder="Số điện thoại hoặc link zalo.me">
                        <p class="description">Nhãn: <input type="text" name="vig_contact_bar_zalo_label" value="<?php echo esc_attr($zalo_label); ?>"></p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Messenger</th>
                    <td>
                        <input type="text" name="vig_contact_bar_messenger" value="<?php echo esc_attr($messenger); ?>" class="regular-text" placeholder="username hoặc link m.me">
                        <p class="description">Nhãn: <input type="text" name="vig_contact_bar_messenger_label" value="<?php echo esc_attr($messenger_label); ?>"></p>
                    </td>
                </tr>
                <tr><td colspan="2"><hr /><h2>Cấu hình chung (Hotline &amp; các kênh dùng chung)</h2></td></tr>

                <!-- Tawk.to settings -->
                <tr valign="top">
                    <th scope="row">Tawk.to Embed Code</th>
                    <td>
                        <textarea name="vig_contact_bar_tawkto_code" rows="6" cols="60" style="font-family:Consolas,Monaco,monospace;"><?php echo esc_textarea($tawkto_code); ?></textarea>
                        <p class="description">Paste the Tawk.to embed script code here.</p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Hide Tawk.to default widget</th>
                    <td>
                        <input type="checkbox" name="vig_contact_bar_tawkto_hide_default" value="1" <?php checked($tawkto_hide, '1'); ?> />
                        <span class="description">Automatically hide Tawk.to's default chat bubble so only the plugin's hotline bar is shown.</span>
                    </td>
                </tr>

                <!-- WhatsApp settings -->
                <tr valign="top">
                    <th scope="row">WhatsApp phone number</th>
                    <td>
                        <input type="text" name="vig_contact_bar_whatsapp_number" value="<?php echo esc_attr($whatsapp_num); ?>" class="regular-text" />
                        <p class="description">Enter the phone number in international format (e.g. 84901234567, without the + sign).</p>
                    </td>
                </tr>

                <!-- Contact Form settings -->
                <tr valign="top">
                    <th scope="row">Contact Form Shortcode</th>
                    <td>
                        <textarea name="vig_contact_bar_contact_shortcode" rows="3" cols="60" style="font-family:Consolas,Monaco,monospace;" class="regular-text"><?php echo esc_textarea($contact_shortcode); ?></textarea>
                        <p class="description">Example: [contact-form-7 id="123" title="Contact form"] or the shortcode of any form you use.</p>
                    </td>
                </tr>

                <!-- Tab option -->
                <tr valign="top">
                    <th scope="row">Open link in new tab</th>
                    <td>
                        <input type="checkbox" name="vig_contact_bar_new_tab" value="1" <?php checked($new_tab, '1'); ?> />
                        <span class="description">Applies to WhatsApp (opens the app or WhatsApp Web in a new tab).</span>
                    </td>
                </tr>

                <!-- Text (label) visibility -->
                <tr valign="top">
                    <th scope="row">Show text (label) on buttons</th>
                    <td>
                        <label style="display:block;margin-bottom:6px;">
                            <input type="checkbox" name="vig_contact_bar_show_text_desktop" value="1" <?php checked($show_text_desktop, '1'); ?> />
                            Show text on <strong>Desktop</strong>
                        </label>
                        <label style="display:block;">
                            <input type="checkbox" name="vig_contact_bar_show_text_mobile" value="1" <?php checked($show_text_mobile, '1'); ?> />
                            Show text on <strong>Mobile</strong>
                        </label>
                        <p class="description">Uncheck to show only the icon (hide text) on the corresponding device.</p>
                    </td>
                </tr>

                <!-- Customizations -->
                <tr>
                    <td colspan="2">
                        <hr />
                        <h2>Customize Hotline Buttons (leave Icon empty to use the default image)</h2>
                    </td>
                </tr>

                <!-- Tawk.to Custom -->
                <tr valign="top">
                    <th scope="row">Tawk.to Chat Button</th>
                    <td>
                        <label>Title:</label><br />
                        <input type="text" name="vig_contact_bar_tawkto_title" value="<?php echo esc_attr($tawkto_title); ?>" class="regular-text" /><br /><br />
                        <label>Custom Icon URL:</label><br />
                        <input type="text" name="vig_contact_bar_tawkto_icon" value="<?php echo esc_url($tawkto_icon); ?>" class="regular-text" placeholder="https://..." />
                    </td>
                </tr>

                <!-- WhatsApp Custom -->
                <tr valign="top">
                    <th scope="row">WhatsApp Button</th>
                    <td>
                        <label>Title:</label><br />
                        <input type="text" name="vig_contact_bar_whatsapp_title" value="<?php echo esc_attr($whatsapp_title); ?>" class="regular-text" /><br /><br />
                        <label>Custom Icon URL:</label><br />
                        <input type="text" name="vig_contact_bar_whatsapp_icon" value="<?php echo esc_url($whatsapp_icon); ?>" class="regular-text" placeholder="https://..." />
                    </td>
                </tr>

                <!-- Contact Custom -->
                <tr valign="top">
                    <th scope="row">Contact Form Button</th>
                    <td>
                        <label>Title:</label><br />
                        <input type="text" name="vig_contact_bar_contact_title" value="<?php echo esc_attr($contact_title); ?>" class="regular-text" /><br /><br />
                        <label>Custom Icon URL:</label><br />
                        <input type="text" name="vig_contact_bar_contact_icon" value="<?php echo esc_url($contact_icon); ?>" class="regular-text" placeholder="https://..." /><br /><br />
                        <label>
                            <input type="checkbox" name="vig_contact_bar_contact_use_modal" value="1" <?php checked($contact_use_modal, '1'); ?> />
                            Show Contact Form as a Modal Popup
                        </label>
                        <p class="description">Enable to open the form in a popup window. Disable to behave as a normal link.</p>
                        <label>Link URL (when Modal is off):</label><br />
                        <input type="text" name="vig_contact_bar_contact_url" value="<?php echo esc_url($contact_url); ?>" class="regular-text" placeholder="https://..." />
                        <p class="description">Only applies when Modal is disabled above.</p>
                    </td>
                </tr>
            </table>
            <p class="submit">
                <input type="submit" name="vig_contact_bar_save" class="button-primary" value="Save Settings" />
            </p>
        </form>
    </div>
    <?php
}

// Nhúng mã script của Tawk.to và ẩn launcher mặc định của họ
add_action('wp_footer', 'vig_contact_bar_inject_tawkto');
function vig_contact_bar_inject_tawkto()
{
    $tawkto_code = get_option('vig_contact_bar_tawkto_code', '');
    $tawkto_hide = get_option('vig_contact_bar_tawkto_hide_default', '1');

    if (empty($tawkto_code)) {
        return;
    }
    ?>
    <script type="text/javascript">
        /* VIG: khai báo Tawk_API + callback TRƯỚC khi nạp embed.
           Set sau khi Tawk đã load thì onLoad/onChatMaximized không chạy. */
        var Tawk_API = Tawk_API || {};

        /* Dời widget + khung chat sang trái/lên chút để không đè lên contact bar (desktop).
           Dùng API vị trí của Tawk. Chỉnh xOffset (px tính từ mép phải) nếu cần. */
        Tawk_API.customStyle = {
            visibility: {
                desktop: { position: 'br', xOffset: 90, yOffset: 20 },
                mobile:  { position: 'br', xOffset: 0,  yOffset: 0 }
            }
        };

        /* Badge số tin chưa đọc trên nút hotline */
        Tawk_API.onUnreadCountChanged = function (count) {
            var b = document.getElementById('vig-tawkto-badge');
            if (!b) return;
            if (count > 0) { b.textContent = count > 9 ? '9+' : String(count); b.style.display = 'flex'; }
            else { b.style.display = 'none'; }
        };
    <?php if ($tawkto_hide === '1') : ?>
        (function () {
            /* Ẩn iframe mặc định của Tawk: ID random theo timestamp -> KHÔNG target được bằng
               ID/class. Chỉ dò theo size cố định trong inline style.
               Ẩn bubble (max-width:64px) + branding "Powered by tawk.to" (max-height:45px).
               ⚠️ Ẩn branding trên gói FREE vi phạm ToS tawk — chỉ nên ẩn khi dùng gói trả phí
               (hoặc bật "Remove Branding" trong dashboard tawk). */
            function vigHideTawkBubble() {
                var frames = document.querySelectorAll('iframe');
                for (var i = 0; i < frames.length; i++) {
                    var s = frames[i].getAttribute('style') || '';
                    if (s.indexOf('max-width:64px') > -1 || s.indexOf('max-width: 64px') > -1
                        || s.indexOf('max-height:45px') > -1 || s.indexOf('max-height: 45px') > -1) {
                        frames[i].style.setProperty('display', 'none', 'important');
                    }
                }
            }
            window.vigHideTawkBubble = vigHideTawkBubble;

            Tawk_API.onLoad = function () {
                if (typeof Tawk_API.hideWidget === 'function') Tawk_API.hideWidget();
                vigHideTawkBubble();
            };
            /* Khi mở chat, Tawk re-show bubble -> ẩn lại (style apply async nên delay 300ms) */
            Tawk_API.onChatMaximized = function () { setTimeout(vigHideTawkBubble, 300); };

            document.addEventListener('DOMContentLoaded', function () {
                vigHideTawkBubble();
                var obs = new MutationObserver(vigHideTawkBubble);
                obs.observe(document.body, { childList: true, subtree: true, attributes: true, attributeFilter: ['style'] });
            });
        })();
    <?php endif; ?>
    </script>
    <style type="text/css">
        /* Safety net (fallback) — không đụng branding bar để giữ đúng ToS gói free */
        .tawk-min-container { display: none !important; }
    </style>
    <?php
    // Nạp embed SAU khi đã set callback
    echo $tawkto_code;
}

// Hiển thị Hotline ở chân trang (Footer)
add_action('wp_footer', 'vig_contact_bar_render_markup');
function vig_contact_bar_render_markup()
{
    // Dispatch theo preset.
    if (get_option('vig_contact_bar_preset', 'hotline') === 'fab-leondio') {
        vig_contact_bar_render_fab();
        return;
    }

    $whatsapp_num = get_option('vig_contact_bar_whatsapp_number', '');
    $contact_shortcode = get_option('vig_contact_bar_contact_shortcode', '');
    $new_tab_enabled = get_option('vig_contact_bar_new_tab', '1');
    $target_attr = ($new_tab_enabled === '1') ? 'target="_blank"' : 'target="_self"';

    $tawkto_title = get_option('vig_contact_bar_tawkto_title', 'Chat withTawk.to');
    $tawkto_icon = get_option('vig_contact_bar_tawkto_icon', '');
    // Icon do user nhập -> esc_url (chống thoát khỏi url() trong CSS); icon mặc định là data: URI tin cậy
    $tawkto_icon_url = !empty($tawkto_icon) ? esc_url($tawkto_icon) : VIG_CONTACT_BAR_DEFAULT_PARROT;

    $whatsapp_title = get_option('vig_contact_bar_whatsapp_title', 'WhatsApp');
    $whatsapp_icon = get_option('vig_contact_bar_whatsapp_icon', '');
    $whatsapp_icon_url = !empty($whatsapp_icon) ? esc_url($whatsapp_icon) : VIG_CONTACT_BAR_DEFAULT_WHATSAPP;

    $contact_title = get_option('vig_contact_bar_contact_title', 'Contact form');
    $contact_icon = get_option('vig_contact_bar_contact_icon', '');
    $contact_icon_url = !empty($contact_icon) ? esc_url($contact_icon) : VIG_CONTACT_BAR_DEFAULT_CONTACT;
    $contact_use_modal = get_option('vig_contact_bar_contact_use_modal', '1');
    $contact_link_url = get_option('vig_contact_bar_contact_url', '');
    $contact_href = ($contact_use_modal === '1') ? '#' : (!empty($contact_link_url) ? $contact_link_url : '#');

    $whatsapp_url = !empty($whatsapp_num) ? 'https://wa.me/' . preg_replace('/[^0-9]/', '', $whatsapp_num) : '#';

    // Ẩn chữ (chỉ hiện icon) theo thiết bị — mặc định hiện
    $wrap_classes = 'vig-hotline-container vig-collapsed'; // mặc định thu nhỏ khi load (tránh nhảy)
    if (get_option('vig_contact_bar_show_text_desktop', '1') !== '1') $wrap_classes .= ' vig-hide-text-desktop';
    if (get_option('vig_contact_bar_show_text_mobile', '1') !== '1')  $wrap_classes .= ' vig-hide-text-mobile';

    ?>
    <!-- VIG Contact Bar Container -->
    <div id="vig-hotline" class="<?php echo esc_attr($wrap_classes); ?>">
        <style>
            #vig-tawkto-trigger .vig-item-icon {
                background-image: url("<?php echo $tawkto_icon_url; ?>");
            }

            #vig-whatsapp-trigger .vig-item-icon {
                background-image: url("<?php echo $whatsapp_icon_url; ?>");
            }

            #vig-contact-trigger .vig-item-icon {
                background-image: url("<?php echo $contact_icon_url; ?>");
            }
        </style>

        <div class="vig-hotline-toggle" id="vig-hotline-toggle-btn"></div>
        <div class="vig-hotline-box">
            <a href="#" class="vig-hotline-item" id="vig-tawkto-trigger">
                <span class="vig-item-icon"></span>
                <span class="vig-item-text"><?php echo esc_html(vig_contact_bar_pll($tawkto_title)); ?></span>
                <span class="vig-tawkto-badge" id="vig-tawkto-badge" style="display:none;"></span>
            </a>

            <div class="vig-divider"></div>

            <a href="<?php echo esc_url($whatsapp_url); ?>" <?php echo $target_attr; ?> class="vig-hotline-item" id="vig-whatsapp-trigger">
                <span class="vig-item-icon"></span>
                <span class="vig-item-text"><?php echo esc_html(vig_contact_bar_pll($whatsapp_title)); ?></span>
            </a>

            <div class="vig-divider"></div>

            <a href="<?php echo esc_url($contact_href); ?>" <?php echo ($contact_use_modal !== '1' && $new_tab_enabled === '1') ? 'target="_blank"' : ''; ?> class="vig-hotline-item" id="vig-contact-trigger">
                <span class="vig-item-icon"></span>
                <span class="vig-item-text"><?php echo esc_html(vig_contact_bar_pll($contact_title)); ?></span>
            </a>
        </div>
    </div>

    <!-- Modal Popup khi nhấp nút Contact Form -->
    <?php if ($contact_use_modal === '1' && !empty($contact_shortcode)) : ?>
        <div class="vig-contact-modal" id="vig-contact-form-modal">
            <div class="vig-modal-overlay"></div>
            <div class="vig-modal-content">
                <span class="vig-modal-close" id="vig-contact-modal-close">&times;</span>
                <div class="vig-modal-body">
                    <?php echo do_shortcode($contact_shortcode); ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- CSS Giao diện (Responsive tối ưu) -->
    <style type="text/css">
        #vig-hotline{position:fixed;z-index:98;font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",sans-serif;transition:transform .4s cubic-bezier(.25, .8, .25, 1);user-select:none}
.vig-hotline-box{background:#fff;border-radius:16px;box-shadow:0 4px 20px rgba(0,0,0,.15);display:flex;box-sizing:border-box;border:1px solid rgba(0,0,0,.05)}
.vig-hotline-item{display:flex;align-items:center;position:relative;text-decoration:none!important;color:#111!important;font-weight:600;cursor:pointer;transition:opacity .2s ease}
.vig-hotline-item:hover{opacity:.8}
.vig-item-icon{width:35px;height:35px;background-size:contain;background-repeat:no-repeat;background-position:center;display:inline-block;flex-shrink:0;background-color:#1553a9;background-size:30px;border-radius:3px}
.vig-tawkto-badge{position:absolute;top:-4px;left:26px;min-width:18px;height:18px;padding:0 5px;border-radius:9px;background:#e53e3e;color:#fff;font-size:11px;font-weight:700;line-height:1;align-items:center;justify-content:center;box-sizing:border-box}
@media (max-width:767px){
#vig-hotline{bottom:-5px;left:50%;transform:translateX(-50%)}
#vig-hotline.vig-collapsed{transform:translate(-50%,calc(100% - 5px))}
.vig-hotline-toggle{background-image:url("<?php echo VIG_CONTACT_BAR_DEFAULT_TOGGLE_DOWN; ?>");width:80px;height:30px;background-size:25px;background-repeat:no-repeat;background-position:center;cursor:pointer;position:absolute;top:-30px;left:50%;transform:translateX(-50%);transition:all .3s ease;background-color:#1953a3;border-radius:20px 20px 0 0}
#vig-hotline.vig-collapsed .vig-hotline-toggle{transform:translateX(-50%) rotate(180deg);border-radius:0 0 20px 20px}
.vig-hotline-box{flex-direction:row;align-items:center;padding:10px 24px;height:64px}
.vig-hotline-item{font-size:15px;padding:0 16px;white-space:nowrap}
.vig-item-icon{margin-right:10px}
.vig-divider{width:1px;height:28px;background-color:#e2e8f0}
}
@media (max-width:640px){
.vig-hotline-item{font-size:12px}
}
@media (min-width:768px){
#vig-hotline{bottom:60px;right:-5px;left:auto;transform:translateX(0)}
#vig-hotline.vig-collapsed{transform:translateX(calc(100% - 5px))}
.vig-hotline-toggle{width:35px;height:80px;background-color:#1953a3;background-image:url("<?php echo VIG_CONTACT_BAR_DEFAULT_TOGGLE_RIGHT; ?>");background-position:center;background-size:25px;background-repeat:no-repeat;cursor:pointer;position:absolute;left:-35px;top:50%;transform:translateY(-50%);transition:all .3s ease;border-radius:20px 0 0 20px}
#vig-hotline.vig-collapsed .vig-hotline-toggle{transform:translateY(-50%) rotate(180deg);border-radius:0 20px 20px 0}
.vig-hotline-box{flex-direction:column;padding:12px 8px;width:200px}
.vig-hotline-item{font-size:14px;padding:10px 8px;width:100%;box-sizing:border-box}
.vig-item-icon{margin-right:12px}
.vig-divider{height:1px;width:calc(100% - 16px);margin:0 auto;background-color:#e2e8f0}
}
.vig-contact-modal{position:fixed;top:0;left:0;width:100%;height:100%;z-index:1000000;display:none}
.vig-modal-overlay{position:absolute;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,.4);backdrop-filter:blur(4px)}
.vig-modal-content{position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);background:#fff;padding:30px 24px 24px 24px;border-radius:16px;width:90%;max-width:480px;box-shadow:0 10px 30px rgba(0,0,0,.25);box-sizing:border-box;z-index:1000001;animation:vigFadeIn .3s ease}
@keyframes vigFadeIn{
from{opacity:0;transform:translate(-50%,-45%)}
to{opacity:1;transform:translate(-50%,-50%)}
}
.vig-modal-close{position:absolute;top:8px;right:14px;font-size:28px;font-weight:300;color:#a0aec0;cursor:pointer;transition:color .2s}
.vig-modal-close:hover{color:#4a5568}
.vig-modal-body{max-height:75vh;overflow-y:auto}
/* Ẩn chữ (chỉ hiện icon) theo thiết bị */
@media (min-width:768px){#vig-hotline.vig-hide-text-desktop .vig-item-text{display:none}#vig-hotline.vig-hide-text-desktop .vig-item-icon{margin-right:0}#vig-hotline.vig-hide-text-desktop .vig-hotline-item{justify-content:center;width:auto}#vig-hotline.vig-hide-text-desktop .vig-hotline-box{width:auto}#vig-hotline.vig-hide-text-desktop .vig-divider{width:28px}}
@media (max-width:767px){#vig-hotline.vig-hide-text-mobile .vig-item-text{display:none}#vig-hotline.vig-hide-text-mobile .vig-item-icon{margin-right:0}#vig-hotline.vig-hide-text-mobile .vig-hotline-item{justify-content:center}}
    </style>

    <!-- Logic xử lý sự kiện JavaScript -->
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            {
                var hotline = document.getElementById('vig-hotline');
                var toggle = document.getElementById('vig-hotline-toggle-btn');

                if (hotline && toggle) {
                    {
                        // Handle Toggle Collapse
                        toggle.addEventListener('click', function(e) {
                            {
                                e.preventDefault();
                                hotline.classList.toggle('vig-collapsed');
                                if (hotline.classList.contains('vig-collapsed')) {
                                    {
                                        localStorage.setItem('vig_contact_bar_state', 'collapsed');
                                    }
                                } else {
                                    {
                                        localStorage.setItem('vig_contact_bar_state', 'expanded');
                                    }
                                }
                            }
                        });

                        // Restore previous state if saved
                        var savedState = localStorage.getItem('vig_contact_bar_state');
                        if (savedState === 'collapsed') {
                            {
                                hotline.classList.add('vig-collapsed');
                            }
                        }
                    }
                }

                // Handle Tawk.to trigger — toggle mở/đóng chat
                var tawkTrigger = document.getElementById('vig-tawkto-trigger');
                if (tawkTrigger) {
                    tawkTrigger.addEventListener('click', function(e) {
                        e.preventDefault();
                        if (typeof Tawk_API === 'undefined') return;
                        if (typeof Tawk_API.isChatMaximized === 'function' && Tawk_API.isChatMaximized()) {
                            if (typeof Tawk_API.minimize === 'function') Tawk_API.minimize();
                        } else if (typeof Tawk_API.maximize === 'function') {
                            Tawk_API.maximize();
                        } else if (typeof Tawk_API.toggle === 'function') {
                            Tawk_API.toggle();
                        }
                    });
                }

                // Handle Contact Form Modal
                var contactTrigger = document.getElementById('vig-contact-trigger');
                var contactModal = document.getElementById('vig-contact-form-modal');

                if (contactTrigger && contactModal) {
                    {
                        var closeBtn = document.getElementById('vig-contact-modal-close');
                        var overlay = contactModal.querySelector('.vig-modal-overlay');

                        contactTrigger.addEventListener('click', function(e) {
                            {
                                e.preventDefault();
                                contactModal.style.display = 'block';
                            }
                        });

                        var closeModal = function() {
                            {
                                contactModal.style.display = 'none';
                            }
                        };

                        if (closeBtn) closeBtn.addEventListener('click', closeModal);
                        if (overlay) overlay.addEventListener('click', closeModal);
                    }
                }
            }
        });
    </script>
<?php
}
