<?php
/**
 * Envato API Wrapper
 *
 * An PHP class to interact with the Envato Marketplace API
 *
 * @author	    Inspiry Themes
 * @copyright	Copyright (c) 2016
 * @link		http://inspirythemes.com
 */

/**
 * Inspiry_Envato_API_Wrapper
 *
 * Create our new class called "Inspiry_Envato_API_Wrapper"
 */
class Inspiry_Envato_API_Wrapper {

    private $envato_token;
    private $mailbox;

    public function set_envato_token( $token ) {
        $this->envato_token = 'bZ65xvwvW6DzaHwUgIN1LpvCobQBQN27';
    }

    public function set_mailbox_address( $email ) {
        $this->mailbox = 'fahid@inspirythemes.com';
    }

    /**
     * verify_purchase()
     *
     * Verify purchase of given purchase code
     *
     * @access	public
     * @param	string bool
     * @return	array bool
     */
    public function verify_purchase( $code, $info = false ) {

        $error = new WP_Error();

        if( empty( $code ) ) {
            $error->add( 'error', 'Please enter a purchase code.' );
            return $error;
        }

        $envato_apiurl = "https://api.envato.com/v1/market/private/user/verify-purchase:" . $code . ".json";
        $envato_header = array();
        $envato_header['headers'] = array( "Authorization" => "Bearer " . $this->envato_token );
        $envato_purchases = wp_safe_remote_request( $envato_apiurl, $envato_header );

        if( is_string( $envato_purchases['body'] ) ) {
            $purchases_body = json_decode( $envato_purchases['body'], true );
            $body_array = (array) $purchases_body['verify-purchase']; // use json_decode

            if( isset( $body_array['item_id'] ) ) {

                if ( $info == true) {
                    return $body_array;
                }
                return true;

            } else {
                $error->add( 'error', 'Please enter a valid purchase code.' );
                return $error;
            }

        } else {
            $error->add( 'error', 'Problem in connecting.' );
            return $error;
        }
    }


    /**
     * item_info()
     *
     * fetch item information with given item code
     *
     * @access	public
     * @param	string
     * @return	array bool
     */
    public function item_info( $item_code ) {

        $envato_apiurl = "https://api.envato.com/v4/market/catalog/item?id=" . $item_code;
        $envato_header = array();
        $envato_header['headers'] = array( "Authorization" => "Bearer " . $this->envato_token );
        $item_info_obj = wp_safe_remote_request( $envato_apiurl, $envato_header );

        if( ! is_wp_error( $item_info_obj ) && is_string( $item_info_obj['body'] ) ) {
            $item_info = json_decode( $item_info_obj['body'], true );
            return $item_info;
        }
        return false;
    }

    /**
     * user_info()
     *
     * fetch user information with given username
     *
     * @access	public
     * @param	string
     * @return	array bool
     */
    public function user_info( $username ) {

        $envato_apiurl = "https://api.envato.com/v1/market/user:". $username .".json";
        $envato_header = array();
        $envato_header['headers'] = array( "Authorization" => "Bearer " . $this->envato_token );
        $user_info_obj = wp_safe_remote_request( $envato_apiurl, $envato_header );

        if( ! is_wp_error( $user_info_obj ) && is_string( $user_info_obj['body'] ) ) {
            $user_info = json_decode( $user_info_obj['body'], true );
            return $user_info['user'];
        }
        return false;
    }

    /**
     * register_user()
     *
     * Register user
     *
     * @access	public
     * @param	string array
     * @return	bool
     */
    public function register_user( $code, $userdata ){

        $error = new WP_Error();

        if( empty( $userdata['user_login'] ) ) {
            $error->add( 'error', __( 'Please enter a username.', 'inspiry' ) );
        }
        if( empty( $userdata['user_email'] ) || ! is_email( $userdata['user_email'] ) ) {
            $error->add( 'error', __( 'Please enter a valid email.', 'inspiry' ) );
        }
        if( empty( $userdata['user_pass'] ) ) {
            $error->add( 'error', __( 'Please enter a password.', 'inspiry' ) );
        }

        if ( empty( $error->get_error_messages() ) ) {

            $user_id = wp_insert_user($userdata);

            //On success
            if ( ! is_wp_error( $user_id ) ) {
                add_user_meta( $user_id, 'item_purchase_code', $code );
                return true;
            } else {
                return $user_id;
            }
        } else {
            return $error;
        }
    }

    /**
     * prettyPrint()
     *
     * Print array
     *
     * @access	public
     * @param	array
     * @return	print
     */
    public function prettyPrint($data)
    {
        echo "<pre>";
        print_r($data);
        echo "</pre>";
    }

    /**
     * item_purchase_code_exists()
     *
     * Check if item purchase code is already exists.
     *
     * @access	public
     * @param	string
     * @return	bool
     */
    public function item_purchase_code_exists( $item_purchase_code ) {

        global $wpdb;

        $results = $wpdb->get_results( "SELECT * FROM $wpdb->usermeta WHERE meta_key = 'item_purchase_code' AND meta_value = '".$item_purchase_code."'" );

        if( isset( $results[0]->meta_value ) && ( $results[0]->meta_value == $item_purchase_code ) ) {
            return new WP_Error( 'error', __( 'Purchase code already exists!', 'inspiry' ) );
        } else {
            return false;
        }
    }

    /**
     * display_message()
     *
     * Display given messages with relevant html tags.
     *
     * @access	public
     * @param	array/string, bool
     * @return	string
     */
    public function display_message( $message, $error = false ) {

        if ( is_wp_error( $message ) ) {
            echo '<ul class="error">';
            echo '<li>' . implode( '</li><li>', $message->get_error_messages() ) . '</li>';
            echo '</ul>';
        } else if( $error ) {
            echo '<p class="error">'. $message .'</p>';
        } else {
            echo '<p class="success">'. $message .'</p>';
        }
    }

