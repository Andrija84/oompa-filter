<?php

//https://codex.wordpress.org/AJAX_in_Plugins

//if ( ! defined( ‘ABSPATH’ ) ) exit;

//if(!defined('NONCE')) {die('Direct access not permitted');}


$path = $_SERVER['DOCUMENT_ROOT'].'/test';

//include_once $path . '/wp-config.php';
include_once $path . '/wp-load.php';
//include_once $path . '/wp-includes/wp-db.php';
//include_once $path . '/wp-includes/pluggable.php';


$job = $_POST['job'];



if ( $job = 'insertTaxonomy' ){


      global $wpdb;
      $table_name = $wpdb->prefix.'oompa_filter';

      foreach($_POST['taxonomy_name'] as $key => $value ) {
          $taxonomyName = $_POST['taxonomy_name'][$key];

          $query = $wpdb->insert(
                          $table_name,
                          array(
                                  'taxonomySlug'=>$taxonomyName,
                                  'taxonomyLabel'=>'TEST'),
                          array( '%s','%s' )
                       );
          if ($query === false){
            return 'ERROR';
          } // Fail -- the "===" operator compares type as well as value
          if ($query > 0){
            return 'SUCCESS';
          }

      }



}

 ?>
