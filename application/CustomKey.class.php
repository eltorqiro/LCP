<?php

require_once('alphaID.lib.php');

class CustomKey {

	private static $startYear = 2011;
	private static $epoch = 1301470041;

	public static function generate($raw = false) {

		$time = substr($fileKey, 4);
		$fdate = ''.str_replace($time,"",$fileKey);
		$fdate = "20".substr($fdate, 0, 2)."/".substr($fdate, 2)."";

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

