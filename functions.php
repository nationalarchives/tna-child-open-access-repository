<?php
/**
 * Created by PhpStorm.
 * User: pchotrani
 * Date: 09/12/15
 * Time: 11:38
 */




class Rational_Meta_Box {
    private $screens = array(
        'page',
    );
    private $fields = array(
        array(
            'id' => 'lead-author',
            'label' => 'Lead Author',
            'type' => 'text',
        ),
        array(
            'id' => 'other-authors',
            'label' => 'Other Authors',
            'type' => 'text',
        ),
        array(
            'id' => 'date-published',
            'label' => 'Date Published',
            'type' => 'date',
        ),
        array(
            'id' => 'published-by',
            'label' => 'Published by',
            'type' => 'text',
        ),
    );

    /**
     * Class construct method. Adds actions to their respective WordPress hooks.
     */
    public function __construct() {
        add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
        add_action( 'save_post', array( $this, 'save_post' ) );
    }

    /**
     * Hooks into WordPress' add_meta_boxes function.
     * Goes through screens (post types) and adds the meta box.
     */
    public function add_meta_boxes() {
        foreach ( $this->screens as $screen ) {
            add_meta_box(
                'authors-section',
                __( 'Authors Section', 'rational-metabox' ),
                array( $this, 'add_meta_box_callback' ),
                $screen,
                'advanced',
                'high'
            );
        }
    }

    /**
     * Generates the HTML for the meta box
     *
     * @param object $post WordPress post object
     */
    public function add_meta_box_callback( $post ) {
        wp_nonce_field( 'authors_section_data', 'authors_section_nonce' );
        echo 'Please enter the authors into the section below.';
        $this->generate_fields( $post );
    }

    /**
     * Generates the field's HTML for the meta box.
     */
    public function generate_fields( $post ) {
        $output = '';
        foreach ( $this->fields as $field ) {
            $label = '<label for="' . $field['id'] . '">' . $field['label'] . '</label>';
            $db_value = get_post_meta( $post->ID, 'authors_section_' . $field['id'], true );
            switch ( $field['type'] ) {
                default:
                    $input = sprintf(
                        '<input %s id="%s" name="%s" type="%s" value="%s">',
                        $field['type'] !== 'color' ? 'class="regular-text"' : '',
                        $field['id'],
                        $field['id'],
                        $field['type'],
                        $db_value
                    );
            }
            $output .= $this->row_format( $label, $input );
        }
        echo '<table class="form-table"><tbody>' . $output . '</tbody></table>';
    }

    /**
     * Generates the HTML for table rows.
     */
    public function row_format( $label, $input ) {
        return sprintf(
            '<tr><th scope="row">%s</th><td>%s</td></tr>',
            $label,
            $input
        );
    }
    /**
     * Hooks into WordPress' save_post function
     */
    public function save_post( $post_id ) {
        if ( ! isset( $_POST['authors_section_nonce'] ) )
            return $post_id;

        $nonce = $_POST['authors_section_nonce'];
        if ( !wp_verify_nonce( $nonce, 'authors_section_data' ) )
            return $post_id;

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
            return $post_id;

        foreach ( $this->fields as $field ) {
            if ( isset( $_POST[ $field['id'] ] ) ) {
                switch ( $field['type'] ) {
                    case 'email':
                        $_POST[ $field['id'] ] = sanitize_email( $_POST[ $field['id'] ] );
                        break;
                    case 'text':
                        $_POST[ $field['id'] ] = sanitize_text_field( $_POST[ $field['id'] ] );
                        break;
                }
                update_post_meta( $post_id, 'authors_section_' . $field['id'], $_POST[ $field['id'] ] );
            } else if ( $field['type'] === 'checkbox' ) {
                update_post_meta( $post_id, 'authors_section_' . $field['id'], '0' );
            }
        }
    }
}
new Rational_Meta_Box;




add_filter( 'admin_post_thumbnail_html', 'add_featured_image_instruction');
function add_featured_image_instruction( $content ) {
    $content .= '<p><i>Upload the PDF of your research</i></p>';

    return str_replace(__('Set featured image'), __('Upload PDF'),$content);
}
function upload_pdf() {
    // Remove the orginal "Set Featured Image" Metabox
    remove_meta_box('postimagediv', 'page', 'side');
    // Add it again with another title
    add_meta_box('postimagediv', __('PDF Section'), 'post_thumbnail_meta_box', 'page', 'normal', 'high');
}
add_action('do_meta_boxes', 'upload_pdf');



/*
 *
 * ================================================
 *              Custom Taxonomy
 * ================================================
 *
 */


function custom_taxonomy() {

    $labels = array(
        'name'              =>  'Keywords',
        'singular_name'     =>  'Keyword',
        'search_items'      =>  'Search Keywords',
        'all_itmes'         =>  'All Keywords',
        'edit_item'         =>  'Edit Keywords',
        'update_item'       =>  'Update Keyword',
        'add_new_item'      =>  'Add New Keyword',
        'new_item_name'     =>  'New Keyword Name',
        'menu_name'         =>  'Keyword'
    );
    $args = array(
        'hierarchical'      =>  false,
        'labels'            =>  $labels,
        'show_ui'           =>  true,
        'show_admin_column' =>  true,
        'query_var'         =>  true,
        'rewrite'           =>  array('slug'    =>  'keyword')
    );
    register_taxonomy('keyword', array('page'), $args);

}

add_action('init', 'custom_taxonomy');
