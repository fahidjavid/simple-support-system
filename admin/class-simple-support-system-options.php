<?php
/**
 * Responsible for the Simple Support System options page.
 */

class Simple_Support_System_Options {

    function __construct() {
        add_action( 'admin_menu', array( $this, 'sss_add_settings_page' ) );
        add_action( 'admin_init', array( $this, 'initialize_sss_options' ) );
    }

    /**
     * Add plugin settings page
     */
    public function sss_add_settings_page() {

        add_menu_page(
            esc_html__( 'Simple Support System', 'simple-support-system' ),
            esc_html__( 'SSS Settings', 'simple-support-system' ),
            'administrator',
            'simple_support_system',
            array( $this, 'simple_support_system_settings'),
            'dashicons-sos',
            '65'
        );

    }

    /**
     * Display simple support settings settings page
     */
    public function simple_support_system_settings() {
        ?>
        <!-- Create a header in the default WordPress 'wrap' container -->
        <div class="wrap">

            <h2><?php esc_html_e( 'Simple Support System Settings', 'simple-support-system' ); ?></h2>

            <!-- Make a call to the WordPress function for rendering errors when settings are saved. -->
            <?php settings_errors(); ?>

            <!-- Create the form that will be used to render our options -->
            <form method="post" action="options.php">
                <?php

                settings_fields( 'simple_support_system_option_group' );
                do_settings_sections( 'simple_support_system_page' );

                submit_button();

                ?>
            </form>

        </div><!-- /.wrap -->
        <?php
    }

    /**
     * Initialize simple support settings page
     */
    public function initialize_sss_options(){

        /**
         * Section
         */
        add_settings_section(
            'simple_support_system_section',
            false,
            array( $this, 'sss_section_desc'),
            'simple_support_system_page'
        );

        add_settings_field(
            'envato_api_token',
            esc_html__( 'Envato API Token', 'simple-support-system' ),
            array( $this, 'text_option_field' ),
            'simple_support_system_page',
            'simple_support_system_section',
            array(
                'field_id'        => 'envato_api_token',
                'field_option'    => 'simple_support_system_option',
                'field_default'   => '',
                'field_description' => __( 'Please provide an <a href="https://build.envato.com/create-token/" target="_blank">Envato API Token</a> here.', 'simple-support-system' ),
            )
        );

        add_settings_field(
            'mailbox_address',
            __( 'MailBox Address', 'simple-support-system' ),
            array( $this, 'text_option_field' ),
            'simple_support_system_page',
            'simple_support_system_section',
            array(
                'field_id'        => 'mailbox_address',
                'field_option'    => 'simple_support_system_option',
                'field_default'   => get_option('admin_email'),
                'field_description' => __( 'Please provide an email address where you would like to receive ticket requests.', 'simple-support-system' ),
            )
        );

        add_settings_field(
            'restrict_admin',
            __( 'Restrict Admin Side Access', 'simple-support-system' ),
            array( $this, 'select_option_field' ),
            'simple_support_system_page',
            'simple_support_system_section',
            array (
                'field_id'          => 'restrict_admin',
                'field_option'      => 'simple_support_system_option',
                'field_default'     => 's',
                'field_options'     => array(
                    '0'   => __( 'Subscriber ( Level 0 )', 'simple-support-system' ),
                    '1'    => __( 'Contributor ( Level 1 )', 'simple-support-system' ),
                    '2'    => __( 'Author ( Level 2 )', 'simple-support-system' ),
                ),
                'field_description' => __( 'Restrict admin side access to any user level equal to or below the selected user level.', 'simple-support-system' ),
            )
        );

        /**
         * Register Settings
         */
        register_setting( 'simple_support_system_option_group', 'simple_support_system_option' );

    }

    /**
     * Simple support system section description
     */
    public function sss_section_desc() {
        echo '<p>'. __( 'Using options provided below, You can configure the simple support system settings.', 'simple-support-system' ) . '</p>';
    }

    /**
     * Reusable text option field for settings page
     *
     * @param $args array   field arguments
     */
    public function text_option_field( $args ) {
        extract( $args );
        if( $field_id ) {

            $fields_values = get_option( 'simple_support_system_option' );

            if( isset( $fields_values[$field_id] ) ) {
                $field_value = $fields_values[$field_id];
            } else {
                $field_value = '';
            }

            echo '<input name="' . $field_option . '[' . $field_id . ']" class="sss-text-field '. $field_id .'" value="' . $field_value . '" />';
            if ( isset( $field_description ) ) {
                echo '<br/><label class="sss-field-description">' . $field_description . '</label>';
            }

        } else {
            esc_html_e( 'Field id is missing!', 'simple-support-system' );
        }
    }

    /**
     * Reusable select options field for settings page
     *
     * @param $args array field arguments
     */
    public function select_option_field( $args ) {
        extract( $args );
        if( $field_id ) {

                $fields_values = get_option( 'simple_support_system_option' );

                // Default value or stored value based on option field
                if( isset( $fields_values[$field_id] ) ) {
                    $existing_value = $fields_values[$field_id];
                } else {
                    $existing_value = '';
                }
            ?>
            <select name="<?php echo $field_option . '[' . $field_id . ']'; ?>" class="sss-select-field <?php echo $field_id; ?>">
                <?php foreach( $field_options as $key => $value ) { ?>
                    <option value="<?php echo $key; ?>" <?php selected( $existing_value, $key ); ?>><?php echo $value; ?></option>
                <?php } ?>
            </select>
            <?php
            if ( isset( $field_description ) ) {
                echo '<br/><label class="sss-field-description">' . $field_description . '</label>';
            }
        } else {
            esc_html_e( 'Field id is missing!', 'simple-support-system' );
        }
    }
}

new Simple_Support_System_Options;