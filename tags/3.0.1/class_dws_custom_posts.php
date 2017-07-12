<?php
session_start();
/**
 * DWS Custom Post Types Generator
 * @author Saad Siddique
 * @link http://dynamic-websolutions.com
 * @version 2.0
 * All Credit goes to original creator of the script Jeffrey Way @ http://jeffrey-way.com for his brilliant idea
 * I've adapted his script and modified it to my own requirements.
 */
class __DWS_Post_Type
{

    /**
     * The Singular name of the post type.
     * @var string
     */
    public $post_type_name;

    /**
     * The Plural name of the post type.
     * @var string
     */
    public $post_type_name_plural;   
    
     /**
     * The Singular name of the post type.
     * @var string
     */
    public $post_type_name_singular;

    /**
     * The Link to Menu Icon.
     * @var string
     */
    public $post_type_icon;

    /**
     * The Slug of the Post Type.
     * @var string
     */
    public $post_type_slug;

    /**
     * The Capability of the Post Type (Post / Page).
     * @var string
     */
    public $post_type_capability;

    /**
     * The Position the Post Type (Post / Page).
     * @var string
     */
    public $post_type_position;

    /**
     * A list of user-specific options for the post type.
     * @var array
     */
    public $post_type_args;


    /**
     * Sets default values, registers the passed post type, and
     * listens for when the post is saved.
     *
     * @param string $name The name of the desired post type.
     * @param array @post_type_args Override the options.
     */
    function __construct($name, $singular, $plural = null, $icon = '', $slug = null, $capability = 'post', $positon = 31, $post_type_args = array()){
        if (!isset($_SESSION["taxonomy_data"])) {
            $_SESSION['taxonomy_data'] = array();
        }
           
        $this->post_type_name = clear_name($name);
        $this->post_type_name_singular = $singular;
        $this->post_type_name_plural = $plural;
        $this->post_type_icon = $icon;
        $this->post_type_slug = $slug;
        $this->post_type_capability = $capability;
        $this->post_type_position = $positon;
        $this->post_type_args = (array)$post_type_args;

        // First step, register that new post type
        $this->init(array($this, "register_post_type"));
        $this->save_post();
    }

    /**
     * Helper method, that attaches a passed function to the WP filter
     * @param function $fn is the filter name, $fcb is the function callback function.
     */
    function filter($fn,$fcb){
        add_filter($fn, $fcb);
    }

    /**
     * Helper method, that attaches a passed function to the 'init' WP action
     * @param function $cb Passed callback function.
     */
    function init($cb){
        add_action("init", $cb);
    }

    /**
     * Helper method, that attaches a passed function to the 'admin_init' WP action
     * @param function $cb Passed callback function.
     */
    function admin_init($cb){
        add_action("admin_init", $cb);
    }


    /**
     * Registers a new post type in the WP db.
     */
    function register_post_type(){
        $n = ucwords(str_replace('-',' ',$this->post_type_name));
        $p = ucwords($this->post_type_name_plural);
        $s = ucwords($this->post_type_name_singular);
         
         $args =   array('labels' => array(
                        'name'          => $n,
                        'singular_name' => $n,
                        'add_new'       => __( 'Add New '.$s ),
                        'add_new_item'  => __( 'Add New '.$s ),
                        'edit'          => __( 'Edit' ),
                        'edit_item'     => __( 'Edit '.$s ),
                        'new_item'      => __( 'New  '.$s),
                        'view'          => __( 'View  '.$p),
                        'view_item'     => __( 'View  '.$s),
                        'search_items'  => __( 'Search  '.$p),
                        'not_found'     => __( 'No '.$p.' found' ),
                        'not_found_in_trash' => __( 'No '.$p.' found in trash' ),
                        'parent'        => __( 'Parent  '.$s),
                        ),
                        'menu_icon'     => $this->post_type_icon,
                        'description'   => __( 'This is where you can create new '.$p.' on your site.' ),
                        'public'        => true,
                        'show_ui'       => true,
                        'capability_type'   => $this->post_type_capability,
                        'publicly_queryable'=> true,
                        'exclude_from_search'=> false,
                        'menu_position' => $this->post_type_position,
                        'hierarchical'  => false,
                        'rewrite'       => array( 'slug' => $this->post_type_slug, 'with_front' => false ), /* Slug set so that permalinks work when just showing post name */
                        'query_var'     => true,
                        'supports'      => $this->post_type_args,
                );
        register_post_type($this->post_type_name, $args);
        flush_rewrite_rules();
    }


