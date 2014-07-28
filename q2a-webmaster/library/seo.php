<?php

/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
					Google PageRank
   ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
function getPR($url) {
	$query="http://toolbarqueries.google.com/tbr?client=navclient-auto&ch=".CheckHash(HashURL($url)). "&features=Rank&q=info:".$url."&num=100&filter=0";
	$data=getPageData($query);
	$pos = strpos($data, "Rank_");
	if($pos === false){return 0;} else{
		$pagerank = substr($data, $pos + 9);
		return $pagerank;
	}
}
function StrToNum($Str, $Check, $Magic)
{
	$Int32Unit = 4294967296;
	$length = strlen($Str);
	for ($i = 0; $i < $length; $i++) {
		$Check *= $Magic;
		if ($Check >= $Int32Unit) {
			$Check = ($Check - $Int32Unit * (int) ($Check / $Int32Unit));
			$Check = ($Check < -2147483648) ? ($Check + $Int32Unit) : $Check;
		}
		$Check += ord($Str{$i});
	}
	 return $Check;
}
function HashURL($String)
{
	$Check1 = StrToNum($String, 0x1505, 0x21);
	$Check2 = StrToNum($String, 0, 0x1003F);
	$Check1 >>= 2;
	$Check1 = (($Check1 >> 4) & 0x3FFFFC0 ) | ($Check1 & 0x3F);
	$Check1 = (($Check1 >> 4) & 0x3FFC00 ) | ($Check1 & 0x3FF);
	$Check1 = (($Check1 >> 4) & 0x3C000 ) | ($Check1 & 0x3FFF);
	$T1 = (((($Check1 & 0x3C0) << 4) | ($Check1 & 0x3C)) <<2 ) | ($Check2 & 0xF0F );
	$T2 = (((($Check1 & 0xFFFFC000) << 4) | ($Check1 & 0x3C00)) << 0xA) | ($Check2 & 0xF0F0000 );
	return ($T1 | $T2);
}
function CheckHash($Hashnum)
{
	$CheckByte = 0;
	$Flag = 0;
	$HashStr = sprintf('%u', $Hashnum) ;
	$length = strlen($HashStr);
	for ($i = $length - 1; $i >= 0; $i --) {
		$Re = $HashStr{$i};
		if (1 === ($Flag % 2)) {
		$Re += $Re;
		$Re = (int)($Re / 10) + ($Re % 10);
		}
		$CheckByte += $Re;
		$Flag ++;
	}
	$CheckByte %= 10;
	if (0 !== $CheckByte) {
		$CheckByte = 10 - $CheckByte;
		if (1 === ($Flag % 2) ) {
			if (1 === ($CheckByte % 2)) {
				$CheckByte += 9;
			}
			$CheckByte >>= 1;
		}
	}
	return '7'.$CheckByte.$HashStr;
}
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
					Google's Indexed Pages
   ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
function getGoogleIndexedPages($url){
	$query="http://ajax.googleapis.com/ajax/services/search/web?v=1.0&q=site:" . $url . "&filter=0&rsz=1";
	$data=getPageData($query);
	$data=json_decode($data,true);
	return isset($data['responseData']['cursor']['resultCount'])?$data['responseData']['cursor']['resultCount']:0;
}
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
					Google Backlinks
   ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
function getGoogleBackLinks($url){
	$query="http://ajax.googleapis.com/ajax/services/search/web?v=1.0&q=link:".$url."&filter=0&rsz=1";
	$data=$getPageData($query);
	$data=json_decode($data,true);
	return isset($data['responseData']['cursor']['resultCount'])?$data['responseData']['cursor']['resultCount']:0;
}
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
					SEMrush.com Detail
   ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
function getSEM($url){
	$query = 'http://us.backend.semrush.com/?action=report&type=domain_rank&domain='.$url;
	$data=getPageData($query);
	$data=json_decode($data,true);
	if(isset($data['rank']['data'][0]))
		return array('rank'=>$data['rank']['data'][0]['Rk'],'keywords'=>$data['rank']['data'][0]['Or'],'traffic'=>$data['rank']['data'][0]['Ot'],'cost'=>$data['rank']['data'][0]['Oc']);
	else
		return array('rank'=>'NA','keywords'=>'NA','traffic'=>'NA','cost'=>'NA');
}
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
					Alexa.com Detail
   ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
