<?php
/**
 * VIG self-update qua GitHub Releases (Plugin Update Checker).
 * Copy GIỐNG NHAU vào mọi VIG plugin. Guard: chưa vendor PUC -> no-op (không fatal).
 * Kích hoạt: (1) vendor PUC vào <plugin>/plugin-update-checker/,
 *            (2) tạo repo github.com/vigdigital/<slug>, (3) tag Release theo Version header.
 * Repo private: define('VIG_GH_TOKEN', '...') trong wp-config.php (KHÔNG hardcode vào plugin).
 * Xem: knowledge/wp-skills/VIG Plugin Distribution (GitHub + PUC).md
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! function_exists( 'vig_setup_updates' ) ) {
    function vig_setup_updates( string $main_file, string $slug, string $org = 'vigdigital' ): void {
        $puc = plugin_dir_path( $main_file ) . 'plugin-update-checker/plugin-update-checker.php';
        if ( ! file_exists( $puc ) ) {
            return; // chưa vendor PUC -> bỏ qua
        }
        require_once $puc;
        if ( ! class_exists( '\\YahnisElsts\\PluginUpdateChecker\\v5\\PucFactory' ) ) {
            return;
        }
        $checker = \YahnisElsts\PluginUpdateChecker\v5\PucFactory::buildUpdateChecker(
            "https://github.com/{$org}/{$slug}/",
            $main_file,
            $slug
        );
        $checker->setBranch( 'main' );
        $api = $checker->getVcsApi();
        if ( method_exists( $api, 'enableReleaseAssets' ) ) {
            $api->enableReleaseAssets(); // ưu tiên file .zip attach vào Release
        }
        if ( defined( 'VIG_GH_TOKEN' ) && VIG_GH_TOKEN ) {
            $checker->setAuthentication( VIG_GH_TOKEN ); // repo private
        }
    }
}
