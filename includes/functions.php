<?php
if ( ! defined( 'WPINC' ) ) {
    die;
}

if(!function_exists('ogulo_view')){

function ogulo_view($file,$attr_arr=array()){
    global $ogulo360;

        if(empty($file)){
        return false;
        }
        $args=[];
        
        if(!empty($attr_arr) && is_array($attr_arr)){
        $args = shortcode_atts( 
            $attr_arr,
            $attr_arr
        );
        }

        $extension='.php';
        $ogulo_dirpath = $ogulo360->dirpath.'view/';

        if(file_exists($ogulo_dirpath.$file.$extension)){
        include $ogulo_dirpath.$file.$extension;
        }
    }

}
