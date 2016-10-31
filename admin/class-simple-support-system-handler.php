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
     * Verify user purchase code and register
     *
     * @since    1.0.0
     */
    public function sss_register_verified_user_form() {

        ob_start();

        $envato = new SSS_Envato_API_Wrapper();

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

                    $envato->display_message( esc_html__( 'Registration has been completed! Please login to get support.', 'simple-support-system' ) );

                } else {

                    $envato->display_message( esc_html__( 'Purchase Code Verified! Please register below.', 'simple-support-system' ) );

                    ?>
                    <div class="sss-form-wrapper">
                        <form method="post" id="register-user-form" class="register-user-form sss-form">
                            <p>
                                <label for="username"><?php esc_html_e( 'Username', 'simple-support-system' ); ?></label>
                                <input type="text" name="username" value="<?php echo ( isset( $_POST['username'] ) ) ? esc_attr( $_POST['username'] ) : ''; ?>"/>
                            </p>
                            <p>
                                <label for="email"><?php esc_html_e( 'Email', 'simple-support-system' ); ?></label>
                                <input type="email" name="email" value="<?php echo ( isset( $_POST['email'] ) ) ? esc_attr( $_POST['email'] ) : ''; ?>"/>
                            </p>
                            <p>
                                <label for="password"><?php esc_html_e( 'Password', 'simple-support-system' ); ?></label>
                                <input type="password" name="password"/><br>
                            </p>
                            <input type="hidden" name="purchase_code" value="<?php echo ( isset( $_POST['purchase_code'] ) ) ? esc_attr( $_POST['purchase_code'] ) : ''; ?>">
                            <input type="submit" value="Register">
                        </form>
                    </div>
                    <?php

                        if ( is_wp_error( $user_registered ) ) {
                            $envato->display_message( $user_registered );
                        }
                }

            } else {

                ?>
                <div class="sss-form-wrapper">
                    <form method="post" id="verify-purchase-form" class="verify-purchase-form sss-form">
                        <p>
                            <label for=""><?php esc_html_e( 'Enter your item purchase code and verify.', 'simple-support-system' ) ?></label>
                            <input type="text" name="purchase_code"/>
                        </p>
                        <p>
                            <input type="submit" value="<?php esc_html_e( 'Verify', 'simple-support-system' ) ?>">
                        </p>
                    </form>

                </div>
                <?php

                    if ( is_wp_error( $purchase_verify ) ) {
                        $envato->display_message( $purchase_verify );
                    }
            }

        } else {

            echo '<h5>'. esc_html__( 'You are logged in already!', 'simple-support-system' ) .'</h5>';

        }

        return ob_get_clean();
    }

    /**
     * User registration form
     *
     * @since    1.0.0
     */
    public function sss_register_user_form() {

        ob_start();

        $user_registered = false;

        $envato = new SSS_Envato_API_Wrapper();

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

            $envato->display_message( esc_html__( 'Registration has been completed! Please login to get support.', 'simple-support-system' ) );

        } else {
            ?>
            <div class="sss-form-wrapper">
                <h2><?php esc_html_e( 'Register.', 'simple-support-system' ); ?></h2>
                <form method="post" id="register-user-form" class="register-user-form sss-form">
                    <p>
                        <label for="username"><?php esc_html_e( 'Username', 'simple-support-system' ); ?></label>
                        <input type="text" name="username" value="<?php echo ( isset( $_POST['username'] ) ) ? esc_attr( $_POST['username'] ) : ''; ?>"/>
                    </p>
                    <p>
                        <label for="email"><?php esc_html_e( 'Email', 'simple-support-system' ); ?></label>
                        <input type="email" name="email" value="<?php echo ( isset( $_POST['email'] ) ) ? esc_attr( $_POST['email'] ) : ''; ?>"/>
                    </p>
                    <p>
                        <label for="password"><?php esc_html_e( 'Password', 'simple-support-system' ); ?></label>
                        <input type="password" name="password"/><br>
                    </p>
                    <p>
                        <input type="submit" value="<?php esc_html_e( 'Register', 'simple-support-system' ) ?>">
                    </p>
                </form>
            </div>
            <?php

            if ( is_wp_error( $user_registered ) ) {
                $envato->display_message( $user_registered );
            }
        }

        return ob_get_clean();
    }

    /**
     * User login form
     *
     * @since    1.0.0
     */
    public function sss_login_user_form() {
        ob_start();
        ?>
        <div class="sss-form-wrapper">
            <div class="sss-form">
                <?php
                    if ( ! is_user_logged_in() ) { // Display WordPress login form:
                        $args = array(
                            'redirect' => esc_url( home_url( '/' ) ),
                            'form_id' => 'login-login-form'
                        );
                        wp_login_form( $args );
                        echo '<a class="sss-lostpassword" href="'. wp_lostpassword_url() .'">'. esc_html__( 'Forgot Password?', 'simple-support-system' ) . '</a>';
                    } else {
                        echo '<h5>'. esc_html__( 'You are logged in already!', 'simple-support-system' ) .'</h5>';
                    }
                ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * List user purchase codes and information
     *
     * @since    1.0.0
     */
    public function list_user_purchases() {

        $envato = new SSS_Envato_API_Wrapper();

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
            <div class="sss-form-wrapper">
                <form method="post" id="add-purchase" class="sss-form">
                    <p>
                        <?php esc_html_e( 'Enter your new item purchase code.', 'simple-support-system' ) ?>
                        <input type="text" name="purchase_code" value="<?php echo ( isset( $_POST['purchase_code'] ) ) ? $_POST['purchase_code'] : ''; ?>"/>
                    </p>
                    <input type="submit" value="<?php esc_html_e( 'Add', 'simple-support-system' ); ?>">

                </form>
            </div>
            <?php

            if ( is_wp_error( $add_purchase ) ) {
                $envato->display_message( $add_purchase->get_error_messages()[0], true );
            } else if ( $add_purchase ) {
                $envato->display_message( __( 'Your new item purchase code has been added.', 'simple-support-system' ) );
            }

        } else {
            echo '<p>'. esc_html__( 'Login required to view user Purchases.', 'simple-support-system' ) .'</p>';
        }
    }

    /**
     * Create ticket form
     *
     * @since    1.0.0
     */
    public function sss_create_ticket_form() {

        ob_start();

        $envato = new SSS_Envato_API_Wrapper();

        $current_user = wp_get_current_user();
        $user_id = $current_user->ID;

        $submit_ticket = $envato->submit_ticket();

        if( $submit_ticket == true && ! is_wp_error( $submit_ticket ) ) {
            ?>
            <div class="after-ticket-box">
                <?php

                echo '<p class="text-after-submit">'. esc_html__( 'Your Ticket Has Been Submitted Successfully!', 'simple-support-system') .'</p>';
                ?>
            </div>
            <?php

        } else {

            if ( is_user_logged_in() ) {
                ?>
                <div class="sss-form-wrapper">
                    <form method="post" id="submit-ticket" class="sss-form">
                        <?php

                        $codes = get_user_meta( $user_id, 'item_purchase_code' );

                        if( ! empty( $codes ) ) {
                            echo '<div class="sss-select-theme-wrap">';
                            echo '<h4>'. esc_html__( 'Select an item:', 'simple-support-system' ) .'</h4>';

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
                                        <label class="sss-select-theme">
                                            <?php
                                            echo '<input class="theme-select-radio" type="radio" name="theme" value="' . $purchase_info['item_name'] . '" ' . $supported . '><span class="' . $supported . '"">' . $purchase_info['item_name'] . '</span><br>';
                                            if( $supported == 'disabled' ) {
                                                echo '<span class="tooltiptext">'. esc_html__( 'This item support period has been expired.', 'simple-support-system' ) .'</span>';
                                            }
                                            ?>
                                        </label>
                                        <br>
                                        <?php
                                    }
                                }
                            }
                            echo '</div>';

                            ?>
                            <h4><?php esc_html_e( 'Ask your question:', 'simple-support-system' ); ?></h4>
                            <p>
                                <input type="text" name="title" value="<?php echo ( ! empty( $_POST['title'] )? $_POST['title'] : '' ) ?>" placeholder="Topic Title">
                            </p>
                            <p>
                                <textarea name="message" cols="30" rows="10"><?php echo ( ! empty( $_POST['message'] )? $_POST['message'] : '' ) ?></textarea>
                            </p>
                            <input type="submit" value="<?php esc_html_e( 'Submit', 'simple-support-system' ); ?>">
                            <?php
                        } else {
                            echo '<p class="non-pcode-user">'. esc_html__( 'Please enter an item purchase code on Your Purchases page, before you submit a ticket.', 'simple-support-system' ) . '</p>';
                        }

                        ?>
                    </form>
                </div>
                <?php

                if ( is_wp_error( $submit_ticket ) ) {
                    $envato->display_message( $submit_ticket );
                }

            } else {
                echo '<p>'. esc_html__( 'Login required to create a ticket.', 'simple-support-system' ) .'</p>';
            }
        }
        return ob_get_clean();
    }
}