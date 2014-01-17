<?php
        
/*              
    Plugin Name: SEO Links
    Plugin URI: https://github.com/Towhidn/Q2A-SEO-Links/
    Plugin Update Check URI:  https://github.com/Towhidn/Q2A-SEO-Links/master/qa-plugin.php
    Plugin Description: SEO Links for Question2Answer
    Plugin Version: 1.1
    Plugin Date: 2013-11-12
    Plugin Author: QA-Themes.com
    Plugin Author URI: http://QA-Themes.com
    Plugin License: copy lifted                           
    Plugin Minimum Question2Answer Version: 1.5
*/                      
                        
    if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
                    header('Location: ../../');
                    exit;   
    }               

	qa_register_plugin_layer('qa-webmaster-layer.php', 'Webmaster Layer');
	
/*                              
    Omit PHP closing tag to help avoid accidental output
*/