    /**
     * Registers a new taxonomy, associated with the instantiated post type.
     *
     * @param string $taxonomy_name The name of the desired taxonomy
     * @param string $plural The plural form of the taxonomy name. (Optional)
     * @param array $options A list of overrides
     */
    function add_taxonomy($taxonomy_name, $plural = '', $slug , $options = array()){
        // Create local reference so we can pass it to the init cb.
        $post_type_name = $this->post_type_name;

        // If no plural form of the taxonomy was provided, do a crappy fix. :)

        if (empty($plural)) {
            $plural = $taxonomy_name . 's';
        }

        // Taxonomies need to be lowercase, but displaying them will look better this way...
        $taxonomy_name = ucwords($taxonomy_name);

        //echo "$taxonomy_name - $post_type_name";
        
        // At WordPress' init, register the taxonomy
        $this->init(
            function() use($taxonomy_name, $plural, $slug, $post_type_name, $options){
                // Override defaults with user provided options

                register_taxonomy( clear_name($taxonomy_name) ,
                                array( clear_name($post_type_name) ),
                                array('hierarchical' => true,
                                        'labels' => array(
                                                'name' => __( $taxonomy_name ),
                                                'singular_name' => __( $taxonomy_name ),
                                                'search_items' =>  __( 'Search '.$taxonomy_name ),
                                                'all_items' => __( 'All '.$taxonomy_name ),
                                                'parent_item' => __( 'Parent '.$taxonomy_name ),
                                                'parent_item_colon' => __( 'Parent '.$taxonomy_name.':' ),
                                                'edit_item' => __( 'Edit '.$taxonomy_name ),
                                                'update_item' => __( 'Update '.$taxonomy_name ),
                                                'add_new_item' => __( 'Add New '.$taxonomy_name ),
                                                'new_item_name' => __( 'New '.$taxonomy_name.' Name' )
                                        ),
                                        'show_ui'   => true,
                                        'query_var' => true,
                                        'update_count_callback' => '_update_post_term_count',
                                        'rewrite' => array( 'slug' => $slug, 'hierarchical' => true ),
                                )
                        );       
                        flush_rewrite_rules();         
            });
    }


