<?php
class qa_html_theme_layer extends qa_html_theme_base {
	var $plugin_directory;
	var $plugin_url;
	function qa_html_theme_layer($template, $content, $rooturl, $request)
	{
		global $qa_layers;
		$this->plugin_directory = $qa_layers['Webmaster Layer']['directory'];
		$this->plugin_url = $qa_layers['Webmaster Layer']['urltoroot'];
		qa_html_theme_base::qa_html_theme_base($template, $content, $rooturl, $request);
	}
	function head_css() {
		global $qa_request;
		if ( ($qa_request == 'webmaster') && (qa_get_logged_in_level()>=QA_USER_LEVEL_ADMIN) )
			$this->output('<LINK REL="stylesheet" TYPE="text/css" HREF="'. qa_opt('site_url') . $this->plugin_url.'include/style.css'.'"/>');
		qa_html_theme_base::head_css();
	}	
	function head_script(){
		qa_html_theme_base::head_script();
		global $qa_request;
		if ( ($qa_request == 'webmaster') && (qa_get_logged_in_level()>=QA_USER_LEVEL_ADMIN) )
				$this->output('<script type="text/javascript" src="'. qa_opt('site_url') . $this->plugin_url .'include/easyResponsiveTabs.js"></script>');  
	}	
	function body_footer(){
		global $qa_request;
		if ( ($qa_request == 'webmaster') && (qa_get_logged_in_level()>=QA_USER_LEVEL_ADMIN) )
			$this->output(
				'<script type="text/javascript">',
				'$("#verticalTab").easyResponsiveTabs({',
				'   type: "vertical",',      
				'   width: "auto",',
				'   fit: true',
				'});',
				'</script>'
			);
		qa_html_theme_base::body_footer();
	}
	function doctype(){
		// Setup Navigation
		global $qa_request;
		if (qa_get_logged_in_level()>=QA_USER_LEVEL_ADMIN){
			if (qa_opt('wm_link_nav')) {
				$this->content['navigation']['main']['webmaster'] = array(
					'label' => 'WebMaster',
					'url' => qa_path_html('webmaster'),
					'opposite' => true,
				);
				if($qa_request == 'webmaster') {
					$this->content['navigation']['main']['webmaster']['selected'] = true;
				}
			}
			if($qa_request == 'webmaster') {
					$this->template="q2a_webmaster";
					$this->content['site_title']="Reports";
					$this->content['error']="";
					$this->content['suggest_next']="";
					$this->content['title']="Question2Answer Webmaster";
			}
			if($qa_request == 'webmaster') {
				require_once QA_INCLUDE_DIR.'qa-db-recalc.php';
				require_once QA_INCLUDE_DIR.'qa-app-admin.php';
				require_once QA_INCLUDE_DIR.'qa-db-admin.php';

				$this->content['custom']='
				<div id="verticalTab">
					<ul class="resp-tabs-list">
						<li>Q2A Stats<span>Statistics & Version info</span></li>
						<li>SEO Statistics<span>SEO Report & Social Statistics</span></li>
						<li>Server Info<span>Resource Usage & Limitations</span></li>
						<li>Server Security<span>find possible vulnerabilities</span></li>
						<li>About<span>Webmaster Plugin & Developer</span></li>
					</ul>
					<div class="resp-tabs-container">
						<div>
							' . $this->getStats() . '
						</div>
						<div>
							' . $this->getSEO() . '
						</div>
						<div>
							' . $this->getServerInfo() . '
						</div>
						<div>
							' . $this->getServerSecurity() . '
						</div>
						<div>
							' . $this->getVersion() . $this->getAbout() . '
						</div>
					</div>
				</div>
				';
			}
		}else{
			if($qa_request == 'webmaster')
				$this->content['custom'] = '<div class="qa-error">Nosy little thing you are, aren\'t you?</div> <strong>Log in as Administrator to see all those precious information.</strong>' . $this->getAbout();
		}
		qa_html_theme_base::doctype();
	}
	function getVersion(){
		$content='
		<div class="wm-info-section">
			<span class="wm-info-header">
				<strong>Q2A Webmaster</strong>
				<span class="wm-info-count">version 1.0</span>
			</span>
			<span class="wm-info-header">
				<strong>Release date</strong>
				<span class="wm-info-count">January 17, 2014</span>
			</span>
			
			</div>
		';
		return $content ;
	}
	function getAbout(){
		$content= '
			<div class="wm-info-section-full">
				<hr>
				<h1>About Developer</h1>
				<p>Hi there, I\'m <a href="http://towhidn.com">Towhid</a>. a freelance web developer and designer.</p>
				<p>if want to hire a professional web development feel free to <a href="http://qa-themes.com/contact-us">contact me</a>.<br>
				also if you are working on an StartUp or you have an interesting idea in your mind, I\'ll be glad to hear about it and exchange ideas.<br></p>
			</div>
			<div class="wm-info-section-full">
				<hr>
				<h1>Some Resources</h1>
				<p><ul>
					<li><a href="http://qa-themes.com/forums/forum/plugins-support">Plugin Support</a></li>
					<li><a href="http://qa-themes.com/themes">Our Themes</a></li>
					<li><a href="http://qa-themes.com/plugins">Our Plugins</a></li>
				</ul>
				send your ideas and suggestions to our <a href="http://idea.qa-themes.com/" title="Question2Answer Idea & Suggestions">IdeaBox</a>.</p>
			</div>
			<hr>
			<div class="wm-info-section-full" style="text-align:center;">
				<span><a href="http://QA-Themes.com/" title="Question2Answer Themes"><img src="' . qa_opt('site_url') . $this->plugin_url .'include/q2a-theme-logo.png"></a></span>
			</div>
		';
		return $content ;
	}
	