    /**
     * add_purchase_code()
     *
     * Add purchase code to the user meta
     *
     * @access	public
     * @param	int $user_id user id
     * @param	string $item_purchase_code item purchase code
     * @return	bool true on success
     * @return	array WP_Error on error reporting
     */
    public function add_purchase_code( $user_id, $item_purchase_code ) {

        // Instantiate the WP_Error object
        $error = new WP_Error();

        if( empty( $item_purchase_code ) ) {
            $error->add( 'error', 'Please enter a purchase code.' );
            return $error;
        }

        $purchase_code_exist = $this->item_purchase_code_exists( $item_purchase_code );

        if( $purchase_code_exist ) {

            $error->add( 'error', __( 'Purchase Code Already Exists!', 'inspiry' ) );

        } else {

            $purchase = $this->verify_purchase( $item_purchase_code );


            if( is_wp_error( $purchase ) ) {
                return $purchase;
            } else {
                add_user_meta( $user_id, 'item_purchase_code', $item_purchase_code );
                return true;
            }
        }

        return $error;
    }

    /**
     * list_user_purchases()
     *
     * Displays user purchases based on purchase codes
     *
     * @access	public
     * @param	array $purchase_codes purchase codes
     * @return	void
     */
    public function list_user_purchases( $purchase_codes ) {

        if( ! empty( $purchase_codes ) && is_array( $purchase_codes )  ) {
            foreach ( $purchase_codes as $code ) {

                $purchase_info = $this->verify_purchase( $code, true );

                if( ! is_wp_error( $purchase_info ) ) {

                    $today = new DateTime( 'now' );
                    $support_until = new DateTime( $purchase_info['supported_until'] );
                    $support_until_date = date_format( $support_until ,'j F Y' );

                    if ( $support_until < $today ) {
                        $supported = 'expired';
                    } else {
                        $supported = 'valid';
                    }

                    ?>
                    <div class="purchase-detail-wrapper <?php echo $supported; ?>">
                        <div class="purchase-detail-inner">
                            <div class="detail-tag-container">
                                <span class="detail-tag">Purchase Code:</span>
                            </div>
                            <div class="detail-container">
                                <span class="purchase-detail"><?php echo $code; ?></span>
                            </div>
                        </div>
                        <div class="purchase-detail-inner">
                            <div class="detail-tag-container">
                                <span class="detail-tag">Product:</span>
                            </div>
                            <div class="detail-container">
                                <span class="purchase-detail"><?php echo $purchase_info['item_name']; ?></span>
                            </div>
                        </div>
                        <div class="purchase-detail-inner">
                            <div class="detail-tag-container">
                                <span class="detail-tag">Supported Until:</span>
                            </div>
                            <div class="detail-container">
                                <span class="purchase-detail"><?php echo $support_until_date; ?></span>
                            </div>
                        </div>
                        <span class="valid-tag"><?php echo $supported; ?> for support</span>
                    </div>
                    <?php
                }
            }
        }
    }

    /**
     * submit_ticket()
     *
     * Submits ticket to Help Scout
     *
     * @access	public
     * @param	void
     * @return	bool
     */
    public function submit_ticket() {

        $error = new WP_Error();

        if( isset( $_POST['title'] ) ) {

            if ( empty($_POST['theme'] ) ) {
                $error->add( 'error', __('No theme is selected to ask question about!', 'inspiry' ) );
            }

            if ( empty( $_POST['title'] ) ) {
                $error->add( 'error', __('You must enter a title for your question.', 'inspiry' ) );
            }

            if ( empty( $_POST['message'] ) ) {
                $error->add( 'error', __('Provide your question details.', 'inspiry' ) );
            }

            if ( empty( $error->get_error_messages() ) ) {

                $current_user = wp_get_current_user();

                $to_email = $this->mailbox;
                $to_email = is_email($to_email);
                if ( ! $to_email ) {
                    $error->add( 'error', __('Target Email address is not properly configured!', 'inspiry' ) );
                    return $error;
                }

                $from_name = sanitize_text_field( $current_user->user_login );
                $title = sanitize_text_field( $_POST['title'] );
                $theme = sanitize_text_field( $_POST['theme'] );
                $message = stripslashes( $_POST['message'] );
                $from_email = sanitize_email( $current_user->user_email );

                /*
                 * Email Subject
                 */
                $email_subject = 'New ticket submitted by' . ' ' . $from_name . ' ' . 'using support form at' . ' ' . get_bloginfo( 'name' );

                /*
                 * Email Body
                 */
                $email_body = "You have received a ticket from: " . $from_name . " <br/><br/>";
                if ( ! empty( $title ) ) {
                    $email_body .= "Title : " . $title . " <br/><br/>";
                }
                if ( ! empty( $theme ) ) {
                    $email_body .= "Theme : " . $theme . " <br/><br/>";
                }
                $email_body .= "Their question detail is as follows:" . " <br/>";
                $email_body .= wpautop( $message ) . " <br/>";

                /*
                 * Email Headers ( Reply To and Content Type )
                 */
                $headers = array();

                $headers[] = "Reply-To: $from_name <$from_email>";
                $headers[] = "Content-Type: text/html; charset=UTF-8";

                if ( wp_mail( $to_email, $email_subject, $email_body, $headers ) ) {
                    return true;
                } else {
                    $error->add( 'error', __('Server Error: WordPress mail function failed!', 'inspiry' ) );
                    return $error;

                }

            } else {
                return $error;
            }

        }

    }
}