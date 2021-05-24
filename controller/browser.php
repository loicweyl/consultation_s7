<?php

function detect_os() {
	$user_agent = $_SERVER['HTTP_USER_AGENT'];
	$browser        = "Inconnu";
	$browser_array = array( '/mobile/i'    => 'Handheld Browser',
				'/msie/i'      => 'Internet Explorer',
				'/trident/i'   => 'Internet Explorer',
				'/firefox/i'   => 'Firefox',
				'/safari/i'    => 'Safari',
				'/chrome/i'    => 'Chrome',
				'/edge/i'      => 'Edge',
				'/opera/i'     => 'Opera',
				'/netscape/i'  => 'Netscape',
				'/maxthon/i'   => 'Maxthon',
				'/konqueror/i' => 'Konqueror'
	);
	foreach ($browser_array as $regex => $value)
	if (preg_match($regex, $user_agent))
		$browser = $value;
	return $browser;
}