    /**
     * Creates a new custom meta box in the New 'post_type' page.
     *
     * @param string $title
     * @param array $form_fields Associated array that contains the label of the input, and the desired input type. 'Title' => 'text'
     */
    function add_meta_box($title, $form_fields = array()){
        $post_type_name = $this->post_type_name;

        // end update_edit_form
        add_action('post_edit_form_tag', function(){
            echo ' enctype="multipart/form-data"';
        });


        // At WordPress' admin_init action, add any applicable metaboxes.
        $this->admin_init(function() use($title, $form_fields, $post_type_name){
                add_meta_box(
                    strtolower(str_replace(' ', '_', $title)), // id
                    $title, // title
                    function($post, $data){ // function that displays the form fields
                        global $post;

                        wp_nonce_field(plugin_basename(__FILE__), 'dws_nonce');

                        // List of all the specified form fields
                        $inputs = $data['args'][0];
                        //echo "<pre>"; print_r($inputs); echo "</pre>";
                        // Get the saved field values
                        $meta = get_post_custom($post->ID);

                        // For each form field specified, we need to create the necessary markup
                        // $name = Label, $type = the type of input to create
                        //foreach ($inputs as $name => $type) {
                        foreach ($inputs as $input) {
                            //echo "<pre>"; print_r($name); echo "</pre>";
                            $name = $input['name'];
                            $type = $input['type'];
                            $desc = $input['desc'];
                            #'Happiness Info' in 'Snippet Info' box becomes
                            # snippet_info_happiness_level
                            $id_name = $data['id'] . '_' . strtolower(str_replace(' ', '_', $name));

                            if (is_array($inputs[$name])) {
                                // then it must be a select or file upload
                                // $inputs[$name][0] = type of input

                                if (strtolower($input['type']) === 'select') {
                                    // filter through them, and create options
                                    if($input['specific'] == "cart66_product_list"){
                                        $input['values'] = cart66_product_list();
                                    }                                    
                                    
                                    $select = "<select name='$id_name'";
                                    
                                    $select.=">";                                   
                                    foreach ($input['values'] as $option) {                        
                                        
                                        if($input['specific'] == "cart66_product_list"){
                                            if (isset($meta[$id_name]) && $meta[$id_name][0] == $option['id']) {
                                                $set_selected = "selected='selected'";
                                            } 
                                            else {                                                               
                                                $set_selected = '';
                                            }
                                        }elseif($input['specific'] == "rating"){                                            
                                            if (isset($meta[$id_name]) && floatval($meta[$id_name][0]) == floatval($option)) {
                                                $set_selected = "selected='selected'";
                                            } 
                                            else {                                                               
                                                $set_selected = '';
                                            }
                                        }else{
                                            if (isset($meta[$id_name]) && $meta[$id_name][0] == $option) {
                                                $set_selected = "selected='selected'";
                                            } 
                                            else {                                                               
                                                $set_selected = '';
                                            }
                                        }
                                        if($input['specific'] == "cart66_product_list"){
                                            $val = $option['id'] ;
                                            $item_name = $option['name']. " ( ". $option['item_number']." - \$". $option['price']." )";
                                            $select .= "<option value='$val' $set_selected> $item_name </option>";
                                        }elseif($input['specific'] == "product_list"){
                                            $val = $option['id'] ;
                                            $item_name = $option['name'];
                                            $select .= "<option value='$val' $set_selected> $item_name </option>";
                                        }else{
                                            $select .= "<option value='$option' $set_selected> $option </option>";
                                        }
                                    }
                                    $select .= "</select>";
                                    array_push($_SESSION['taxonomy_data'], $id_name);
                                }elseif (strtolower($input['type']) === 'checkbox_group') {                                    
                                    if(strlen(trim($meta[$id_name][0]))):
                                        $metas = unserialize($meta[$id_name][0]);
                                        //echo "<pre>"; print_r($metas); echo "</pre>";
                                    endif;
                                    $checkbox_group='<div style="width:50%; height:100px; overflow:auto;">';
                                    $input['values'] = product_list();
                                    foreach($input['values'] as $val){
                                        if(is_array($metas) && in_array($val['id'],$metas)){
                                            $checked = ' checked="checked"';
                                        }else{
                                            $checked = "";
                                        }

                                        $checkbox_group .= '<input type="checkbox" name="'.$id_name.'[]" '.$checked.' value="'.$val['id'].'"> '.$val['name'].' <a href="'.get_permalink($val['id']).'" target="_blank">View</a> <br />'."\n";
                                    }
                                    $checkbox_group.="</div>";
                            }
                                
                                
                            }
                            
                            // Attempt to set the value of the input, based on what's saved in the db.
                            $value = isset($meta[$id_name][0]) ? $meta[$id_name][0] : '';

                            $checked = ($type == 'checkbox' && !empty($value) ? 'checked' : '');

                            // Sorta sloppy. I need a way to access all these form fields later on.
                            // I had trouble finding an easy way to pass these values around, so I'm
                            // storing it in a session. Fix eventually.
                            array_push($_SESSION['taxonomy_data'], $id_name);

                            // TODO - Add the other input types.
                            $lookup = array(
                                "hidden" => "<input type='hidden' name='$id_name' value='$value' />",
                                "text" => "<input type='text' name='$id_name' value='$value' class='widefat' />",
                                "text small" => "<input type='text' name='$id_name' value='$value' />",
                                "money" => "\$<input type='text' name='$id_name' value='$value' />",
                                "textarea" => "<textarea name='$id_name' class='ckeditor' rows='10'>$value</textarea>",
                                "checkbox" => "<input type='checkbox' name='$id_name' value='$name' $checked />",
                                "select" => isset($select) ? $select : '',
                                "group" => isset($features) ? $features : '',
                                "checkbox_group" => isset($checkbox_group) ? $checkbox_group : '',
                                "file" => "<input type='file' name='$id_name' id='$id_name' />",
                            );
                            ?>

                            <p><label style="font-weight: bold; font-size:12px; text-transform: uppercase"><?php echo ucwords($name) . ':'; ?></label><?php if($type <> "checkbox"): ?><br /><?php endif; ?>
                                <?php echo $lookup[is_array($type) ? $type[0] : $type]; ?><br />
                                <?php if(strlen(trim($desc))): ?><span style="color: gray; font-size:11px;"><?php echo $desc ?></span><?php endif; ?>
                            </p>
                           
                            <p>

                                <?php
                                    // If a file was uploaded, display it below the input.
                                    $file = get_post_meta($post->ID, $id_name, true);
                                    if ( $type === 'file' ) {
                                        // display the image
                                        $file = get_post_meta($post->ID, $id_name, true);

                                        $file_type = wp_check_filetype($file);
                                        $image_types = array('jpeg', 'jpg', 'bmp', 'gif', 'png');
                                        if ( isset($file) ) {
                                            if ( in_array($file_type['ext'], $image_types) ) {
                                                echo "<img src='$file' alt='' style='max-width: 200px;' />";
                                            } else {
                                                echo "<a href='$file'>$file</a>";
                                            }
                                        }
                                    }
                                ?>
                            </p>

                            <?php

                        }
                        /*echo "<pre>"; print_r($_SESSION['taxonomy_data']); echo "</pre>";*/
                    },
                    $post_type_name, // associated post type
                    'normal', // location/context. normal, side, etc.
                    'default', // priority level
                    array($form_fields) // optional passed arguments.
                ); // end add_meta_box
            });
    }


