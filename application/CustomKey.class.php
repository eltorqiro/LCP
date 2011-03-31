<?php

require_once('alphaID.lib.php');

class CustomKey {

	private static $startYear = 2011;
	private static $epoch = 1301470041;
	
	public static function generate($raw = false) {

		list($usec, $sec) = explode(' ', microtime());

		$date = getdate($sec);
		$year = $date['year'] - self::$startYear;
		$month = $date['month'] - 1;
		
		$sec -= self::$epoch;
		$usec = substr($usec, 2, 2);

		// seconds since custom epoch = variable number of bits
		$key = (int)$sec;

		// hundredths of a second 0-99 = 7 bits 
		$key <<= 7;
		$key += usec;
		
		// year 0-7 = 3 bits
		$key <<= 3;
		$key += $year;
		
		// month 0-11 = 4 bits
		$key <<= 4;
		$key += $month;
		
		$key = 86400;
		$r = alphaID($key);
		
		echo '<br />' . $key . '<br />';
		echo $r . '<br />';
		echo alphaID($r, true) . '<br />';

		echo '<br />' . microtime() . '<br />';
				
		//echo $key;
		//return $raw ? $key : self::encode($key);
	}
	
	/*
	 * encodes a key
	 */
	private static function encode($key) {
		return alphaID($key);
	}
	
	/*
	 * decodes a key
	 * returns array of elements
	 */
	public static function decode($encoded) {
		$key = alphaID($encoded, true);
		
		$yearmonth = substr($key, 0, 1);
		$year = ((int)$yearmonth & 15) + self::$startYear;
		$month = (int)$yearmonth >> 4;
		//$time = 
	}
	
}

