<?php

class WCW_Menus_Rest {

    public static function init() {
        add_action( 'rest_api_init', array(__CLASS__, 'menus_rest' ));
    }

    /** this function adds an api endpoint returns a list of all rest url endpoints for the network */
    public static function menus_rest() {
        register_rest_route( 'wcw/v1', '/menus', array(      
            'methods'  => WP_REST_Server::READABLE,        
            'callback' => array(__CLASS__, 'get_menus_for_rest'),
            'permission_callback' => function() {
                return true;
            }
        ) ); 
    }  


    public static function get_menus_for_rest($request) {
        if(!$request['slug'] && !$request['id']) {
        
        $menus = get_terms( 'nav_menu', array( 'hide_empty' => true ) ); 
            return $menus;
        }

        if( isset($request['slug'])){
            $query = $request['slug'];
        }

        if( isset( $request['id'])) {
            $query = $request['id'];
        }

        return wp_get_nav_menu_items($query);

    }
}