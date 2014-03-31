<?php
        
/*              
    Plugin Name: Webmaster Reports
    Plugin URI: https://github.com/Towhidn/Q2A-Webmaster
    Plugin Update Check URI:  https://github.com/Towhidn/Q2A-Webmaster/raw/master/q2a-webmaster/qa-plugin.php
    Plugin Description: Webmaster Reports for Q2A Admin
    Plugin Version: 1.2
    Plugin Date: 2014-20-1
    Plugin Author: QA-Themes.com
    Plugin Author URI: http://QA-Themes.com
    Plugin License: copy lifted                           
*/                      
                        
    if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
                    header('Location: ../../');
                    exit;   
    }               

	qa_register_plugin_module('page', 'qa-webmaster-admin.php', 'qa_webmaster_admin', 'Webmaster Options');
	qa_register_plugin_layer('qa-webmaster-layer.php', 'Webmaster Layer');
	
/*                              
    Omit PHP closing tag to help avoid accidental output
*/