	function getServerSecurity(){
	error_reporting(E_ALL);
	ini_set('display_errors', '1'); 
		$content='';
		require_once $this->plugin_directory.'library/security.php';
		foreach ($security as $key => $value) {
		$content.='
			<div class="wm-info-section-full">
				<span class="wm-info-header">
					<strong>' . $key . ' '. $value['link'] . '</strong>
					<span class="wm-info-count">' . $value['status'] . '</span>
					<span class="wm-info-detail">' . @$value['detail'] . '</span>
				</span>
			</div>
			<hr>
		';
		} 
		return $content ;
	}
	
	function getServerInfo(){
		if (!function_exists('memory_get_usage')){
			$memory_usage=$this->memory_get_usage();
			$real_memory_usage = $memory_usage;
			$f_memory_usage = $this->formatBytes($memory_usage);
			$f_real_memory_usage = 'unknown';
		}else{
			$memory_usage = memory_get_usage();
			$real_memory_usage = memory_get_usage(true);
			$f_memory_usage = $this->formatBytes($memory_usage);
			$f_real_memory_usage = $this->formatBytes($real_memory_usage);
		}
		if (!function_exists('memory_get_peak_usage')){
			$peak_memory_usage = 0;
			$f_peak_memory_usage='unknown';
			//$real_peak_memory_usage = 'unknown';
		}else{
			$peak_memory_usage = memory_get_peak_usage();
			$f_peak_memory_usage = $this->formatBytes($peak_memory_usage);
			//$real_peak_memory_usage = memory_get_peak_usage(true);
		}
		$server = &$_SERVER;
		$php_limit = @get_cfg_var('memory_limit');
		$php_limit_byte = $this->return_bytes(@get_cfg_var('memory_limit'));
		$content='
		<div class="wm-info-section-full">
			<span class="wm-info-header">
				<strong>Memory Usage Sampling</strong>
				<span class="wm-info-count">' . $f_memory_usage . '</span>
			</span>
			<span class="wm-info-header">
				<strong>Real Memory Usage</strong>
				<span class="wm-info-count">' . $f_real_memory_usage . '</span>
			</span>
			<span class="wm-info-header">
				<strong>Peak Usage</strong>
				<span class="wm-info-count">' . $f_peak_memory_usage . '</span>
			</span>
			<span class="wm-info-header">
				<strong>PHP Memory Limit</strong>
				<span class="wm-info-count">' . $php_limit . '</span>
			</span>
			<span class="wm-info-bar-big">
				<span class="wm-info-percent-big" style="width:' . ( ($memory_usage/$php_limit_byte) * 100 ) . '%"></span>' . round( (($memory_usage/$php_limit_byte) * 100),2 ) . '%
			</span>
			<hr>
			<span class="wm-info-header">
				<strong>PHP Version</strong>
				<span class="wm-info-count">' . phpversion() . '</span>
			</span>
			<span class="wm-info-header">
				<strong>MySQL Version</strong>
				<span class="wm-info-count">' . qa_db_mysql_version() . '</span>
			</span>
			<span class="wm-info-header">
				<strong>Database Size</strong>
				<span class="wm-info-count">' . number_format(qa_db_table_size()/1048576, 1) . '</span>
			</span>	
			<span class="wm-info-header">
				<strong>Maximum Upload Size</strong>
				<span class="wm-info-count">' . ini_get('upload_max_filesize') . '</span>
			</span>
			<hr>
			<span class="wm-info-header">
				<strong>OS/Server:</strong>
				<span class="wm-info-content">' . php_uname() . '</span>
			</span>
			<span class="wm-info-header">
				<strong>Server Address:</strong>
				<span class="wm-info-content">' . $server['SERVER_ADDR'] . '</span>
			</span>
			<span class="wm-info-header">
				<strong>Server Name:</strong>
				<span class="wm-info-content">' . $server['SERVER_NAME'] . '</span>
			</span>
			<span class="wm-info-header">
				<strong>Server Software:</strong>
				<span class="wm-info-content">' . $server['SERVER_SOFTWARE'] . '</span>
			</span>
			<span class="wm-info-header">
				<strong>Server Protocol:</strong>
				<span class="wm-info-content">' . $server['SERVER_PROTOCOL'] . '</span>
			</span>			<span class="wm-info-header">
				<strong>User Agent:</strong>
				<span class="wm-info-content">' . $server['HTTP_USER_AGENT'] . '</span>
			</span>
		</div>
			
		';
		return $content ;
	}
	function getSEO(){
		set_time_limit(120); // give it 2 minutes to get all rankings
		require_once $this->plugin_directory.'library/seo.php';
		$homepage = qa_opt('site_url');
		
		if (qa_clicked('updateseo')) {
			// SEO Info
			$report['GPR'] = getPR($homepage);
			$report['GIP'] = getGoogleIndexedPages($homepage);
			$report['SEM'] = getSEM($homepage);
			$report['ALEXA'] = getAlexa($homepage);
			if ($report['ALEXA']['dmoz']==0) $report['ALEXA']['dmoz']='No'; else	$report['ALEXA']['dmoz']='Yes';
			// Social Activity
			$report['Tweets'] = getTweets($homepage);
			$report['FBCount'] = getFacebookCount($homepage);
			$report['LinkedinShares'] = getLinkedin($homepage);
			$report['GPlusOnes'] = getPlusOnes($homepage);
			$report['DeliciousBookmarks'] = getDeliciousBookmarks($homepage);
			$report['StumbleuponViews'] = getStumbleViews($homepage);
			$report['PinterestPins'] = getPinterestPins($homepage);
			qa_opt('seo_report' , json_encode($report) );
			
		}else{
			$report=json_decode( qa_opt('seo_report') ,true);
		}
		$content='';
		if (empty($report)){
			$content='
			<div class="wm-info-section-full">
				<span class="wm-info-header">
					<strong>You had never updated your SEO Status</strong>
					<span class="wm-info-count">
						<form name="webmaster_form" action="'.qa_self_html().'#verticalTab2" method="post">
							<input id="updateseo" NAME="updateseo" class="qa-form-tall-button qa-form-tall-button-updateseo" type="submit" title="" value="Update Stats Now!">
						</form>
					</span>
				</span>
				<hr>
			</div>
			';
		}
		$content.='
		<div class="wm-info-section">
			<span class="wm-info-header">
				Google Page Rank
				<span class="wm-info-count">' . $report['GPR'] . '</span>
			</span>
			<span class="wm-info-bar">
				<span class="wm-info-percent" style="width:' . ( (int)$report['GPR'] * 10 ) . '%"></span>
			</span>
		</div>
		<div class="wm-info-section">
			<span class="wm-info-header">
				Indexed Pages in Google (<a href="http://www.google.com/search?q=site:' . $homepage . '">#</a>)
				<span class="wm-info-count">' . $report['GIP'] . '</span>
			</span>
		</div>
		<hr>
		<div class="wm-info-section">
			<span class="wm-info-header">Alexa info</span>
			<span class="wm-info-line"></span>
			<span class="wm-info-content">Rank: ' . $report['ALEXA']['rank'] . '</span>
			<span class="wm-info-content">Links: ' . $report['ALEXA']['links'] . '</span>
			<span class="wm-info-content">speed: ' . $report['ALEXA']['speed'] . '</span>
			<span class="wm-info-content">Is in dmoz: ' . $report['ALEXA']['dmoz'] . '</span>
		</div>
		<hr>
		<div class="wm-info-section">
			<span class="wm-info-header">SEMrush info</span>
			<span class="wm-info-line"></span>
			<span class="wm-info-content">Keywords: ' . $report['SEM']['keywords'] . '</span>
			<span class="wm-info-content">Cost: ' . $report['SEM']['cost'] . '</span>
			<span class="wm-info-content">Traffic: ' . $report['SEM']['traffic'] . '</span>
			<span class="wm-info-content">Rank: ' . $report['SEM']['rank'] . '</span>
		</div>
		<hr>
		<div class="wm-info-section">
			<span class="wm-info-header">Social Report</span>
			<span class="wm-info-line"></span>
			<span class="wm-info-content">FaceBook Activities: ' . $report['FBCount'] . '</span>
			<span class="wm-info-content">Tweets: ' . $report['Tweets'] . '</span>
			<span class="wm-info-content">Google\'s PlusOnes: ' . $report['GPlusOnes'] . '</span>
			<span class="wm-info-content">Linkedin Shares: ' . $report['LinkedinShares'] . '</span>
			<span class="wm-info-content">delicious bookmarks: ' . $report['DeliciousBookmarks'] . '</span>
			<span class="wm-info-content">Stumbleupon views: ' . $report['StumbleuponViews'] . '</span>
			<span class="wm-info-content">pinterest pins: ' . $report['PinterestPins'] . '</span>
		</div>
		<form name="webmaster_form" action="'.qa_self_html().'#verticalTab2" method="post">
			<input id="updateseo" NAME="updateseo" class="qa-form-tall-button qa-form-tall-button-updateseo" type="submit" title="" value="Update Stats">
		</form>
		';
		return $content ;
	
	}
	
	function getStats(){
	
		// it's posible to use qa_db_count_posts() function
		$qcount=(int)qa_opt('cache_qcount');
		$qcount_anon=qa_db_count_posts('Q', false);

		$acount=(int)qa_opt('cache_acount');
		$acount_anon=qa_db_count_posts('A', false);

		$ccount=(int)qa_opt('cache_ccount');
		$ccount_anon=qa_db_count_posts('C', false);
		
		$words= qa_db_count_words();
		
		$user_count=qa_db_count_users();
		$users = QA_FINAL_EXTERNAL_USERS ? '<span>number of users is not extracted for external integrations</span>' : ('<span>'.$user_count.'</span><p>Users</p>');
		$users_with_points = (int)qa_opt('cache_userpointscount'); // users with points
		$users_with_posts = qa_db_count_active_users('posts'); // users with posts
		$inactive_users = (int)$user_count - $users_with_points;
		
		//$users_with_votes = qa_db_count_active_users('uservotes'); // users with votes
		$content='
			<div class="wm-half">
				<div class="wm-widget wm-yellow">
					<div class="wm-widget-content">
						<span>' . $qcount . '</span>
						<p>Questions</p>
					</div>
					<div class="wm-widget-content">
						<div class="wm-widget-content-sub">
							<span>' . ((int)$qcount - (int)$qcount_anon) . '</span>
							<p>from users</p>				
						</div>
						<div class="wm-widget-content-sub">
							<span>' . $qcount_anon . '</span>
							<p>Anonymous</p>				
						</div>
					</div>
				</div>
			</div>
			<div class="wm-half">
			<div class="wm-widget wm-green">
				<div class="wm-widget-content">
					<span>' . $acount . '</span>
					<p>Answers</p>
				</div>
				<div class="wm-widget-content">
					<div class="wm-widget-content-sub">
						<span>' . ((int)$acount - (int)$acount_anon) . '</span>
						<p>from users</p>				
					</div>
					<div class="wm-widget-content-sub">
						<span>' . $acount_anon . '</span>
						<p>Anonymous</p>				
					</div>
				</div>
			</div>
			</div>
			<div class="wm-half">
				<div class="wm-widget wm-blue">
					<div class="wm-widget-content">
						<span>' . $ccount . '</span>
						<p>Comments</p>
					</div>
					<div class="wm-widget-content">
						<div class="wm-widget-content-sub">
							<span>' . ((int)$ccount - (int)$ccount_anon) . '</span>
							<p>from Users</p>				
						</div>
						<div class="wm-widget-content-sub">
							<span>' . $ccount_anon . '</span>
							<p>Anonymous</p>				
						</div>
					</div>
				</div>
			</div>
			<div class="wm-half">
				<div class="wm-widget wm-black">
					<div class="wm-widget-content">
						' . $users . '
					</div>
					<div class="wm-widget-content">
						<div class="wm-widget-content-sub">
							<span>' . $inactive_users . '</span>
							<p>Inactive</p>				
						</div>
						<div class="wm-widget-content-sub">
							<span>' . $users_with_posts . '</span>
							<p>With Content</p>				
						</div>
					</div>
				</div>
			</div>
			<hr>
			<p>You are using Question2Answer <span>v' . QA_VERSION . '</span> Build  in ' . QA_BUILD_DATE . '</p>
			<p>Latest Version of Q2A is <iframe src="http://www.question2answer.org/question2answer-latest.php?version='.urlencode(QA_VERSION).'&language='.urlencode(qa_opt('site_language')) . '" height="16" style="vertical-align:text-top; border:0; background:transparent;" allowTransparency="true" scrolling="no" frameborder="0"></iframe></p>
		';
		return $content ;
	}
	
	function formatBytes($bytes) { 
		$sizes = array( 'Bytes', 'KB', 'MB', 'GB', 'TB');
		if ($bytes == 0) return 'n/a';
		$i = intval(floor(log($bytes) / log(1024)));
		if ($i == 0) return $bytes . ' ' . $sizes[$i]; 
		return round(($bytes / pow(1024, $i)),1). ' ' . $sizes[$i];
	} 
	
	function return_bytes($val) {
		$val = trim($val);
		$last = strtolower($val[strlen($val)-1]);
		switch($last) {
			// The 'G' modifier is available since PHP 5.1.0
			case 'g':
				$val *= 1024;
			case 'm':
				$val *= 1024;
			case 'k':
				$val *= 1024;
		}

		return $val;
	}
	function memory_get_usage()
    {
        //If its Windows
        //Tested on Win XP Pro SP2. Should work on Win 2003 Server too
        //Doesn't work for 2000
        //If you need it to work for 2000 look at http://us2.php.net/manual/en/function.memory-get-usage.php#54642
        if ( substr(PHP_OS,0,3) == 'WIN')
        {
               if ( substr( PHP_OS, 0, 3 ) == 'WIN' )
                {
                    $output = array();
                    exec( 'tasklist /FI "PID eq ' . getmypid() . '" /FO LIST', $output );
       
                    return preg_replace( '/[\D]/', '', $output[5] ) * 1024;
                }
        }else
        {
            //We now assume the OS is UNIX
            //Tested on Mac OS X 10.4.6 and Linux Red Hat Enterprise 4
            //This should work on most UNIX systems
            $pid = getmypid();
            exec("ps -eo%mem,rss,pid | grep $pid", $output);
            $output = explode("  ", $output[0]);
            //rss is given in 1024 byte units
            return $output[1] * 1024;
        }
    } 
}


/*
	Omit PHP closing tag to help avoid accidental output
*/
