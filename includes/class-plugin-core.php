<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class YPN_Core {

    public function __construct() {
        add_action( 'init', [ $this, 'register_project_cpt' ] );
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_frontend_assets' ] );
        add_shortcode( 'student_portfolio', [ $this, 'render_portfolio_shortcode' ] );

        add_action( 'wp_ajax_ypn_like_project', [ $this, 'handle_like_ajax' ] );
        add_action( 'wp_ajax_nopriv_ypn_like_project', [ $this, 'handle_like_ajax' ] );
    }

    public function register_project_cpt() {
        $args = [
            'labels'       => [
                'name'          => __( 'Projects', 'yourpluginname' ),
                'singular_name' => __( 'Project', 'yourpluginname' ),
                'add_new_item'  => __( 'Add New Project', 'yourpluginname' ),
            ],
            'public'       => true,
            'has_archive'  => true,
            'menu_icon'    => 'dashicons-portfolio',
            'supports'     => [ 'title', 'editor', 'thumbnail', 'excerpt' ],
            'show_in_rest' => true,
        ];
        register_post_type( 'student_project', $args );
    }

    public function enqueue_frontend_assets() {
        wp_enqueue_style( 'ypn-style', YPN_URL . 'assets/css/style.css', [], YPN_VERSION );
        wp_enqueue_script( 'ypn-script', YPN_URL . 'assets/js/script.js', [], YPN_VERSION, true );

        wp_localize_script( 'ypn-script', 'ypnData', [
            'ajaxUrl' => admin_url( 'admin-ajax.php' ),
            'nonce'   => wp_create_nonce( 'ypn_ajax_nonce' ),
        ] );
    }

    public function render_portfolio_shortcode( $atts ) {
        $atts = shortcode_atts( [ 'count' => 3 ], $atts, 'student_portfolio' );
        $greeting = get_option( 'ypn_greeting_text', '' );

        $query = new WP_Query( [
            'post_type'      => 'student_project',
            'posts_per_page' => absint( $atts['count'] ),
        ] );

        ob_start();
        echo '<div class="ypn-wrapper">';
        if ( ! empty( $greeting ) ) {
            echo '<p class="ypn-banner">' . esc_html( $greeting ) . '</p>';
        }

        if ( $query->have_posts() ) {
            echo '<div class="ypn-grid">';
            while ( $query->have_posts() ) {
                $query->the_post();
                $post_id = get_the_ID();
                $likes   = (int) get_post_meta( $post_id, '_ypn_likes', true );
                ?>
                <div class="ypn-card">
                    <h3><?php echo esc_html( get_the_title() ); ?></h3>
                    <p><?php echo esc_html( get_the_excerpt() ); ?></p>
                    <button class="ypn-like-btn" data-id="<?php echo esc_attr( $post_id ); ?>">
                        Appreciate (<span class="ypn-like-count"><?php echo esc_html( $likes ); ?></span>)
                    </button>
                </div>
                <?php
            }
            echo '</div>';
            wp_reset_postdata();
        } else {
            echo '<p>No projects added yet. Add some from Dashboard -> Projects!</p>';
        }
        echo '</div>';

        return ob_get_clean();
    }

    public function handle_like_ajax() {
        check_ajax_referer( 'ypn_ajax_nonce', 'nonce' );

        $post_id = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : 0;
        if ( ! $post_id ) {
            wp_send_json_error( 'Invalid Project ID' );
        }

        $likes = (int) get_post_meta( $post_id, '_ypn_likes', true );
        $likes++;
        update_post_meta( $post_id, '_ypn_likes', $likes );

        wp_send_json_success( [ 'likes' => $likes ] );
    }
}