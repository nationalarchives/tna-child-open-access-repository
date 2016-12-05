<?php
/* 
 * REGISTER SITE GLOBAL VARIABLES 
 */
function tnatheme_globals() {
    global $tnatheme;
    $tnatheme['ischildsite'] = 1;
    $tnatheme['childsitename'] = 'Research and scholarship';
    if (substr($_SERVER['REMOTE_ADDR'], 0, 3) === '10.') {
        $tnatheme['subsitepath'] = '';
        $tnatheme['subsitepatharr'] = array();
    } else {
        $tnatheme['subsitepath'] = '/about/our-role/research-and-scholarship';
        $tnatheme['subsitepatharr'] = array(
        	'About us' => '/about/',
        	'Our role' => '/about/our-role/'
        	);
    }
}
tnatheme_globals();


/*
 *
 * ================================================
 *              Custom Metabox
 * ================================================
 *
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
            'label' => 'Published By',
            'type' => 'text',
        ),
        array(
            'id' => 'pdf',
            'label' => 'PDF',
            'type' => 'media',
        ),
    );

    /**
     * Class construct method. Adds actions to their respective WordPress hooks.
     */
    public function __construct() {
        add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
        add_action( 'admin_footer', array( $this, 'admin_footer' ) );
        add_action( 'save_post', array( $this, 'save_post' ) );
    }

    /**
     * Hooks into WordPress' add_meta_boxes function.
     * Goes through screens (post types) and adds the meta box.
     */
    public function add_meta_boxes() {
        foreach ( $this->screens as $screen ) {
            add_meta_box(
                'authors-sections',
                __( 'Author Section', 'Author(s) Section' ),
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
     *
     */
    public function add_meta_box_callback( $post ) {
        wp_nonce_field( 'authors_section_data', 'authors_section_nonce' );
        echo 'Please update the the author(s) and upload research pdf.';
        $this->generate_fields( $post );
    }

    /**
     * Hooks into WordPress' admin function.
     * Added scripts for media uploader.
     */
    public function admin_footer() {
        ?><script>
            // https://codestag.com/how-to-use-wordpress-3-5-media-uploader-in-theme-options/
            jQuery(document).ready(function($){
                if ( typeof wp.media !== 'undefined' ) {
                    var _custom_media = true,
                        _orig_send_attachment = wp.media.editor.send.attachment;
                    $('.rational-metabox-media').click(function(e) {
                        var send_attachment_bkp = wp.media.editor.send.attachment;
                        var button = $(this);
                        var id = button.attr('id').replace('_button', '');
                        _custom_media = true;
                        wp.media.editor.send.attachment = function(props, attachment){
                            if ( _custom_media ) {
                                $("#"+id).val(attachment.url);
                            } else {
                                return _orig_send_attachment.apply( this, [props, attachment] );
                            };
                        }
                        wp.media.editor.open(button);
                        return false;
                    });
                    $('.add_media').on('click', function(){
                        _custom_media = false;
                    });
                }
            });
        </script><?php
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
                case 'media':
                    $input = sprintf(
                        '<input class="regular-text" id="%s" name="%s" type="text" value="%s"> <input class="button rational-metabox-media" id="%s_button" name="%s_button" type="button" value="Upload" />',
                        $field['id'],
                        $field['id'],
                        $db_value,
                        $field['id'],
                        $field['id']
                    );
                    break;
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
            '<tr><th>%s</th><td>%s</td></tr>',
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



/*
 *
 * ================================================
 *              Custom Taxonomy
 * ================================================
 *
 */


function custom_taxonomy() {

    $labels = array(
        'name' => 'Repository keywords',
        'singular_name' => 'Keyword',
        'search_items' => 'Search Keyword',
        'all_items' => 'All keywords',
        'parent_item' => 'Parent keyword',
        'parent_item_colon' => 'Parent keyword:',
        'edit_item' => 'Edit keyword',
        'update_item' => 'Update keyword',
        'add_new_item' => 'Add new keyword type',
        'new_item_name' => 'New keyword name',
        'menu_name' => 'Repository keywords'
    );

    $args = array(
        'hierarchical' => false,
        'labels' => $labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array( 'slug' => 'keyword' )
    );

    register_taxonomy('keywords', 'page', $args);

}

add_action('init', 'custom_taxonomy');




/*
 *
 * ================================================================================================
 *              Removing Metaboxes & Post Tags from Child Theme
 * ================================================================================================
 *
 */


// REMOVE POST META BOXES
if (!function_exists('remove_page_metaboxes')) {
    function remove_page_metaboxes() {
        remove_meta_box('commentstatusdiv', 'page', 'normal'); // Comments Metabox
        remove_meta_box('trackbacksdiv', 'page', 'normal'); // Talkback Metabox
        remove_meta_box('slugdiv', 'page', 'normal'); // Slug Metabox
        remove_meta_box('authordiv', 'page', 'normal'); // Author Metabox
        remove_meta_box('postimagediv', 'page', 'normal'); // Featured Image Metabox
        remove_meta_box('postcustom', 'page', 'normal');
        remove_meta_box('commentsdiv', 'page', 'normal');
    }
}
add_action('admin_menu','remove_page_metaboxes');



/*
 *
 * ================================================
 *             Get PDF file size
 * ================================================
 *
 */



// retrieves the attachment ID from the file URL
function get_pdf_id($pdf_url) {
    global $wpdb;
    $attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $pdf_url ));
    return $attachment[0];
}

//Size conversion
function formatSizeUnits($pdf_size) {
    if ($pdf_size >= 1048576) {
        $pdf_size = number_format($pdf_size / 1048576, 2) . ' MB';
    } elseif ($pdf_size >= 1024) {
        $pdf_size = number_format($pdf_size / 1024, 2) . ' KB';
    } elseif ($pdf_size > 1) {
        $pdf_size = $pdf_size . ' bytes';
    } elseif ($pdf_size == 1) {
        $pdf_size = $pdf_size . ' byte';
    } else {
        $pdf_size = '0 bytes';
    }
    return $pdf_size;
}


/*
 *
 * ================================================
 *             Menu Side Bar
 * ================================================
 *
 */

add_theme_support( 'menus' );


function register_theme_menus () {
    register_nav_menus(
        array(
            'sidebar-menu' => __( 'Sidebar menu' )
        )
    );
}
add_action ( 'init', 'register_theme_menus' );




/*
 *
 * ================================================
 *    Override unneeded functions from parent
 * ================================================
 *
 */


function education_resource_init () {
	// remove
}
function my_add_excerpts_to_pages () {
	// remove
}
function create_post_type () {
	// remove
}
function create_post_type2 () {
	// remove
}
function get_indicator () {
	// remove
}
function get_glossary () {
	// remove
}
function guidance_init () {
	// remove
}
function m_explode () {
	// remove
}
function create_events_cpt () {
	// remove
}
function include_template_function () {
	// remove
}
function flexslider_shortcode () {
	// remove
}
