<?php
$safe='<span class="wm-safe-text">Safe</span>';
$unsafe='<span class="wm-unsafe-text">Unsafe</span>';
$warning='<span class="wm-warning-text">Warning</span>';

$security = array();
$security['allow_url_fopen']['value'] = ini_get('allow_url_fopen');
$security['expose_php']['value'] = ini_get('expose_php');
$security['register_globals']['value'] = ini_get('register_globals');
$security['display_errors']['value'] = ini_get('display_errors');
$security['server_signature']['value'] = isset($_SERVER['SERVER_SIGNATURE']) && trim($_SERVER['SERVER_SIGNATURE']) != '' ? true : false;
$security['allow_url_include']['value'] = ini_get('allow_url_include');
$security['safe_mode']['value'] = ini_get('safe_mode');
$security['open_basedir']['value'] = (ini_get('open_basedir') && trim(ini_get('open_basedir')) != '') ? true : false;
$security['mod_security']['value'] = checkModSecurity();
$security['magic_quotes_gpc']['value'] = (int) get_magic_quotes_gpc();
$security['upload_tmp_dir']['value'] = ini_get('upload_tmp_dir');

// ~~~~ allow_url_fopen ~~~~~
if ($security['allow_url_fopen']['value']) {
	$security['allow_url_fopen']['status']=$unsafe;
	$security['allow_url_fopen']['detail']='
		The <strong>allow_url_fopen</strong> directive is set to ON.  It is recommended that you disable
		allow_url_fopen in the <em>php.ini</em> file for security reasons.  This allows PHP file functions, such as 
		<code>include</code>, <code>require</code>, and <code>file_get_contents()</code>, to retrieve data from remote 
		locations (Example: FTP, web site).  According to PHP Security Consortium, a large number of code injection 
		vulnerabilities are caused by the combination of enabling allow_url_fopen, and bad input filtering.
	';
}else{
	$security['allow_url_fopen']['status']=$safe;
	$security['allow_url_fopen']['detail']='
		The <strong>allow_url_fopen</strong> directive is set to OFF.  This disallows PHP file functions, such as 
		<code>include</code>, <code>require</code>, and <code>file_get_contents()</code>, from retrieving data from remote 
		locations (Example: FTP, web site).  According to PHP Security Consortium, a large number of code injection 
		vulnerabilities are caused by the combination of enabling allow_url_fopen, and bad input filtering.
	';
}
$security['allow_url_fopen']['link']='<a href="http://phpsec.org/projects/phpsecinfo/tests/allow_url_fopen.html">#</a>';
// ~~~~ expose_php ~~~~~
if ($security['expose_php']['value']) {
	$security['expose_php']['status']=$warning;
	$security['expose_php']['detail']='
		<p>When enabled, <strong>expose_php</strong> reports in every request that PHP is being used to process the request, and what version of PHP
		is installed. Malicious users looking for potentially vulnerable targets can use this to identify a weakness.</p>
	';
}else{
	$security['expose_php']['status']=$safe;
	$security['expose_php']['detail']='
		<strong>expose_php</strong> is set to off. it means that Apache is not sending its version information in headers.
	';
}
$security['expose_php']['link']='<a href="http://phpsec.org/projects/phpsecinfo/tests/allow_url_fopen.html">#</a>';
// ~~~~ register_globals ~~~~~
if ($security['register_globals']['value']) {
	$security['register_globals']['status']=$warning;
	$security['register_globals']['detail']='
		<p>The <strong>register_globals</strong> setting in <em>php.ini</em> is set to ON.  This feature has been depreciated 
		as of PHP 5.3 and removed as of PHP 6.0.  Relying on this feature is <em>highly</em> discouraged.</p>	
	';
}else{
	$security['register_globals']['status']=$safe;
	$security['register_globals']['detail']='
		<p>The <strong>register_globals</strong> setting in <em>php.ini</em> is set to OFF.</p>
	';			
}
$security['register_globals']['link']='<a href="http://phpsec.org/projects/phpsecinfo/tests/register_globals.html">#</a>';
// ~~~~ display_errors ~~~~~
if ($security['display_errors']['value'] == '1') {
	$security['display_errors']['status']=$warning;
	$security['display_errors']['detail']='
				<p>The <strong>display_errors</strong> setting in <em>php.ini</em> is set to ON.  This means that PHP errors, and 
				warnings are being displayed. Such warnings can cause sensitive information to be revealed to users (paths, database 
				queries, etc.).</p>	
	';
}else{
	$security['display_errors']['status']=$safe;
	$security['display_errors']['detail']='
		<p>The <strong>display_errors</strong> setting in <em>php.ini</em> is set to OFF.  This is the proper setting for a 
		production environment.</p>
	';			
}
$security['display_errors']['link']='<a href="http://phpsec.org/projects/phpsecinfo/tests/display_errors.html">#</a>';
// ~~~~ server_signature ~~~~~
if ($security['server_signature']['value']) {
	$security['server_signature']['status']=$unsafe;
	$security['server_signature']['detail']='
		<p>Apache\'s <strong>ServerSignature</strong> directive is set to ON. This means that your server software version, and 
		other important details are public, which can give hackers information necessary to exploit version and software-specific 
		vulnerabilities.</p>	
	';
}else{
	$security['server_signature']['status']=$safe;
	$security['server_signature']['detail']='
		<p>Apache\'s <strong>ServerSignature</strong> directive is set to OFF. This prevents hackers from gaining information 
		that could help them exploit vulnerabilities based on your specific server software version.</p>	
	';			
}
$security['server_signature']['link']='<a href="http://phpsec.org/projects/phpsecinfo/tests/allow_url_fopen.html">#</a>';
// ~~~~ allow_url_include ~~~~~
if ($security['allow_url_include']['value']) {
	$security['allow_url_include']['status']=$warning;
	$security['allow_url_fopen']['detail']='
		<p>The <strong>allow_url_include</strong> directive is set to ON.  <code>allow_url_include</code> allows 
		remote file access via <code>include</code> and <code>require</code>.  We <em>strongly</em> recommend 
		disabling this functionality, as <code>include</code> and <code>require</code> are the most common attack 
		points for code injection attempts.</p>
	';
}else{
	$security['allow_url_include']['status']=$safe;
	$security['allow_url_include']['detail']='
				<p>The <strong>allow_url_include</strong> directive is set to OFF.  This disables remote file access 
				via <code>include</code> and <code>require</code>.  <code>include</code> and <code>require</code> are the 
				most common attack points for code injection attempts.</p>
	';			
}
$security['allow_url_include']['link']='<a href="http://phpsec.org/projects/phpsecinfo/tests/allow_url_include.html">#</a>';
// ~~~~ safe_mode ~~~~~
if ($security['safe_mode']['value']) {
	$security['safe_mode']['status']=$warning;
	$security['safe_mode']['detail']='
		<p>The <strong>safe_mode</strong> setting in <em>php.ini</em> is set to ON.  This feature is depreciated in PHP 5.3 
		and is removed in PHP 6.0.  Relying on this feature is architecturally incorrect, as this should not be solved at 
		the PHP level.</p>
	';
}else{
	$security['safe_mode']['status']=$safe;
	$security['safe_mode']['detail']='
		<p>The <strong>safe_mode</strong> setting in <em>php.ini</em> is set to OFF.  While relying on this feature is 
		architecturally incorrect because this should not be solved at the PHP level, many ISP\'s still use safe mode in 
		shared hosting situations due to limitations at the level the web server and OS.</p>
	';			
}
$security['safe_mode']['link']='<a href="http://www.php.net/manual/en/ini.sect.safe-mode.php">#</a>';
// ~~~~ open_basedir ~~~~~
if ($security['open_basedir']['value']) {
	$security['open_basedir']['status']=$unsafe;
	$security['open_basedir']['detail']='
		<p>The <strong>open_basedir</strong> directive is not set. <code>open_basedir</code>, set in <em>php.ini</em>, 
		limits the PHP process from accessing files outside of the specified directories.  It is strongly 
		suggested that you set <code>open_basedir</code> to your web site documents and shared libraries 
		<em>only</em>.</p>	
	';
}else{
	$security['open_basedir']['status']=$safe;
	$security['open_basedir']['detail']='
		<p>The <strong>open_basedir</strong> directive is set to <code><?php echo @ini_get(\'open_basedir\'); ?></code>. 
		<code>open_basedir</code>, set in <em>php.ini</em> limits the PHP process from accessing files outside of 
		the specified directories.</p>
	';			
}
$security['open_basedir']['link']='<a href="http://phpsec.org/projects/phpsecinfo/tests/open_basedir.html">#</a>';
// ~~~~ mod_security ~~~~~
if ($security['mod_security']['value']) {
	$security['mod_security']['status']=$unsafe;
	$security['mod_security']['detail']='
				<p><strong>mod_security</strong> for Apache is not installed. ModSecurity can help protect your server against SQL 
				injections, XSS attacks, and a variety of other attacks. The Apache module is available for free at 
				<a href="http://www.modsecurity.org" title="ModSecurity">http://www.modsecurity.org</a>.</p>
	';
}elseif ( $security['mod_security'] === 'N/A' ){
	$security['mod_security']['status']=$unsafe;
	$security['mod_security']['detail']='
				<p>Unable to determine if <strong>mod_security</strong> for Apache is installed. This can happen if a host uses 
				a different name for the Apache module, or if the <em>apache_get_modules()</em> function is not available in your 
				PHP installation. ModSecurity can help protect your server against SQL injections, XSS attacks, and a variety of 
				other attacks. The Apache module is available for free at 
				<a href="http://www.modsecurity.org" title="ModSecurity">http://www.modsecurity.org</a>.</p>
	';
}else{
	$security['mod_security']['status']=$safe;
	$security['mod_security']['detail']='
				<p><strong>mod_security</strong> for Apache is installed and actively protecting your web server.</p>	
	';			
}
$security['mod_security']['link']='<a href="http://en.wikipedia.org/wiki/ModSecurity">#</a>';
// ~~~~ magic_quotes_gpc ~~~~~
if ($security['magic_quotes_gpc']['value']) {
	$security['magic_quotes_gpc']['status']=$unsafe;
	$security['magic_quotes_gpc']['detail']='
		<p><strong>Magic Quotes</strong> is set to ON. This feature has been depreciated as of PHP 5.3 and removed as of PHP 
		6.0. Relying on this feature is highly discouraged. It is preferred to code with magic quotes off and to instead 
		escape the data at runtime, as needed.</p>	
	';
}else{
	$security['magic_quotes_gpc']['status']=$safe;
	$security['magic_quotes_gpc']['detail']='
		<p><strong>Magic Quotes</strong> is set to OFF. This is the proper setting for any environment.</p>
	';			
}
$security['magic_quotes_gpc']['link']='<a href="http://phpsec.org/projects/phpsecinfo/tests/magic_quotes_gpc.html">#</a>';
// ~~~~ upload_tmp_dir ~~~~~
if ( empty( $security['upload_tmp_dir']['value'] ) ) 
	if (function_exists("sys_get_temp_dir")) {
		$security['upload_tmp_dir']['value'] = sys_get_temp_dir();
	} else {
		$security['upload_tmp_dir']['value'] = MY_sys_get_temp_dir();
	}
$upload_tmp_dir_perms = @fileperms($security['upload_tmp_dir']['value']);
if ( $upload_tmp_dir_perms === false ) {
	$security['upload_tmp_dir']['status'] = $warning;
	$security['upload_tmp_dir']['detail']='
		<p>unable to retrieve file permissions on upload_tmp_dir</p>
	';
}elseif (	$security['upload_tmp_dir']['value']
            && !preg_match("|" . '/tmp' . "/?|", $security['upload_tmp_dir']['value'])
            && !($upload_tmp_dir_perms & 0x0004)
            && !($upload_tmp_dir_perms & 0x0002)
        ){
	$security['upload_tmp_dir']['status']=$safe;
	$security['upload_tmp_dir']['detail']='
		<p><strong>upload_tmp_dir</strong> is enabled, which is the
		recommended setting. Make sure your upload_tmp_dir path is not world-readable</p>	
	';	
}
else{
	$security['upload_tmp_dir']['status']=$unsafe;
	$security['upload_tmp_dir']['detail']='
		<p><strong>upload_tmp_dir</strong> is disabled, or is set to a
        common world-writable directory. This typically allows other users on this server
        to access temporary copies of files uploaded via your PHP scripts. You should set
        upload_tmp_dir to a non-world-readable directory</p>	
	';			
}
$security['upload_tmp_dir']['link']='<a href="http://phpsec.org/projects/phpsecinfo/tests/upload_tmp_dir.html">#</a>';


function checkModSecurity() {
	if (function_exists('apache_get_modules')) {
		$apache_mods = apache_get_modules();
		$modSecurity = in_array('mod_security', $apache_mods) || in_array('mod_security2', $apache_mods) ? true : false;
		if (!$modSecurity && in_array('security2_module', $apache_mods))
			$modSecurity = 'N/A';
	} else {
		$modSecurity = 'N/A';
	}
	
	return $modSecurity;
}
function MY_sys_get_temp_dir()
{
    // Try to get from environment variable
    $vars = array('TMP', 'TMPDIR', 'TEMP');
    foreach ($vars as $var) {
        $tmp = getenv($var);
        if (!empty($tmp)) {
            return realpath($tmp);
        }
    }
    return NULL;
}