<?php
/*
Plugin Name: DWS - Testimonials
Plugin URI: http://dynamic-websolutions.com/plugins/dws-testimonials/
Description: Easily implement Client / Customers Testimonials with images on your Wordpress website / blog with shortcode "[dwstestimonial]". 
Version: 3.2.1
Author: Saad Siddique (Dynamic Web Solutions)
Author URI: http://dynamic-websolutions.com/ 
Copyright 2013 Saad Siddique (Dynamic Web Solutions)  (email : jigzyy@gmail.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/* Adding Custom Post Type Creator Script */
require_once 'class_dws_custom_posts.php'; 

//The name the menu of Wordpress Admin..
$pluginVersion = '3.2.1';
$pluginName = 'Testimonials';
$pluginShortName = str_replace(' ','',strtolower($pluginName));
$pluginSlugName = str_replace(' ','-',strtolower($pluginName));

//Creating New Post Type.
$dwsTestimonials = new _DWS_Post_Type($pluginName, 'Testimonial', 'Testimonials', plugins_url( 'images/testimonial.png' , __FILE__ ), 'testimonials', 'page', 31, array('title','editor','thumbnail'));
$dwsTestimonials->add_meta_box('Customer Details', array(
      'Full Name'   => array('name' => 'Full Name', 'type' => 'text', 'desc' => '' ) ,
      'City'        => array('name' => 'City', 'type' => 'text', 'desc' => '' ) ,
      'State/Province' => array('name' => 'State/Province', 'type' => 'text', 'desc' => '' ) ,
      'Country'     => array('name' => 'Country', 'type' => 'text', 'desc' => '' ) ,
      'Company'     => array('name' => 'Company', 'type' => 'text', 'desc' => '' ) ,
      'Website'     => array('name' => 'Website', 'type' => 'text', 'desc' => '' ) ,
      'E-mail'      => array('name' => 'E-mail', 'type' => 'text', 'desc' => '' ) ,
      'Age'         => array('name' => 'Age', 'type' => 'text', 'desc' => '' ) ,
      'Position'    => array('name' => 'Position', 'type' => 'text', 'desc' => '' ) ,
));

if ( function_exists( 'add_theme_support' ) ) {
    add_image_size('dws-testimonial-thumb', 96 , 96, true);
}

// Posts & pages columns filter
add_filter('manage_dws-testimonial_posts_columns', 'dwstestimonial_wp_add_post_thumbnail_column', 10);
add_filter('manage_dws-testimonial_pages_columns', 'dwstestimonial_wp_add_post_thumbnail_column', 10);

// Add featured image colum (we'll call it 'Featured')
function dwstestimonial_wp_add_post_thumbnail_column($cols){
  $cols['wp_post_thumb'] = __('Image');
  return $cols;
}

add_action('manage_dws-testimonial_posts_custom_column', 'dwstestimonial_wp_display_post_thumbnail_column', 10, 2);
add_action('manage_dws-testimonial_pages_custom_column', 'dwstestimonial_wp_display_post_thumbnail_column', 10, 2);
                                                             
// Get scaled featured-thumbnail & display it.
function dwstestimonial_wp_display_post_thumbnail_column($col, $id){
  switch($col){
    case 'wp_post_thumb':
      if( function_exists('the_post_thumbnail') )
        echo the_post_thumbnail( 'dws-testimonial-thumb' );
      else
        echo 'Not supported in theme';
      break;
  }
}

function create_testimonial_page(){
    global $user_ID;
    
    $page = get_page_by_title('Testimonials');
    if (!empty($page)) {
        // page exists and is in $page
    } else {
        $data = array(
                    'post_content'  => '[dwstestimonial]'
                ,   'post_title'    => 'Testimonials'
                ,   'post_status'   => 'publish'
                ,   'post_author'   => $user_ID
                ,   'post_type'     => 'page'
        );
        wp_insert_post($data);
    }
}
function get_page_by_name($pagename){
    $pages = get_pages();
    foreach ($pages as $page) if ($page->post_name == $pagename) return $page;
    return false;
}

/* Creating Shortcode and extracting everything */
add_shortcode('dwstestimonial', 'dwstestimonial_func');         
function dwstestimonial_func($atts) {
     extract(shortcode_atts(array(
          'excerpt'     => 'yes',
          'name'        => 'yes',
          'pic'         => 'yes',
          'email'       => 'yes',
          'position'    => 'yes',
          'website'     => 'yes',
          'company'     => 'yes',
          'country'     => 'yes',
          'city'        => 'yes',
          'state'       => 'yes',
          'limit'       => -1,
          'orderby'     => 'date',
          'order'       => 'desc',
     ), $atts));
     
     $args = array(
        'post_type'         => 'testimonials',
        'orderby'           => $orderby,
        'order'             => $order,
        'post_status'       => 'publish',
        'posts_per_page'    => $limit,
        //'nopaging'          => true,
     );  
         
   query_posts($args);
   if(have_posts()):
    if($excerpt == "yes"):
        global $more;
        $more = 0;
    endif;
        while(have_posts()): the_post();
            include('loop-testimonials.php');
        endwhile;        
   else:
        return 'We are adding testimonials. Please check back later.';
   endif;
   wp_reset_query();
}

function register_dwstestimonials_default_styles(){
     wp_register_style( 'dws-testimonials', plugins_url( 'testimonial.css' , __FILE__ ),'',$pluginVersion);
     wp_enqueue_style( 'dws-testimonials' );
}
add_action( 'wp_enqueue_scripts', 'register_dwstestimonials_default_styles' );
register_activation_hook( __FILE__, 'create_testimonial_page' );

?>