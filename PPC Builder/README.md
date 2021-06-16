PPc Builder - Wordpress Plugin
===================================
Developed for [Guaranteedppc](https://guaranteedppc.com/)
Wordpress custom plugin to create aricles automatically based on Imported Excel file.

Project Period
----------------------
- Start: 2017.2.28
- Finished: 2017.3.9

## Environment
- CMS: Wordpress

## Plugin Features
- Add/Import Excel file via File upload in Plugin Admin
- Choose Article templates and click "Start Build" button
- Save/Edit Article Template
- History save for Article creation, Excel imports

## Plugin Code
- Main File: personal-page-template.php
- Defined page template in wp_insert_post

page_template: If post_type is 'page', will attempt to set the page template. On failure, the function will return either a WP_Error or 0, and stop before the final actions are called. If the post_type is not ‘page’, the parameter is ignored. You can set the page template for a non-page by calling update_post_meta() with a key of '_wp_page_template'
````
add_action( 'admin_init', 'mytheme_admin_init' );
function mytheme_admin_init() {
    if ( ! get_option( 'mytheme_installed' ) ) {
        $new_page_id = wp_insert_post( array(
            'post_title'     => 'Blog',
            'post_type'      => 'page',
            'post_name'      => 'blog',
            'comment_status' => 'closed',
            'ping_status'    => 'closed',
            'post_content'   => '',
            'post_status'    => 'publish',
            'post_author'    => get_user_by( 'id', 1 )->user_id,
            'menu_order'     => 0,
            // Assign page template
            'page_template'  => 'template-blog.php'
        ) );

        if ( $new_page_id && ! is_wp_error( $new_page_id ) ){
            update_post_meta( $new_page_id, '_wp_page_template', 'template-blog.php' );
        }

        update_option( 'mytheme_installed', true );
    }
}
````
