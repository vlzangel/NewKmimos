<?php

    $WP_path_load = dirname(dirname(dirname(dirname(__DIR__)))).'/wp-load.php';

    include_once($WP_path_load);

    //CLASS
    include_once(dirname(__FILE__).'/includes/class/class_whitelabel.php');
    include_once(dirname(__FILE__).'/includes/class/class_whitelabel-user.php');
    $_wlabel = new Class_WhiteLabel();
    $_wlabel_user = new Class_WhiteLabel_User();

    //CREATE TEMPLATE
    add_filter('page_template', 'WhiteLabel_Template');
    function WhiteLabel_Template($template){
        if(is_page('wlabel')){
            $dirtemplate = dirname(__FILE__).'/index.php';
            if(file_exists($dirtemplate)){
                $template = $dirtemplate;
            }
        }
        return $template;
    }

    //CREATE PAGE
    add_action('init', 'WhiteLabel_Page');
    function WhiteLabel_Page(){
        global $user_ID;
        $post_name = 'wlabel';
        $post_title = 'Label';
        $post_content = '';
        $page = get_page_by_path($post_name);
        $post = array(
            'post_author' => $user_ID, 
            'post_content' => $post_content, 
            'post_name' => $post_name, 
            'post_status' => 'publish', 
            'post_title' => $post_title, 
            'post_type' => 'page', 
            'post_parent' => 0, 
            'menu_order' => 0, 
            'to_ping' =>  '', 
            'pinged' => ''
        );
        if($page->post_name!=$post_name){
            $insert = wp_insert_post($post);
            if(!$insert){
                wp_die('Error creando Post');
            }
        }
        return;
    }

    //RULES HTACCESS
    add_action('generate_rewrite_rules', 'WhiteLabel_add_rewrite_rules');
    function WhiteLabel_add_rewrite_rules($wp_rewrite){
        $new_rules = array(
            'label/?$' => 'index.php?pagename=wlabel',
            'label/([a-z]*)/?$' =>'index.php?pagename=wlabel&wlabel='.$wp_rewrite->preg_index(1)
        );
        $wp_rewrite->rules = $new_rules+$wp_rewrite->rules;
    }

    add_filter('query_vars', 'WhiteLabel_query_vars');
    function WhiteLabel_query_vars($query_vars){
        return $query_vars;
    }

    add_action('init', 'WhiteLabel_flush_rewrite_rules');
    function WhiteLabel_flush_rewrite_rules(){
        global $wp_rewrite;
        $wp_rewrite->flush_rules();
    }

?>