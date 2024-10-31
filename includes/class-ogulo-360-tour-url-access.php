<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.ogulo.de/
 * @since      1.0.0
 *
 * @package    Ogulo_360_Tour
 * @subpackage Ogulo_360_Tour/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Ogulo_360_Tour
 * @subpackage Ogulo_360_Tour/URL Access
 * @author     Rextheme <support@rextheme.com>
 */


class Ogulo_360_Tour_URL_Access
{
    
    function __construct()
    {
        add_filter( 'query_vars', [$this,'registerQueryQrgs'], 0, 1 );
        add_action( 'init', [$this,'registerTourURL'], 10 );
        add_action( 'wp', [$this,'handleRequest'], 10 );
        add_action( 'wp', [$this,'handleRedirection'], 10 );
    }

    function registerTourURL(){
        $mask = 'ogulo_access';
        $slug = 'tour';
         add_rewrite_rule(
            $slug . '/([A-Za-z0-9-]+)[/]?$',
            'index.php?tour=$matches[1]',
            'top' );

         add_rewrite_rule(
            $mask . '/([A-Za-z0-9-]+)[/]?$',
            'index.php?ogulo_access=$matches[1]',
            'top' );
    }

    function registerQueryQrgs($query_vars){
        $query_vars[] = 'ogulo_access';
        $query_vars[] = 'tour';
        return $query_vars;
    }

    function handleRedirection(){
        $red_code = sanitize_text_field( stripslashes_deep( get_query_var( 'ogulo_access' ) ) ) ; 

        if ( $red_code == false || $red_code == '' ) {
            return;
        }
        
        $url = get_transient( 'ogulo_access_redirection_'.$red_code );
        
        if($url){
            //delete_transient( 'ogulo_access_redirection_'.$red_code );
            wp_redirect($url); exit;	
        }
        
    }

    function handleRequest(){
        global $wp_query;
        $tour_shortcode = sanitize_text_field( stripslashes_deep( get_query_var( 'tour' ) ) ) ; 
        
        if ( $tour_shortcode == false || $tour_shortcode == '' ) {
            return;
        }
        
        $atts=[
            'slug' => $tour_shortcode,
            'height' => '100%',
            'width' => '100%',
        ];
        $obj = new Ogulo_360_Tour_Shortcode();
        $tour = $obj->ogulo_tour_load_tour($atts);
        if($tour){
            echo $tour;
        }else{
             
            $wp_query->set_404();
            status_header( 404 );
            get_template_part( 404 ); 
            
        }
        exit;
    }
}
?>