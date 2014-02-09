<?php 
/**
 * @package Nicescroll for Wordpress
 * @author InuYaksa
 * @version 2.0.0
 */
/*
Plugin Name: WP-Nicescroll
Plugin URI: http://www.areaaperta.com/nicescroll/
Description: Modern and sexy scrollbar for desktop and mobile browsers
Author: InuYaksa
Version: 2.0.0
Author URI: http://www.areaaperta.com/nicescroll/
*/
 
function addNiceScroll(){

    $opts = array();    
    $oo = get_option("_jnc_opts");
    if ($oo!='') $oo = unserialize($oo);
    if (is_array($oo)) $opts = $oo;    
    
    $dom = (!empty($opts['maindom'])) ? $opts['maindom'] : "html";
    
    $str = array();
    if (!empty($opts['color'])) $str['cursorcolor'] = $opts['color'];
    if (!empty($opts['size'])) $str['cursorwidth'] = $opts['size'];
    if (!empty($opts['opacity'])) $str['cursoropacitymax'] = $opts['opacity'];
 
    $jquery = (isset($opts['jquery'])) ? ($opts['jquery']=='Y') : true;
    
    $stropts = (count($str)>0) ? json_encode($str) : '{cursorwidth:20}';
    
    $plugin_location=WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));
    $jq=$plugin_location."jquery.min.js";
    $jns=$plugin_location."jquery.nicescroll.min.js";
    
    if ($jquery) echo "<script type=\"text/javascript\" src=\"$jq\"></script>\n";
    echo "<script type=\"text/javascript\" src=\"$jns\"></script>\n";
    
    $out = "
    <script type=\"text/javascript\">
      (function(){
        var nice = false;
        ";        
    $out.=($jquery)?"var $ = NiceScroll.getjQuery();":"";
    $out.="$(document).ready(function(){
          nice = $(\"$dom\").niceScroll($stropts);
        });
        $(window).load(function(){
          nice.resize();
        });
      })();
    </script>
    ";
    
  echo $out;
}

function _jnc_AdminSettings(){
    include("jnc_admin_set.php");
}
        
        
function jncmenu(){   
    add_options_page('Nicescroll', 'WP Nicescroll', 'administrator', 'Nicescroll', '_jnc_AdminSettings');    
}

if(is_admin()){
    add_Action("admin_menu","jncmenu");
}
        
add_action('wp_head',"addNiceScroll");
 