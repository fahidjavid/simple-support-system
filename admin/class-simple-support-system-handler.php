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
class Simple_Support_System_Handler {

    /**
     * Add verify purchased code form.
     *
     * @since    1.0.0
     */
    public function sss_create_ticket_form() {

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
    public function sss_register_user_form() {

        ob_start();

        $user_registered = false;

        $envato = new Inspiry_Envato_API_Wrapper();

        $envato_token = get_theme_mod( 'inspiry_envato_token' );
        $mailbox = get_theme_mod( 'inspiry_mailbox_address' );

        $envato->set_envato_token( $envato_token );
        $envato->set_mailbox_address( $mailbox );

        if( isset( $_POST['username'] ) ) {

            $code = esc_attr( $_POST['purchase_code'] );

            $purchase_verify = $envato->verify_purchase( $code );

            if( ! is_wp_error( $purchase_verify ) ) {

                $purchase_exist = $envato->item_purchase_code_exists( $code );

                if( is_wp_error( $purchase_exist ) ) {
                    $user_registered = $purchase_exist->get_error_messages();
                } else {
                    $login_name = esc_attr( $_POST['username'] );
                    $user_email =  $_POST['email'];
                    $user_pass=  $_POST['password'];

                    $userdata = array(
                        'user_login'  =>  $login_name,
                        'user_email'  =>  $user_email,
                        'user_pass'   =>  $user_pass
                    );

                    $user_registered = $envato->register_user( $code, $userdata );
                }
            }

        }

        if( ! is_wp_error( $user_registered ) && $user_registered == true ) {
            ?>
            <div class="col-md-12">
                <?php
                $envato->display_message( __( 'Registration has been completed! Please login to get support.', 'inspiry' ) );
                echo do_shortcode( '[sss_login_user_form]' );
                ?>
            </div>
            <?php
        } else {

            echo '<div class="col-md-6">';
            echo '<div class="form-after-purchase">';
            $envato->display_message( __( 'Purchase Code Verified!', 'inspiry' ) );

            echo do_shortcode( '[sss_register_user]' );

            if ( is_wp_error( $user_registered ) ) {
                $envato->display_message( $user_registered );
            }
            echo '</div>';
            echo '</div>';
        }

        return ob_get_clean();
    }

    /**
     * Add verify purchased code form.
     *
     * @since    1.0.0
     */
    public function sss_verify_purchase_form() {

        ob_start();

        $envato = new Inspiry_Envato_API_Wrapper();

        $envato_token = get_theme_mod( 'inspiry_envato_token' );
        $mailbox = get_theme_mod( 'inspiry_mailbox_address' );

        $envato->set_envato_token( $envato_token );
        $envato->set_mailbox_address( $mailbox );

        if( ! is_user_logged_in() ) {

            $purchase_verify = false;
            $user_registered = false;

            if( isset( $_POST['username'] ) ) {

                $code = esc_attr( $_POST['purchase_code'] );

                $purchase_verify = $envato->verify_purchase( $code );

                if( ! is_wp_error( $purchase_verify ) ) {

                    $purchase_exist = $envato->item_purchase_code_exists( $code );

                    if( is_wp_error( $purchase_exist ) ) {
                        $user_registered = $purchase_exist->get_error_messages();
                    } else {
                        $login_name = esc_attr( $_POST['username'] );
                        $user_email =  $_POST['email'];
                        $user_pass=  $_POST['password'];

                        $userdata = array(
                            'user_login'  =>  $login_name,
                            'user_email'  =>  $user_email,
                            'user_pass'   =>  $user_pass
                        );

                        $user_registered = $envato->register_user( $code, $userdata );
                    }
                }

            } else if( isset( $_POST['purchase_code'] ) ) {

                $code = esc_attr( $_POST['purchase_code'] );
                $purchase_verify = $envato->verify_purchase( $code );

                if( ! is_wp_error( $purchase_verify ) && $purchase_verify == true ) {

                    $purchase_verify = $envato->item_purchase_code_exists( $code );

                    if( ! is_wp_error( $purchase_verify ) && $purchase_verify == false  ) {
                        $purchase_verify = true;
                    }


                }
            }

            if( ! is_wp_error( $purchase_verify ) && $purchase_verify == true) {

                if( ! is_wp_error( $user_registered ) && $user_registered == true ) {
                    ?>
                    <div class="col-md-12">
                        <?php
                            $envato->display_message( __( 'Registration has been completed! Please login to get support.', 'inspiry' ) );
                            echo do_shortcode( '[sss_login_user_form]' );
                        ?>
                    </div>
                    <?php
                } else {

                    echo '<div class="col-md-6">';
                    echo '<div class="form-after-purchase">';
                    $envato->display_message( __( 'Purchase Code Verified!', 'inspiry' ) );

                    echo do_shortcode( '[sss_register_user]' );

                    if ( is_wp_error( $user_registered ) ) {
                        $envato->display_message( $user_registered );
                    }
                    echo '</div>';
                    echo '</div>';
                }

            } else {

                echo '<div class="col-md-6">';
                echo '<div class="wrapper-register-form">';

                echo do_shortcode( '[sss_verify_purchase_form]' );

                if ( is_wp_error( $purchase_verify ) ) {
                    $envato->display_message( $purchase_verify );
                }
                echo '</div>';
                echo '</div>';

            }

        } else {

            $envato->display_message( "You are already logged in!" );

        }

        return ob_get_clean();
    }

    public function list_user_purchases() {

        $envato = new Inspiry_Envato_API_Wrapper();

        $envato_token = get_theme_mod( 'inspiry_envato_token' );
        $mailbox = get_theme_mod( 'inspiry_mailbox_address' );

        $envato->set_envato_token( $envato_token );
        $envato->set_mailbox_address( $mailbox );

        if( is_user_logged_in() ) {

            $current_user = wp_get_current_user();
            $user_id = $current_user->ID;
            $add_purchase = false;

            if( isset( $_POST['purchase_code'] ) ) {
                $add_purchase = $envato->add_purchase_code( $user_id, $_POST['purchase_code'] );
            }

            $codes = get_user_meta( $user_id, 'item_purchase_code' );

            $envato->list_user_purchases( $codes );

            ?>
            <form method="post" id="add-purchase" class="support">

                <p><?php _e( 'Enter your new item purchase code', 'inspiry' ) ?></p>
                <input type="text" name="purchase_code" value="<?php echo ( isset( $_POST['purchase_code'] ) ) ? $_POST['purchase_code'] : ''; ?>"/>
                <input type="submit" value="<?php _e( 'Add','inspiry' ); ?>">

            </form>
            <?php

            if ( is_wp_error( $add_purchase ) ) {
                $envato->display_message( $add_purchase->get_error_messages()[0], true );
            } else if ( $add_purchase ) {
                $envato->display_message( __( 'Your new item purchase code has been added.', 'inspiry' ) );
            }

        } else {
            $envato->display_message( __( 'Login Required.', 'inspiry' ), true );
        }
    }

}