<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class YPN_Admin {

    public function __construct() {
        add_action( 'admin_menu', [ $this, 'add_settings_page' ] );
        add_action( 'admin_init', [ $this, 'register_settings' ] );
    }

    /**
     * Register a top-level menu item in the WordPress admin sidebar
     */
    public function add_settings_page() {
        add_menu_page(
            __( 'YourPluginName Settings', 'yourpluginname' ), // Browser page title
            __( 'YourPluginName', 'yourpluginname' ),          // Sidebar menu label
            'manage_options',                                  // Required capability
            'ypn-settings',                                    // Unique menu slug
            [ $this, 'render_settings_page' ],                 // Render callback
            'dashicons-welcome-learn-more',                    // Dashicon in sidebar
            25                                                 // Position (right below Comments)
        );
    }

    /**
     * Register plugin settings using the WordPress Settings API
     */
    public function register_settings() {
        register_setting( 'ypn_settings_group', 'ypn_greeting_text', [
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => 'Hello from my custom WordPress plugin!',
        ] );
    }

    /**
     * Render the admin settings page HTML
     */
    public function render_settings_page() {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }
        ?>
        <div class="wrap">
            <h1><?php esc_html_e( 'YourPluginName Settings', 'yourpluginname' ); ?></h1>
            <p><?php esc_html_e( 'Configure your student portfolio plugin options below.', 'yourpluginname' ); ?></p>

            <form method="post" action="options.php">
                <?php
                settings_fields( 'ypn_settings_group' );
                do_settings_sections( 'ypn_settings_group' );
                ?>
                <table class="form-table" role="presentation">
                    <tr>
                        <th scope="row">
                            <label for="ypn_greeting_text">
                                <?php esc_html_e( 'Portfolio Banner Text', 'yourpluginname' ); ?>
                            </label>
                        </th>
                        <td>
                            <input
                                type="text"
                                id="ypn_greeting_text"
                                name="ypn_greeting_text"
                                value="<?php echo esc_attr( get_option( 'ypn_greeting_text', '' ) ); ?>"
                                class="regular-text"
                            />
                            <p class="description">
                                <?php esc_html_e( 'Displayed above your portfolio grid on the frontend.', 'yourpluginname' ); ?>
                            </p>
                        </td>
                    </tr>
                </table>
                <?php submit_button( __( 'Save Settings', 'yourpluginname' ) ); ?>
            </form>

            <hr />

            <h2><?php esc_html_e( 'Quick Usage Guide', 'yourpluginname' ); ?></h2>
            <ol>
                <li><?php esc_html_e( 'Go to Projects -> Add New in the left sidebar to publish your projects.', 'yourpluginname' ); ?></li>
                <li><?php esc_html_e( 'Create or edit any Page and paste the shortcode below:', 'yourpluginname' ); ?></li>
            </ol>
            <p><code>[student_portfolio count="3"]</code></p>
        </div>
        <?php
    }
}