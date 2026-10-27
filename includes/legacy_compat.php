<?php
/**
 * Narrow compatibility helpers for legacy phpBB 2 code paths on modern PHP.
 */
if (!function_exists('each')) {
	function each(&$array)
	{
		if (!is_array($array)) {
			return false;
		}

		$key = key($array);
		if ($key === null) {
			return false;
		}

		$value = current($array);
		next($array);
		return array(1 => $value, 'value' => $value, 0 => $key, 'key' => $key);
	}
}

if (!function_exists('get_magic_quotes_gpc')) {
	function get_magic_quotes_gpc()
	{
		return false;
	}
}

if (!function_exists('get_magic_quotes_runtime')) {
	function get_magic_quotes_runtime()
	{
		return false;
	}
}

if (!function_exists('set_magic_quotes_runtime')) {
	function set_magic_quotes_runtime($new_setting)
	{
		return false;
	}
}

// The old database abstraction uses ext/mysql fetch-mode constants with mysqli.
if (!defined('MYSQL_ASSOC')) {
	define('MYSQL_ASSOC', defined('MYSQLI_ASSOC') ? MYSQLI_ASSOC : 1);
}
if (!defined('MYSQL_NUM')) {
	define('MYSQL_NUM', defined('MYSQLI_NUM') ? MYSQLI_NUM : 2);
}
if (!defined('MYSQL_BOTH')) {
	define('MYSQL_BOTH', defined('MYSQLI_BOTH') ? MYSQLI_BOTH : 3);
}
