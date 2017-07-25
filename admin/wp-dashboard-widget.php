<?php
/**
 * WordPress Dashboard Widget
 *
 * A PHP class for the Dashboard widget.
 *
 * @author	    Fahid Javid
 * @copyright	Copyright (c) 2017
 * @link		https://fahidjavid.com
 */

/**
 * SSS_WP_Dashboard_Widget
 *
 * Create our new class called "SSS_WP_Dashboard_Widget"
 */
class SSS_WP_Dashboard_Widget {

    public function product_versions( $post, $callback_args ) {
        ?>
        <div id="published-posts" class="activity-block">
            <h3>Recently Published</h3>
            <ul>
                <li><span>Jul 15th 2016, 1:46 pm</span> <a href="http://plugins.dev/wp-admin/post.php?post=926&amp;action=edit" aria-label="Edit “A nice post”">A nice post</a></li>
                <li><span>Feb 11th 2016, 9:32 am</span> <a href="http://plugins.dev/wp-admin/post.php?post=1&amp;action=edit" aria-label="Edit “Hello world!”">Hello world!</a></li>
                <li><span>Jul 13th 2015, 7:57 pm</span> <a href="http://plugins.dev/wp-admin/post.php?post=24&amp;action=edit" aria-label="Edit “Gallery Post Format”">Gallery Post Format</a></li>
                <li><span>Jul 13th 2015, 7:52 pm</span> <a href="http://plugins.dev/wp-admin/post.php?post=21&amp;action=edit" aria-label="Edit “Image Post Format”">Image Post Format</a></li>
                <li><span>Jul 13th 2015, 7:47 pm</span> <a href="http://plugins.dev/wp-admin/post.php?post=18&amp;action=edit" aria-label="Edit “Video Post Format”">Video Post Format</a></li>
            </ul>
        </div>
        <?php
    }

    public function add_dashboard_widgets() {
        wp_add_dashboard_widget('dashboard_widget', 'Simple Support System ( Product Versions )', array( $this, 'product_versions' ) );
    }
}