<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Simple_Support_System
 * @subpackage Simple_Support_System/admin
 * @author     Fahid Javid <fahidjavid@gmail.com>
 */
class Simple_Support_System_Shortcodes {

    /**
     * Add verify purchased code form.
     *
     * @since    1.0.0
     */
    public function sss_create_ticket_form_shortcode() {

        ob_start();

//        require_once get_template_directory() . '/support/envato-api-wrapper.php';

        $ticket_submit = false;

        $envato = new Inspiry_Envato_API_Wrapper();

        $envato_token = get_theme_mod( 'inspiry_envato_token' );
        $mailbox = get_theme_mod( 'inspiry_mailbox_address' );

        $envato->set_envato_token( $envato_token );
        $envato->set_mailbox_address( $mailbox );

        $current_user = wp_get_current_user();
        $user_id = $current_user->ID;

        $submit_ticket = $envato->submit_ticket();

        if( $submit_ticket == true && ! is_wp_error( $submit_ticket ) ) {
            ?>
            <div class="after-ticket-box">
                <?php

                echo '<p class="text-after-submit">'. __( 'Your Ticket Has Been Submitted Successfully!', 'inspiry') .'</p>';
                echo '<p class="text-check-email">'. __( 'Please check your email for more details.', 'inspiry') .'</p>';
                ?>
            </div>
            <?php

        } else {

            ?>

            <form method="post" id="submit-ticket" class="support">
                <?php

                //                        delete_user_meta( $user_id, 'item_purchase_code');

                //                        add_user_meta($current_user->ID,'item_purchase_code','900b8b25-bb70-4c91-80bd-1c06ac78ec35');
                //                        add_user_meta($current_user->ID,'item_purchase_code','a6c68a43-fa96-486a-8a73-13bd379454ec');
                //                        add_user_meta($current_user->ID,'item_purchase_code','59fc1f32-cae0-4c7f-a41b-5d6384ae0a07');
                //                        add_user_meta($current_user->ID,'item_purchase_code','a8494c59-30d4-44b2-b13d-11794e376201');


                $codes = get_user_meta( $user_id, 'item_purchase_code' );

                if( ! empty( $codes ) ) {

                    echo '<h2>'. __( 'Select a theme:', 'inspiry' ) .'</h2>';

                    foreach ( $codes as $code ) {

                        $purchase_info = $envato->verify_purchase( $code, true );

                        if( ! is_wp_error( $purchase_info ) ) {

                            $today = new DateTime( 'now' );
                            $support_util = new DateTime( $purchase_info['supported_until'] );
                            $supported = '';

                            if ( $support_util < $today ) {
                                $supported = 'disabled';
                            }
                            if ( ! empty( $purchase_info['item_name'] ) ) {
                                ?>
                                <label class="inspiry-select-theme">
                                    <?php
                                    echo '<input class="theme-select-radio" type="radio" name="theme" value="' . $purchase_info['item_name'] . '" ' . $supported . '><span class="' . $supported . '"">' . $purchase_info['item_name'] . '</span><br>';
                                    ?>
                                </label>
                                <br>
                                <?php
                            }
                        }
                    }
                    ?>
                    <br>
                    <h2><?php _e( 'Ask your question:', 'inspiry' ); ?></h2>
                    <input type="text" name="title" value="<?php echo ( ! empty( $_POST['title'] )? $_POST['title'] : '' ) ?>" placeholder="Topic Title">
                    <textarea name="message" cols="30" rows="10"><?php echo ( ! empty( $_POST['message'] )? $_POST['message'] : '' ) ?></textarea>
                    <input type="submit" value="Submit">
                    <?php
                } else {
                    echo '<p class="non-pcode-user">'. __( 'Please enter your item purchase code on <a href="/your-purchases">Your Purchases</a> page, before you submit a ticket.', 'inspiry' ) . '</p>';
                }

                ?>
            </form>
            <?php

            if ( is_wp_error( $submit_ticket ) ) {
                $envato->display_message( $submit_ticket );
            }
        }
        return ob_get_clean();
    }

    /**
     * Add verify purchased code form.
     *
     * @since    1.0.0
     */
    public function sss_register_user_form_shortcode() {

        ob_start();
        ?>
        <h2><?php _e( 'Please register below.', 'inspiry' ); ?></h2>
        <form method="post" id="register-user" class="support">
            <label for="username"><?php _e( 'Username', 'inspiry' ); ?></label>
            <input type="text" name="username" value="<?php echo ( isset( $_POST['username'] ) )? esc_attr( $_POST['username'] ) : ''; ?>"/>
            <label for="email"><?php _e( 'Email', 'inspiry' ); ?></label>
            <input type="email" name="email" value="<?php echo ( isset( $_POST['email'] ) )? esc_attr( $_POST['email'] ) : ''; ?>"/>
            <label for="password"><?php _e( 'Password', 'inspiry' ); ?></label>
            <input type="password" name="password" /><br>
            <input type="hidden" name="purchase_code" value="<?php echo ( isset( $_POST['purchase_code'] ) )? esc_attr( $_POST['purchase_code'] ) : ''; ?>">
            <input type="submit" value="Register">
        </form>
        <?php
        return ob_get_clean();
    }

    /**
     * Add verify purchased code form.
     *
     * @since    1.0.0
     */
    public function sss_verify_purchase_form_shortcode() {

        ob_start();
        ?>
        <h2><?php esc_html_e( 'Register', 'inspiry' ); ?></h2>
        <form method="post" id="verify-purchase" class="support">
            <p><?php _e( 'Enter your <a href="http://support.inspirythemes.com/knowledgebase/how-to-get-themeforest-item-purchase-code/" target="_blank">item purchase code</a> and verify.', 'inspiry' ) ?></p>
            <input type="text" name="purchase_code" />
            <input type="submit" value="Verify">
        </form>
        <?php
        return ob_get_clean();
    }

    /**
     * Add verify purchased code form.
     *
     * @since    1.0.0
     */
    public function sss_login_user_form_shortcode() {

        ob_start();
        ?>
        <div class="wrapper-login-form">
            <h2><?php esc_html_e( 'Login', 'inspiry' ); ?></h2>
            <?php
            if ( ! is_user_logged_in() ) { // Display WordPress login form:
                $args = array(
                    'redirect' => '/forums',
                    'form_id' => 'inspiry-login'
                );
                wp_login_form( $args );
            }
            echo '<a class="inspiry-lostpassword" href="'. wp_lostpassword_url() .'">Forgot Password?</a>';
            ?>
        </div>
        <?php
        return ob_get_clean();
    }

}