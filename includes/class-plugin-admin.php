<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class YPN_Admin {

    public function __construct() {
        add_action( 'admin_menu', [ $this, 'add_settings_page' ] );
        add_action( 'admin_init', [ $this, 'register_settings' ] );
    }

    public function add_settings_page() {
        add_options_page(
            __( 'YourPluginName Settings', 'yourpluginname' ),
            __( 'YourPluginName', 'yourpluginname' ),
            'manage_options',
            'ypn-settings',
            [ $this, 'render_settings_page' ]
        );
    }

    public function register_settings() {
        register_setting( 'ypn_settings_group', 'ypn_greeting_text', [
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => '',
        ] );
    }

    public function render_settings_page() {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }
        ?>
        <div class="wrap">
            <h1><?php esc_html_e( 'YourPluginName Settings', 'yourpluginname' ); ?></h1>
            <form method="post" action="options.php">
                <?php settings_fields( 'ypn_settings_group' ); ?>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="ypn_greeting_text">Portfolio Banner Text</label></th>
                        <td>
                            <input type="text" id="ypn_greeting_text" name="ypn_greeting_text"
                                   value="<?php echo esc_attr( get_option( 'ypn_greeting_text' ) ); ?>"
                                   class="regular-text" />
                            <p class="description">Displayed above your <code>[student_portfolio]</code> shortcode.</p>
                        </td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }
}