    /**
     * When a post saved/updated in the database, this methods updates the meta box params in the db as well.
     */
    function save_post(){
        add_action('save_post', function()
            {
                // Only do the following if we physically submit the form,
                // and now when autosave occurs.
                if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

                global $post;

                if ($_POST && !wp_verify_nonce($_POST['dws_nonce'], plugin_basename(__FILE__))) {
                    return;
                }
                
                //echo "<pre>"; print_r($_POST); echo "</pre>";
                //break;

                // Get all the form fields that were saved in the session,
                // and update their values in the db.
                
                if (isset($_SESSION['taxonomy_data'])) {
                    foreach ($_SESSION['taxonomy_data'] as $form_name) {
                        if (!empty($_FILES[$form_name]) ) {
                            if ( !empty($_FILES[$form_name]['tmp_name']) ) {
                                $upload = wp_upload_bits($_FILES[$form_name]['name'], null, file_get_contents($_FILES[$form_name]['tmp_name']));

                                if (isset($upload['error']) && $upload['error'] != 0) {
                                    wp_die('There was an error uploading your file. The error is: ' . $upload['error']);
                                } else {
                                    update_post_meta($post->ID, $form_name, $upload['url']);
                                }
                            }
                       } else {
                            // Make better. Have to do this, because I can't figure
                            // out a better way to deal with checkboxes. If deselected,
                            // they won't be represented here, but I still need to
                            // update the value to false to blank in the table. Hmm...                       
                            if (!isset($_POST[$form_name])) $_POST[$form_name] = '';
                            if (isset($post->ID) ) {
                                update_post_meta($post->ID, $form_name, $_POST[$form_name]);
                            }
                        }
                    }

                    $_SESSION['taxonomy_data'] = array();

                }

            });
    }
    
}
function clear_name($str){
    return preg_replace('/[^\w]+/','-',strtolower($str));
}

/*********/
/* USAGE */
/*********/

// $product = new PostType("movie");
// $product->add_taxonomy('Actor');
// $product->add_taxonomy('Director');
// $product->add_meta_box('Movie Info', array(
//     'name' => 'text',
//     'rating' => 'text',
//     'review' => 'textarea',
// 'Profile Image' => 'file'

// ));