function getAlexa($url){
	$query="http://data.alexa.com/data?cli=10&dat=snbamz&url=".$url;
	$data=getPageData($query);
	$rank = preg_match("/<POPULARITY[^>]*TEXT=\"([\d]*)\"/",$data,$match)?$match[1]:0;
	$speed = preg_match("/<SPEED[^>]*TEXT=\"([\d]*)\"/",$data,$match)?$match[1]:0;
	$isdmoz=preg_match("/FLAGS=\"DMOZ\"/",$data,$match)?1:0;
	$links=preg_match("/<LINKSIN[^>]*NUM=\"([\d]*)\"/",$data,$match)?$match[1]:0;
	return array("rank"=>$rank,"dmoz"=>$isdmoz,"links"=>$links,"speed"=>$speed);
}

/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
					Twitter Tweets
   ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
function getTweets($url) {
	$json_string = getPageData('http://urls.api.twitter.com/1/urls/count.json?url=' . $url);
	$json = json_decode($json_string, true);
	return isset($json['count'])?intval($json['count']):0;
}
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
					Linkedin Shares
   ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
function getLinkedin($url) {
	$json_string = getPageData("http://www.linkedin.com/countserv/count/share?url=$url&format=json");
	$json = json_decode($json_string, true);
	return isset($json['count'])?intval($json['count']):0;
}
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
					FaceBook Shares+Likes+Comments
   ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
function getFacebookCount($url) {
	$json_string = getPageData('http://api.facebook.com/restserver.php?method=links.getStats&format=json&urls='.$url);
	$json = json_decode($json_string, true);
	return isset($json[0]['total_count'])?intval($json[0]['total_count']):0;
}
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
					Google +1s
   ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
function getPlusOnes($url)  {
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, "https://clients6.google.com/rpc");
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_POSTFIELDS, '[{"method":"pos.plusones.get","id":"p","params":{"nolog":true,"id":"'.rawurldecode($url).'","source":"widget","userId":"@viewer","groupId":"@self"},"jsonrpc":"2.0","key":"p","apiVersion":"v1"}]');
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
	$curl_results = curl_exec ($curl);
	curl_close ($curl);
	$json = json_decode($curl_results, true);
	return isset($json[0]['result']['metadata']['globalCounts']['count'])?intval( $json[0]['result']['metadata']['globalCounts']['count'] ):0;
}
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
					Stumbleupon views
   ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
function getStumbleViews($url) {
	$json_string = getPageData('http://www.stumbleupon.com/services/1.01/badge.getinfo?url='.$url);
	$json = json_decode($json_string, true);
	return isset($json['result']['views'])?intval($json['result']['views']):0;
}
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
					delicious bookmarks count
   ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
function getDeliciousBookmarks($url) {
	$json_string = getPageData('http://feeds.delicious.com/v2/json/urlinfo/data?url='.$url);
	$json = json_decode($json_string, true);
	return isset($json[0]['total_posts'])?intval($json[0]['total_posts']):0;
}
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
					pinterest pins
   ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
function getPinterestPins($url) {
	$return_data = getPageData('http://api.pinterest.com/v1/urls/count.json?url='.$url);
	$json_string = preg_replace('/^receiveCount\((.*)\)$/', "\\1", $return_data);
	$json = json_decode($json_string, true);
	return isset($json['count'])?intval($json['count']):0;
}


/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
					Public Functions
   ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
function getPageData($url) {
	if(function_exists('curl_init')) {
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		if((ini_get('open_basedir') == '') && (ini_get('safe_mode') == 'Off')) {
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		}
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
		curl_setopt($ch, CURLOPT_FAILONERROR, 1);
		return @curl_exec($ch);
	}
	else {
		return @file_get_contents($url);
	}
}
