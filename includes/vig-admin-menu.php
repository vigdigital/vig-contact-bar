<?php
/**
 * VIG Toolkit — shared parent admin menu for VIG plugins.
 * Copy this file IDENTICALLY into every VIG plugin. function_exists guards prevent fatals on duplicate load;
 * the $admin_page_hooks check ensures the parent menu is registered only once (first plugin to run admin_menu).
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! function_exists( 'vig_toolkit_register_parent' ) ) {
    function vig_toolkit_register_parent(): void {
        global $admin_page_hooks;
        if ( isset( $admin_page_hooks['vig-toolkit'] ) ) {
            return; // already registered by another VIG plugin
        }
        add_menu_page(
            'VIG Toolkit',                 // page title
            'VIG Toolkit',                 // sidebar label
            'manage_options',
            'vig-toolkit',                 // shared parent slug (fixed across VIG plugins)
            'vig_toolkit_dashboard',
            'dashicons-screenoptions',
            58
        );
        // Rename the auto-created duplicate submenu "VIG Toolkit" -> "Dashboard"
        add_submenu_page( 'vig-toolkit', 'VIG Toolkit', 'Dashboard', 'manage_options', 'vig-toolkit', 'vig_toolkit_dashboard' );
        // Guideline page (position 99 -> keep it last)
        add_submenu_page( 'vig-toolkit', 'Guideline', 'Guideline', 'manage_options', 'vig-toolkit-guideline', 'vig_toolkit_guideline', 99 );
    }
}

if ( ! function_exists( 'vig_toolkit_dashboard' ) ) {
    function vig_toolkit_dashboard(): void {
        global $submenu;
        $items = isset( $submenu['vig-toolkit'] ) ? $submenu['vig-toolkit'] : array();
        $guideline_url = admin_url( 'admin.php?page=vig-toolkit-guideline' );
        ?>
        <div class="wrap">
            <h1>VIG Toolkit</h1>
            <p style="font-size:14px;max-width:780px;">
                Central hub for the VIG Digital tools installed on this site. Open a tool's settings from the cards
                below or the submenu on the left. For how-to instructions, see
                <a href="<?php echo esc_url( $guideline_url ); ?>">Guideline</a>.
            </p>

            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:16px;margin-top:20px;max-width:900px;">
                <?php foreach ( $items as $it ) :
                    $title = isset( $it[0] ) ? wp_strip_all_tags( $it[0] ) : '';
                    $slug  = isset( $it[2] ) ? $it[2] : '';
                    if ( $slug === '' || $slug === 'vig-toolkit' ) {
                        continue; // skip the Dashboard entry itself
                    }
                    $url = ( strpos( $slug, '.php' ) !== false ) ? admin_url( $slug ) : admin_url( 'admin.php?page=' . $slug );
                    ?>
                    <a href="<?php echo esc_url( $url ); ?>"
                       style="display:block;background:#fff;border:1px solid #dcdcde;border-radius:8px;padding:18px 20px;text-decoration:none;color:#1d2327;box-shadow:0 1px 2px rgba(0,0,0,.04);transition:box-shadow .15s;">
                        <span class="dashicons dashicons-admin-generic" style="color:#2271b1;font-size:22px;width:22px;height:22px;"></span>
                        <strong style="display:block;font-size:15px;margin:8px 0 4px;"><?php echo esc_html( $title ); ?></strong>
                        <span style="color:#646970;font-size:13px;">Open &rarr;</span>
                    </a>
                <?php endforeach; ?>
            </div>

            <p style="margin-top:26px;color:#646970;">
                Built &amp; maintained by <a href="https://vigdigital.com" target="_blank" rel="noopener">VIG Digital</a>.
            </p>
        </div>
        <?php
    }
}

if ( ! function_exists( 'vig_toolkit_guideline' ) ) {
    /**
     * Guideline — in-admin Markdown documentation viewer.
     * Source: .md files in wp-content/vig-guideline/ (images in the images/ subfolder, relative paths).
     * Rendered with Parsedown (bundled next to this file — no CDN). Admins can view / edit / create topics.
     * The same .md files can later feed a central docs site (MkDocs/Docsify) without rework.
     */
    function vig_toolkit_guideline(): void {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }
        $dir      = trailingslashit( WP_CONTENT_DIR ) . 'vig-guideline/';
        $baseurl  = trailingslashit( content_url() ) . 'vig-guideline/';
        wp_mkdir_p( $dir );
        $writable = wp_is_writable( $dir );

        $force_doc = '';
        $force_edit = false;
        $notice = '';

        // Create a new topic (.md file)
        if ( isset( $_POST['vig_gl_new'] ) && check_admin_referer( 'vig_gl_new' ) && $writable ) {
            $slug = sanitize_title( wp_unslash( $_POST['vig_gl_newslug'] ) );
            if ( $slug ) {
                $f = $dir . $slug . '.md';
                if ( ! file_exists( $f ) ) {
                    file_put_contents( $f, '# ' . ucfirst( str_replace( '-', ' ', $slug ) ) . "\n\n" );
                }
                $force_doc = $slug;
                $force_edit = true;
            }
        }

        // Save an edited topic
        if ( isset( $_POST['vig_gl_save'] ) && check_admin_referer( 'vig_gl_edit' ) && $writable ) {
            $slug = preg_replace( '/[^a-z0-9_-]/i', '', wp_unslash( $_POST['vig_gl_slug'] ) );
            $f = $dir . $slug . '.md';
            if ( $slug && file_exists( $f ) ) {
                file_put_contents( $f, wp_unslash( $_POST['vig_gl_content'] ) );
                $notice = 'Saved.';
                $force_doc = $slug;
            }
        }

        // Build topic list (title = first "# heading", fallback to filename)
        $md_files = glob( $dir . '*.md' );
        if ( ! is_array( $md_files ) ) {
            $md_files = array();
        }
        sort( $md_files );
        $topics = array();
        foreach ( (array) $md_files as $path ) {
            $slug  = basename( $path, '.md' );
            $fh    = fopen( $path, 'r' );
            $first = $fh ? (string) fgets( $fh ) : '';
            if ( $fh ) { fclose( $fh ); }
            $title = trim( preg_replace( '/^#\s*/', '', $first ) );
            if ( '' === $title ) {
                $title = ucfirst( str_replace( '-', ' ', preg_replace( '/^\d+[-_]/', '', $slug ) ) );
            }
            $topics[ $slug ] = $title;
        }

        // Current topic + mode
        $cur = $force_doc ? $force_doc : ( isset( $_GET['doc'] ) ? preg_replace( '/[^a-z0-9_-]/i', '', wp_unslash( $_GET['doc'] ) ) : '' );
        if ( ! isset( $topics[ $cur ] ) ) {
            $cur = $topics ? array_key_first( $topics ) : '';
        }
        $edit = $force_edit || ( ! empty( $_GET['edit'] ) && $writable );
        $md   = ( $cur && file_exists( $dir . $cur . '.md' ) ) ? (string) file_get_contents( $dir . $cur . '.md' ) : '';

        // Render markdown -> HTML (safe mode) + point relative images at the images/ folder
        if ( ! class_exists( 'Parsedown' ) ) { // tránh xung đột nếu plugin khác (vd Rank Math) đã bundle Parsedown
            require_once __DIR__ . '/Parsedown.php';
        }
        $pd = new Parsedown();
        $pd->setSafeMode( true );
        $html = '' !== $md ? $pd->text( $md ) : '<p>No content yet.</p>';
        $html = preg_replace( '#(src=["\'])(?:\./)?images/#', '$1' . $baseurl . 'images/', $html );

        $base = admin_url( 'admin.php?page=vig-toolkit-guideline' );
        ?>
        <div class="wrap">
            <?php if ( $notice ) : ?><div class="notice notice-success is-dismissible"><p><?php echo esc_html( $notice ); ?></p></div><?php endif; ?>
            <h1 style="display:inline-block;">Guideline</h1>
            <?php if ( $cur && $writable && ! $edit ) : ?>
                <a href="<?php echo esc_url( add_query_arg( array( 'doc' => $cur, 'edit' => 1 ), $base ) ); ?>" class="page-title-action">Edit</a>
            <?php endif; ?>

            <div style="display:flex;gap:24px;align-items:flex-start;margin-top:14px;">
                <div style="flex:0 0 240px;background:#fff;border:1px solid #dcdcde;border-radius:8px;padding:12px;">
                    <input type="search" id="vig-gl-search" placeholder="Search topics&hellip;" style="width:100%;margin-bottom:10px;" />
                    <ul id="vig-gl-nav" style="margin:0;list-style:none;">
                        <?php foreach ( $topics as $slug => $title ) : ?>
                            <li class="vig-gl-topic" style="margin:0 0 2px;">
                                <a href="<?php echo esc_url( add_query_arg( 'doc', $slug, $base ) ); ?>"
                                   style="display:block;padding:6px 10px;border-radius:5px;text-decoration:none;<?php echo ( $slug === $cur ) ? 'background:#2271b1;color:#fff;' : 'color:#1d2327;'; ?>">
                                    <?php echo esc_html( $title ); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <hr style="margin:12px 0;" />
                    <form method="post" style="display:flex;gap:6px;">
                        <?php wp_nonce_field( 'vig_gl_new' ); ?>
                        <input type="text" name="vig_gl_newslug" placeholder="new-topic" style="flex:1;min-width:0;" <?php disabled( ! $writable ); ?> />
                        <button type="submit" name="vig_gl_new" class="button" <?php disabled( ! $writable ); ?>>+ New</button>
                    </form>
                    <?php if ( ! $writable ) : ?>
                        <p style="color:#b32d2e;font-size:12px;margin:8px 0 0;">Folder not writable — in-admin editing off. Edit <code>.md</code> via FTP.</p>
                    <?php endif; ?>
                </div>

                <div style="flex:1;min-width:0;background:#fff;border:1px solid #dcdcde;border-radius:8px;padding:24px 28px;">
                    <?php if ( $edit ) : ?>
                        <form method="post">
                            <?php wp_nonce_field( 'vig_gl_edit' ); ?>
                            <input type="hidden" name="vig_gl_slug" value="<?php echo esc_attr( $cur ); ?>" />
                            <p><strong>Editing</strong> <code><?php echo esc_html( $cur ); ?>.md</code> — Markdown. Images: put files in <code>vig-guideline/images/</code> and use <code>![alt](images/file.png)</code>.</p>
                            <textarea name="vig_gl_content" rows="26" style="width:100%;font-family:Menlo,Consolas,monospace;font-size:13px;line-height:1.5;"><?php echo esc_textarea( $md ); ?></textarea>
                            <p style="margin-top:12px;">
                                <button type="submit" name="vig_gl_save" class="button button-primary">Save</button>
                                <a href="<?php echo esc_url( add_query_arg( 'doc', $cur, $base ) ); ?>" class="button">Cancel</a>
                            </p>
                        </form>
                    <?php else : ?>
                        <div class="vig-gl-doc" style="max-width:820px;"><?php echo wp_kses_post( $html ); ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <style>
            .vig-gl-doc h1,.vig-gl-doc h2,.vig-gl-doc h3{margin-top:1.3em}
            .vig-gl-doc img{max-width:100%;height:auto;border-radius:6px}
            .vig-gl-doc pre{background:#f6f7f7;padding:12px;border-radius:6px;overflow:auto}
            .vig-gl-doc code{background:#f6f7f7;padding:2px 5px;border-radius:3px}
            .vig-gl-doc blockquote{border-left:4px solid #2271b1;margin:1em 0;padding:2px 14px;color:#50575e}
            .vig-gl-doc table{border-collapse:collapse}.vig-gl-doc td,.vig-gl-doc th{border:1px solid #dcdcde;padding:6px 10px}
        </style>
        <script>
        (function(){
            var s=document.getElementById('vig-gl-search'); if(!s){return;}
            s.addEventListener('input',function(){
                var q=this.value.toLowerCase();
                document.querySelectorAll('#vig-gl-nav .vig-gl-topic').forEach(function(li){
                    li.style.display = li.textContent.toLowerCase().indexOf(q) > -1 ? '' : 'none';
                });
            });
        })();
        </script>
        <?php
    }
}
