<?php
// twój adres IP
$twoje_ip = 'TU WPISZ SWOJE IP';
// wy³±cz sprawdzanie IP
$disable_ip_check = 0;
// maksymalny czas wykonywania skryptu w sekundach, 0 je¶li nieograniczony
$execution_time = 300;
// hash md5 has³a, wy³±czone je¶li puste
$pass_hash = ''; 

error_reporting  (E_ERROR | E_WARNING | E_PARSE);

define('IN_PHPBB', true);
$version = '1.0';
@ini_set('arg_separator.output', '&amp;');
ini_set('session.use_strict_mode', '1');
ini_set('session.cookie_httponly', '1');
ini_set('session.cookie_samesite', 'Lax');
ini_set('session.cookie_secure', (!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off') ? '1' : '0');
session_start();
if (empty($_SESSION['dbloader_csrf_token']))
{
	$_SESSION['dbloader_csrf_token'] = bin2hex(random_bytes(32));
}
$request_method = strtoupper(isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : '');
$post_token = (isset($_POST['csrf_token']) && is_string($_POST['csrf_token'])) ? $_POST['csrf_token'] : '';
$get_token = (isset($_GET['csrf_token']) && is_string($_GET['csrf_token'])) ? $_GET['csrf_token'] : '';
$csrf_valid = hash_equals($_SESSION['dbloader_csrf_token'], $request_method === 'POST' ? $post_token : $get_token);
if ($request_method === 'POST' && !$csrf_valid)
{
	http_response_code(403);
	exit('Invalid request token. Reload the page and try again.');
}
if( $pass_hash !== '' && isset($_POST['password']) )
{
	$password = is_scalar($_POST['password']) ? (string) $_POST['password'] : '';
	$password_is_valid = false;
	if (function_exists('password_verify') && preg_match('/^\$(2[aby]|argon2(id|i)?)\$/', $pass_hash))
	{
		$password_is_valid = password_verify($password, $pass_hash);
	}
	elseif (preg_match('/^[a-f0-9]{32}$/i', $pass_hash))
	{
		$password_is_valid = hash_equals(strtolower($pass_hash), md5($password));
	}
	if ($password_is_valid)
	{
		session_regenerate_id(true);
		$_SESSION['dbloader_auth_hash'] = hash('sha256', $pass_hash);
	}
	else
	{
		unset($_SESSION['dbloader_auth_hash']);
	}
}
$remote_ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
$password_matches = ($pass_hash !== '' && isset($_SESSION['dbloader_auth_hash']) && hash_equals(hash('sha256', $pass_hash), $_SESSION['dbloader_auth_hash']));
$has_access = $disable_ip_check
	? $password_matches
	: ($remote_ip === $twoje_ip && ($pass_hash === '' || $password_matches));
$ip_allowed = $disable_ip_check || $remote_ip === $twoje_ip;
if (!$ip_allowed)
{
	http_response_code(403);
	exit('Access denied.');
}
if (!$has_access)
{
	if ($pass_hash !== '')
	{
		$login_action = isset($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : '';
		header('Content-Type: text/html; charset=iso-8859-2');
		echo '<!DOCTYPE html><html><head><meta charset="iso-8859-2"><title>Logowanie do DumpLoader</title></head><body><form action="' . htmlspecialchars($login_action, ENT_QUOTES, 'ISO-8859-2') . '" method="post"><input type="hidden" name="csrf_token" value="' . htmlspecialchars($_SESSION['dbloader_csrf_token'], ENT_QUOTES, 'ISO-8859-2') . '" /><label>Haslo: <input type="password" name="password" /></label> <button type="submit">Zaloguj</button></form></body></html>';
	}
	else
	{
		http_response_code(403);
		exit('Access denied. Configure an IP allowlist or password to use dbloader.');
	}
	exit;
}
$phpbb_root_path = ( file_exists('extension.inc') ) ? './' : '../';

$img_path = $phpbb_root_path . 'dbloader/templates/images/';
include $phpbb_root_path.'extension.inc';
include_once $phpbb_root_path . 'includes/legacy_compat.'.$phpEx;
include_once 'functions.'.$phpEx;
include_once 'Timer.class.'.$phpEx;
include_once 'FileReader.class.'.$phpEx;
include_once 'SQLReader.class.'.$phpEx;

$is_limited = @ini_get('safe_mode') || preg_match('/lycos/', get_servername()) || !@ini_set('max_execution_time', 30);

$page_time = new Timer;
//
// Sprawd¼ config.php i po³±czenie z baz±
//
if( file_exists($phpbb_root_path . 'config.' . $phpEx) )
{
	$config_size_ok = ( filesize($phpbb_root_path . 'config.' . $phpEx) >= 177 ) ? true : false;
	$is_config = true;
}
else
{
	$config_size_ok = false;
	$is_config = false;
}
if( $config_size_ok ) // config w porz±dku, po³±cz z baz± danych
{
	include $phpbb_root_path . 'config.' . $phpEx;
	if (!isset($table_prefix) || !is_string($table_prefix) || !preg_match('/^[A-Za-z0-9_]{0,64}$/D', $table_prefix))
	{
		http_response_code(400);
		exit('Invalid table prefix in config.');
	}
    define('BEGIN_TRANSACTION', 1);
    define('END_TRANSACTION',   2);

    switch($dbms)
    {
        case 'mysql':
        case 'mysql4':
        case 'mysqli':
            $dbms = 'mysqli';
            include($phpbb_root_path . 'db/mysqli.'.$phpEx);
            break;
    }
	
    $db = new sql_db($dbhost, $dbuser, $dbpasswd, $dbname, false);
    $err = $db->sql_error(true);
    if($err['code']){
        $connect       = true;
        $db_error      = true;
        $not_supported = true;
    }else{
        $connect       = false;
    }
}
else
{
	$db_error = true;
}
include $phpbb_root_path.'includes/template_old.'.$phpEx;
//
// Koniec sprawdzania
//

$PHP_SELF = ($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : $_ENV['PHP_SELF']; 

$template = new Template('templates');
$template->set_filenames(array(
	'body' => 'dbloader_body.tpl')
);
$template->assign_vars(array(
	'CSRF_TOKEN' => htmlspecialchars($_SESSION['dbloader_csrf_token'], ENT_QUOTES, 'ISO-8859-2'),
	'VERSION' => $version,
	'PHP_EX' => $phpEx,
	'STYLE_IMAGES' => $img_path,
	'PHP_SELF' => $PHP_SELF)
);

if( !$has_access )
{
	if ($pass_hash !== '')
	{
		$template->assign_block_vars('login', array());
		$template->assign_vars(array(
			'TITLE' => 'Logowanie',
			'PAGE_GENTIME' => $page_time->elapsed())
		);
		$template->pparse('body');
	}
	else
	{
		http_response_code(403);
		exit('Access denied. Configure an IP allowlist or password to use dbloader.');
	}
	exit;
}
elseif( !$is_config )
{
	$template->assign_block_vars('info', array(
		'TITLE' => 'Brak pliku config.'.$phpEx,
		'CONTENT' => 'Brak pliku config.'.$phpEx.' w g³ównym katalogu forum. Aby kontynuowaæ pracê ze skryptem musisz go utworzyæ.')
	);
}
elseif( !$config_size_ok )
{
	$template->assign_block_vars('info', array(
		'TITLE' => 'Niepoprawny plik config.'.$phpEx,
		'CONTENT' => 'Plik config.'.$phpEx.' ma zbyt ma³y rozmiar by móg³ zostaæ uznany za poprawny. Aby kontynuowaæ pracê ze skryptem musisz go poprawiæ.')
	);
}
elseif( $db_error )
{
	$msg = 'Podczas próby po³±czenia z baz± danych nast±pi³ b³±d.<br />';
	$sql_error = $db->sql_error($connect);
	$msg .= ' &nbsp; Error code: ' . $sql_error['code'] . '<br />';
	$msg .= ' &nbsp; Error message: ' . htmlspecialchars((string) $sql_error['message'], ENT_QUOTES, 'ISO-8859-2');
	$template->assign_block_vars('info', array(
		'TITLE' => 'B³±d bazy danych',
		'CONTENT' => $msg)
	);
}

if( $is_config && !$db_error )
{
	$result = $db->sql_query("SHOW TABLES LIKE '" . escape_str($table_prefix) . "config'");
	$cfg_table_ex = ( $db->sql_numrows($result) == 1 ) ? true : false;
	$tpl_sect = ($cfg_table_ex ? '' : 'no_') . 'forum_config_link';
	$template->assign_block_vars($tpl_sect, array());
	$template->assign_block_vars('db_actions', array());
}

$csrf_input = '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($_SESSION['dbloader_csrf_token'], ENT_QUOTES, 'ISO-8859-2') . '" />';
$csrf_query = 'csrf_token=' . rawurlencode($_SESSION['dbloader_csrf_token']);
$mode_get = (isset($_GET['mode']) && is_string($_GET['mode'])) ? $_GET['mode'] : '';
$mode = ( $has_access ) ? $mode_get : '';
$unsafe_get = ($mode === 'checkdb' && (isset($_GET['create']) || isset($_GET['config_update'])))
	|| ($mode === 'dbread' && isset($_GET['step']) && $_GET['step'] === 'go')
	|| isset($_GET['droptables']);
if ($request_method === 'GET' && $unsafe_get && !$csrf_valid)
{
	http_response_code(403);
	exit('Invalid request token. Reload the page and try again.');
}
if ( $mode == 'makeconfig' )
{
	if( isset($_POST['submit']) || isset($_POST['download_config']) )
	{
		$dbms = (isset($_POST['dbms']) && is_string($_POST['dbms']) && in_array($_POST['dbms'], array('mysql', 'mysql4', 'mysqli'), true)) ? $_POST['dbms'] : 'mysqli';
		$dbhost = (isset($_POST['dbhost']) && is_scalar($_POST['dbhost'])) ? (string) $_POST['dbhost'] : '';
		$dbname = (isset($_POST['dbname']) && is_scalar($_POST['dbname'])) ? (string) $_POST['dbname'] : '';
		$dbuser = (isset($_POST['dbuser']) && is_scalar($_POST['dbuser'])) ? (string) $_POST['dbuser'] : '';
		$dbpasswd = (isset($_POST['dbpasswd']) && is_scalar($_POST['dbpasswd'])) ? (string) $_POST['dbpasswd'] : '';
		$table_prefix = (isset($_POST['table_prefix']) && is_string($_POST['table_prefix']) && preg_match('/^[A-Za-z0-9_]{0,64}$/D', $_POST['table_prefix'])) ? $_POST['table_prefix'] : '';
	}

	$config_save = false;
	$save_result = '';
	if( isset($_POST['download_config']) && $_POST['download_config'] == true && isset($_POST['submit_download_config']) && $_POST['submit_download_config'] == '¦ci±gnij plik' )
	{
		header('Pragma: no-cache');
		header('Content-Type: text/x-delimtext; name="config.php"');
		header('Content-disposition: attachment; filename=config.php');
		echo make_download($dbms, $dbhost, $dbname, $dbuser, $dbpasswd, $table_prefix);
		return;
	}
	elseif( isset($_POST['download_config']) && $_POST['download_config'] == true && isset($_POST['submit_save_config']) && $_POST['submit_save_config'] == 'Spróbuj zapisaæ' )
	{
		$config_save = true;
		$config_fname = $phpbb_root_path . 'config.' . $phpEx;
		if( $fh = fopen($config_fname, 'w+') )
		{
			fwrite($fh, make_download($dbms, $dbhost, $dbname, $dbuser, $dbpasswd, $table_prefix));
			fclose($fh);
			$save_result = '<br /><br /><span style="color:green"><b>Zapisano plik '.$config_fname.'</b></span>. Aby nowa konfiguracja zaczê³a dzia³aæ od¶wie¿ stronê.';
		}
		else
		{
			$save_result = '<br /><br /><span style="color:red">Nie zapisano pliku '.$config_fname.'! Powodem mog± byæ niewystarczaj±ce prawa dostêpu do pliku. Wymagany chmod to 666.</span>';
		}
	}
	$available_dbms = array(
		'mysql' => 'MySQL 3.x',
		'mysql4' => 'MySQL 4.x',
		'mysqli' => 'MySQLi',
	);

	$dbms_options = '';
	foreach ((array) ($available_dbms) as $var => $param)
	{
		$selected = ($dbms == $var) ? ' selected="selected"' : '';
		$dbms_options .= '<option value="' . $var . '"' . $selected . '>' . $param . '</option>';
	}

	$template->assign_vars(array(
		'TITLE' => 'Tworzenie / edycja pliku config.'.$phpEx)
	);

	$template->assign_block_vars('config', array(
		'DBMS_OPTIONS' => $dbms_options,
		'DBMS' => htmlspecialchars((string) @$dbms, ENT_QUOTES, 'ISO-8859-2'),
		'DBHOST' => htmlspecialchars((string) @$dbhost, ENT_QUOTES, 'ISO-8859-2'),
		'DBNAME' => htmlspecialchars((string) @$dbname, ENT_QUOTES, 'ISO-8859-2'),
		'DBUSER' => htmlspecialchars((string) @$dbuser, ENT_QUOTES, 'ISO-8859-2'),
		'DBPASSWORD' => htmlspecialchars((string) @$dbpasswd, ENT_QUOTES, 'ISO-8859-2'),
		'TABLE_PREFIX' => htmlspecialchars((string) @$table_prefix, ENT_QUOTES, 'ISO-8859-2'))
	);
	if( (isset($_POST['generate_config']) && $_POST['generate_config'] == true) || $config_save )
	{
		$ccode = make_download($dbms, $dbhost, $dbname, $dbuser, $dbpasswd, $table_prefix);
		$ccode = highlight_string($ccode, true);
		$template->assign_block_vars('config.code', array(
			'CODE' => $ccode,
			'SAVE_RESULT' => $save_result)
		);
	}
	$template->assign_var('PAGE_GENTIME', $page_time->elapsed());
	$template->pparse('body');
}
elseif( $mode == 'forumconfig' )
{
	$template->assign_vars(array(
		'TITLE' => 'Konfiguracja forum')
	);
	if( !$config_size_ok || $db_error)
	{
		message_die('', true, true);
	}
	// Odgadnij dane (install.php)...
	$server_name = get_servername();
	$script_path = preg_replace('/(|dbloader\/)dbloader\.php/i', '', $PHP_SELF);
	if ( !empty($HTTP_SERVER_VARS['SERVER_PORT']) || !empty($HTTP_ENV_VARS['SERVER_PORT']) )
	{
		$server_port = ( !empty($HTTP_SERVER_VARS['SERVER_PORT']) ) ? $HTTP_SERVER_VARS['SERVER_PORT'] : $HTTP_ENV_VARS['SERVER_PORT'];
	}
	else
	{
		$server_port = '80';
	}
	$check_address = true;
	$cookie_domain = $server_name;
	$cookie_path = '/';
	$cookie_secure = false;
	// Je¶li wys³ano forumularz, zapisz konfiguracjê do bazy
	if( isset($_POST['save_config']) && $_POST['save_config'] == 'true' )
	{
		$config_fields = array('server_name', 'server_port', 'script_path', 'check_address', 'cookie_domain', 'cookie_name', 'cookie_path', 'cookie_secure');
		$new_cfg = array();
		foreach ($config_fields as $config_field)
		{
			$new_cfg[$config_field] = (isset($_POST[$config_field]) && is_scalar($_POST[$config_field])) ? (string) $_POST[$config_field] : '';
		}
		$q_error = '';
		foreach( $new_cfg as $cfg_name => $cfg_value )
		{
			$sql = "UPDATE `" . $table_prefix . "config` SET config_value = '" . escape_str($cfg_value) . "' WHERE config_name = '" . escape_str($cfg_name) . "' LIMIT 1";
			if( !$db->sql_query($sql) )
			{
				$err = $db->sql_error();
				$q_error .= '<span class="color:red">SQL: ' . htmlspecialchars($sql, ENT_QUOTES, 'ISO-8859-2') . ' Code: ' . htmlspecialchars((string) $err['code'], ENT_QUOTES, 'ISO-8859-2') . '<br />Message: ' . htmlspecialchars($err['message'], ENT_QUOTES, 'ISO-8859-2') . '<br /><br />';
			}
		}
		@unlink($phpbb_root_path . 'cache/board_config.'.$phpEx);
	}
	// Wczytaj dane z bazy
	$db->sql_query("SELECT * FROM " . $table_prefix . "config");
	$bb_cfg = $db->sql_fetchrowset();
	$bb_cfg = config_assoc($bb_cfg, 'config_name', 'config_value');
	foreach ($bb_cfg as $cfg_name => $cfg_value)
	{
		$bb_cfg[$cfg_name] = htmlspecialchars((string) $cfg_value, ENT_QUOTES, 'ISO-8859-2');
	}

	$template->assign_block_vars('forumconfig', array(
		'SERVER_NAME' => htmlspecialchars((string) $server_name, ENT_QUOTES, 'ISO-8859-2'),
		'SERVER_PORT' => htmlspecialchars((string) $server_port, ENT_QUOTES, 'ISO-8859-2'),
		'SCRIPT_PATH' => htmlspecialchars((string) $script_path, ENT_QUOTES, 'ISO-8859-2'),
		'CHECK_ADDRESS_0' => ($check_address ? '' : 'checked="checked"'),
		'CHECK_ADDRESS_1' => ($check_address ? 'checked="checked"' : ''),
		'COOKIE_DOMAIN' => htmlspecialchars((string) $cookie_domain, ENT_QUOTES, 'ISO-8859-2'),
		'COOKIE_NAME' => $bb_cfg['cookie_name'],
		'COOKIE_PATH' => htmlspecialchars((string) $cookie_path, ENT_QUOTES, 'ISO-8859-2'),
		'COOKIE_SECURE_0' => ($cookie_secure ? '' : 'checked="checked"'),
		'COOKIE_SECURE_1' => ($cookie_secure ? 'checked="checked"' : ''),
		'SERVER_NAME_DB' => $bb_cfg['server_name'],
		'SERVER_PORT_DB' => $bb_cfg['server_port'],
		'SCRIPT_PATH_DB' => $bb_cfg['script_path'],
		'CHECK_ADDRESS_DB' => ($bb_cfg['check_address'] ? 'Tak' : 'Nie'),
		'COOKIE_DOMAIN_DB' => $bb_cfg['cookie_domain'],
		'COOKIE_NAME_DB' => $bb_cfg['cookie_name'],
		'COOKIE_PATH_DB' => $bb_cfg['cookie_path'],
		'COOKIE_SECURE_DB' => ($bb_cfg['cookie_secure'] ? 'Tak' : 'Nie'))
	);
	if( isset($q_error) )
	{
		$template->assign_block_vars('forumconfig.result', array(
			'RESULT' => ($q_error != '' ? $q_error : '<span style="color:green">Zapisano konfiguracjê</span>'))
		);
	}
	$template->assign_var('PAGE_GENTIME', $page_time->elapsed());
	$template->pparse('body');
}
elseif( $mode == 'checkdb' )
{
	$template->assign_vars(array(
		'TITLE' => 'Sprawdzanie zgodno¶ci bazy danych z phpBB modified by Przemo')
	);
	if( !$config_size_ok || $db_error)
	{
		message_die('', true, true);
	}
	include 'dbdata.inc.php'; // $dbs, $dbs_for
	$dbs = append_prefix($table_prefix, $dbs);
	$create_table_request = (isset($_POST['create']) && is_string($_POST['create'])) ? $_POST['create'] : '';
if ($create_table_request !== '')
{
	foreach ($dbs as $dbs_item)
	{
		if (isset($dbs_item['table']) && $dbs_item['table'] === $create_table_request && isset($dbs_item['create']))
		{
			$cr_query = nl2br($dbs_item['create']);
			if ($db->sql_query($dbs_item['create']))
			{
				$cr_res = '<span style="color:green">Table created.</span>';
			}
			else
			{
				$error = $db->sql_error();
				$cr_res = '<span style="color:red">Table was not created.<br />Code: ' . htmlspecialchars((string) $error['code'], ENT_QUOTES, 'ISO-8859-2') . '<br />Message: ' . htmlspecialchars((string) $error['message'], ENT_QUOTES, 'ISO-8859-2') . '</span>';
			}
		}
	}
}
$config_update_request = (isset($_POST['config_update']) && is_string($_POST['config_update'])) ? $_POST['config_update'] : '';
if ($config_update_request === 'text')
{
	$db->sql_query("ALTER TABLE " . $table_prefix . "config CHANGE config_value config_value TEXT NOT NULL");
}
$tables_check = '';
	$tbls_in_db = tables_in_db();
	foreach($dbs as $dbs_item)
	{
		$table_name = !is_array($dbs_item) ? $dbs_item : $dbs_item['table'];
		$verbose = isset($dbs_item['verbose']) ? $dbs_item['verbose'] : true;
		$table_exists = in_array($table_name, $tbls_in_db);
		if( $verbose && !$table_exists )
		{
			$tables_check .= '<span style="color:red"><b>brak tabeli</b> ' . $table_name . '</span> <img src="'.$img_path.'icon_mini_ignore.gif" alt="B³±d">';
			if( isset($dbs_item['create']) )
			{
				$tables_check .= ' <form action="' . htmlspecialchars($PHP_SELF, ENT_QUOTES, 'ISO-8859-2') . '?mode=checkdb" method="post">' . $csrf_input . '<input type="hidden" name="create" value="' . htmlspecialchars($table_name, ENT_QUOTES, 'ISO-8859-2') . '" /><button type="submit">Create</button></form>';
			}
			$tables_check .= '<br />';
		}
		if( $table_exists && $table_name != $table_prefix.'sessions' )
		{
			$db->sql_query("CHECK TABLE `$table_name`");
			$ret = $db->sql_fetchrow();
			$error = $ret['Msg_text'] != 'OK';
			$tables_check .= ( $error ) ? '<span style="color:red"><b>tabela uszkodzona</b> ' . $table_name . '</span> <img src="'.$img_path.'icon_mini_ignore.gif" alt="B³±d"><br />' : '';
			if( !$error && isset($dbs_item['fields']) )
			{
				$db->sql_query("SHOW COLUMNS FROM `$table_name`");
				$numrows = $db->sql_numrows();
				if( $numrows < $dbs_item['fields'] )
				{
					$tables_check .= '<span style="color:red"><b>tabela </b> ' . $table_name . ' zawiera za ma³o pól ('.$numrows.' zamiast '.$dbs_item['fields'].')</span> <img src="'.$img_path.'icon_mini_ignore.gif" alt="B³±d"><br />';
				}
			}
			if( !$error && $table_name == $table_prefix . 'config' )
			{
				$db->sql_query("DESCRIBE `$table_name` config_value");
				$ret = $db->sql_fetchrow();
				if ( $ret['Type'] != 'text' )
				{
					$tables_check .= '<span style="color:red"><b>tabela </b> ' . $table_name . ' ma niepoprawny typ kolumny config_value</span> <img src="'.$img_path.'icon_mini_ignore.gif" alt="B³±d">';
					$tables_check .= ' <form action="' . htmlspecialchars($PHP_SELF, ENT_QUOTES, 'ISO-8859-2') . '?mode=checkdb" method="post">' . $csrf_input . '<input type="hidden" name="config_update" value="text" /><button type="submit">Repair</button></form><br />';
				}
			}
		}
	}
	$tables_check = ( $tables_check == '' ) ? '<span style="color:green">Wszystkie tabele (' . count($dbs) . ') istniej± i nie zawieraj± b³êdów</span> <img src="'.$img_path.'icon_mini_register.gif" alt="Ok"><br />' : $tables_check ;
	$template->assign_block_vars('db_check', array(
		'DB' => htmlspecialchars($dbuser.'.'.$dbname.' @ '.$dbhost, ENT_QUOTES, 'ISO-8859-2'),
		'TABES_CHECK' => $tables_check,
		'TABLES_DEF_FOR' => $dbs_for)
	);
	if ($create_table_request !== '')
{
	$template->assign_block_vars('db_check.create', array(
		'SQL' => isset($cr_query) ? nl2br(htmlspecialchars($dbs_item['create'], ENT_QUOTES, 'ISO-8859-2')) : '',
		'RESULT' => isset($cr_res) ? $cr_res : '')
	);
}

$template->assign_var('PAGE_GENTIME', $page_time->elapsed());
	$template->pparse('body');
}
elseif( $mode == 'sqllist' )
{
	$template->assign_var('TITLE', 'Wczytywanie zrzutu bazy danych: wybierz plik');
	if( !$config_size_ok || $db_error)
	{
		message_die('', true, true);
	}


	$tmp_list = scan_dir($phpbb_root_path, 1);
	$alist = array();
	for( $i = 0; $i < count($tmp_list); $i++ )
	{
		if( substr($tmp_list[$i], 0, 8) != 'scripts/' && !stristr($tmp_list[$i],'admin_logs') )
		{
			$alist[] = $tmp_list[$i];
		}
	}
	$template->assign_block_vars('sqllist', array());
	if( count($alist) > 0 )
	{
		for($i = 0; $i < count($alist); $i++)
		{
			$template->assign_block_vars('sqllist.item', array(
				'ROW_STYLE' => ($i % 2 ? '2' : '3'),
				'LINK' => '?mode=dbread&amp;step=prepare&amp;file='.urlencode(str_replace("..", "kat_wyzej", $phpbb_root_path).$alist[$i]),
				'TEXT' => htmlspecialchars($alist[$i], ENT_QUOTES, 'ISO-8859-2'),
				'SIZE' => round(filesize($phpbb_root_path.$alist[$i]) / 1024, 2))
			);
		}
	}
	else
	{
		$template->assign_block_vars('sqllist.no_items', array(
			'MSG' => 'Brak plików w przeszukanych katalogach')
		);
	}
	$template->assign_var('PAGE_GENTIME', $page_time->elapsed());
	$template->pparse('body');
}
elseif( $mode == 'dbread' )
{
	// $step = ('prepare', 'go', 'completed', 'error_notify',
	//     'error_notify_continue', 'break', 'show_errors')
	if( !$config_size_ok || $db_error)
	{
		$template->assign_vars(array(
			'TITLE' => 'Wczytywanie zrzutu bazy danych...')
		);
		message_die('', true, true);
	}
	$db->sql_query("SELECT * FROM _dbloader");
	$tbl_exists = ($db->sql_affectedrows() > 0 ) ? true : false;
	if( $tbl_exists && !isset($_POST['max_queries']) )
	{
		$rt_data = $db->sql_fetchrowset();
		$rt_data = config_assoc($rt_data, 'config', 'value');
	}
	else
	{
		$rt_data['max_queries'] = (isset($_POST['max_queries']) && is_scalar($_POST['max_queries'])) ? max(0, intval($_POST['max_queries'])) : 0;
		$file_request = (isset($_GET['file']) && is_string($_GET['file'])) ? $_GET['file'] : '';
		$rt_data['file_name'] = ($file_request !== '') ? str_replace("kat_wyzej", "..", $file_request) : '';
	}
	$requested_file = (isset($rt_data['file_name']) && is_string($rt_data['file_name'])) ? $rt_data['file_name'] : '';
	$resolved_file = dbloader_resolve_sql_file($requested_file, $phpbb_root_path);
	$rt_data['file_name'] = ($resolved_file !== false) ? $resolved_file : '';
	if( $rt_data['file_name'] == '' )
	{
		$msg = 'The selected file does not exist or is not an allowed SQL backup.';
		$template->assign_vars(array(
			'TITLE' => 'Wczytywanie zrzutu bazy danych...',
			'CONTENT' => $msg,
			'PAGE_GENTIME' => $page_time->elapsed())
		);
		$template->pparse('body');
		exit;
	}
	$step = (isset($_GET['step']) && is_string($_GET['step'])) ? $_GET['step'] : '';
	$allowed_steps = array('prepare', 'go', 'completed', 'error_notify',
		'error_notify_continue', 'break', 'show_errors');
	if( !in_array($step, $allowed_steps) )
	{
		$msg = "Z³e wywo³anie skryptu.";
		$template->assign_vars(array(
			'TITLE' => 'Wczytywanie zrzutu bazy danych...',
			'CONTENT' => $msg,
			'PAGE_GENTIME' => $page_time->elapsed())
		);
		$template->pparse('body');
		exit;
	}
	//
	// prepare
	//
	if( $step == 'prepare' )
	{
		$template->assign_block_vars('dbread', array(
			'DB' => htmlspecialchars($dbuser.'.'.$dbname.' @ '.$dbhost, ENT_QUOTES, 'ISO-8859-2'),
			'SQL_FILE' => htmlspecialchars((string) $rt_data['file_name'], ENT_QUOTES, 'ISO-8859-2'))
		);
		if( $rt_data['max_queries'] > 0 )
		{
			$fr = new FileReader($rt_data['file_name']);
			$file_size = $fr->realsize();
			$fr->close();

			$tables = tables_in_db();
			$tables[] = '_dbloader';
			$tables[] = '_dbloader_err';
			$tables = escape_str(serialize($tables));
			$db->sql_query("DROP TABLE IF EXISTS _dbloader");
			$db->sql_query("DROP TABLE IF EXISTS _dbloader_err");
			if (isset($result)) { $db->sql_freeresult($result); }
			// Wpisz zmienne do bazy danych, bêd± ³adowane do tablicy $rt_cfg
			$queries = array(
"CREATE TABLE IF NOT EXISTS `_dbloader` (
`config` VARCHAR( 255 ) NOT NULL ,
`value` TEXT NOT NULL ) DEFAULT CHARSET latin2 COLLATE latin2_general_ci",
"TRUNCATE TABLE `_dbloader`",
"CREATE TABLE `_dbloader_err` (
`id` SMALLINT NOT NULL AUTO_INCREMENT ,
`pos` INT NOT NULL ,
`code` SMALLINT NOT NULL ,
`message` TEXT NOT NULL ,
PRIMARY KEY ( `id` ) ) DEFAULT CHARSET latin2 COLLATE latin2_general_ci",
"TRUNCATE TABLE `_dbloader_err`",
"INSERT INTO `_dbloader` VALUES ('max_queries', '".$rt_data['max_queries']."')",
"INSERT INTO `_dbloader` VALUES ('omit_search', '".(!empty($_POST['omit_search']) ? 1 : 0)."')",
"INSERT INTO `_dbloader` VALUES ('loaded_queries', '0')",
"INSERT INTO `_dbloader` VALUES ('omitted_queries', '0')",
"INSERT INTO `_dbloader` VALUES ('file_name', '".escape_str($rt_data['file_name'])."')",
"INSERT INTO `_dbloader` VALUES ('file_size', '$file_size')",
"INSERT INTO `_dbloader` VALUES ('offset', '0')",
"INSERT INTO `_dbloader` VALUES ('php_time', '0')",
"INSERT INTO `_dbloader` VALUES ('sql_time', '0')",
"INSERT INTO `_dbloader` VALUES ('errors', '0')",
"INSERT INTO `_dbloader` VALUES ('max_errors', '1')",
"INSERT INTO `_dbloader` VALUES ('alt_engine', '".(!empty($_POST['alt_engine']) ? 1 : 0)."')",
"INSERT INTO `_dbloader` VALUES ('lock_tables', '".(!empty($_POST['lock_tables']) ? 1 : 0)."')",
"INSERT INTO `_dbloader` VALUES ('last_lock', '')",
"INSERT INTO `_dbloader` VALUES ('tables', '$tables')",
"INSERT INTO `_dbloader` VALUES ('completed', '0')");
			foreach($queries as $query)
			{
				$db->sql_query($query);
			}
			$rt_data['file_size'] = $file_size;
			echo generate_progress($rt_data, 'Rozpoczynanie wczytywania...');
			echo '<meta http-equiv="refresh" content="0;url=' . htmlspecialchars($PHP_SELF, ENT_QUOTES, 'ISO-8859-2') . '?mode=dbread&amp;step=go&amp;' . $csrf_query . '"></body></html>';
			exit;
		}
		else
		{
			$template->assign_block_vars('dbread.form', array(
				'FORM_ACTION' => $PHP_SELF.'?mode=dbread&step=prepare&file='.urlencode(str_replace("..", "kat_wyzej", $rt_data['file_name'])),
				'MAX_QUERIES_DEF' => ($is_limited ? 100 : 1000) )
			);
		}
		$template->assign_vars(array(
			'TITLE' => 'Wczytywanie zrzutu bazy danych...',
			'PAGE_GENTIME' => $page_time->elapsed())
		);
		$template->pparse('body');
	}
	//
	// go
	//
	elseif( $step == 'go')
	{
		// ustaw limit czasu wykonywania skryptu, nie dzia³a w Safe Mode
		@set_time_limit($execution_time);

		$fr = new FileReader($rt_data['file_name'], $rt_data['file_size']);
		$fr->seek($rt_data['offset']);
		$reader_engine = ( $rt_data['alt_engine'] ) ? SQL_READER_FAST : SQL_READER_PMA;
		$sqlreader = new SQLReader($fr, $reader_engine);

		$gentime = array();
		$gentime['php'] = new Timer(true);
		$gentime['sql'] = new Timer(false);
		$errors = array();
		$locked = array();
		$i = 0;
		$time0 = time() + 30;
		$error_try = ($reader_engine == SQL_READER_FAST);
		if( $rt_data['lock_tables'] && $rt_data['last_lock'] != '' )
		{
			$db->sql_query("LOCK TABLES `" . escape_str($rt_data['last_lock']) . "` WRITE");
			$locked[] = $rt_data['last_lock'];
		}
		while( $i <= $rt_data['max_queries'] && is_array($query = $sqlreader->get_query()) )
		{
			$i++;
			// rozbij kawa³ek zapytania
			$tokens = explode(' ', str_replace(array("\n", "\r"), array(' ', ''), substr($query['query'], 0, 64)));
			// je¶li INSERT i co¶ pomijamy lub blokujemy...
			if( ($rt_data['omit_search'] || $rt_data['lock_tables'])
				&& $tokens[0] == 'INSERT' )
			{
				// pomijanie search
				if( $rt_data['omit_search'] )
				{
					$matches = preg_match('/(search_(results|wordlist|wordmatch))/i', $tokens[2]);
					if($matches > 0)
					{
						$rt_data['omitted_queries']++;
						continue;
					}
				}
				// blokowanie tabel
				if( $rt_data['lock_tables'] )
				{
					if( !in_array($tokens[2], $locked) )
					{
						$locked[] = $tokens[2];
						$db->sql_query("LOCK TABLES `" . escape_str($tokens[2]) . "` WRITE");
					}
				}
			}
			// sprawd¼ czy zapytanie to LOCK TABLES
			if( $rt_data['lock_tables'] && $tokens[0] == 'LOCK'
				&& !in_array($tokens[2], $locked) )
			{
					$locked[] = $tokens[2];
			}
			$rt_data['loaded_queries']++;
			$gentime['php']->stop();
			$gentime['sql']->start();
			// wykonaj zapytanie
			if( !$db->sql_query($query['query']) )
			{
				// b³±d...
				$is_error = true;
				$err = $db->sql_error();

				if( $err['code'] == 1100 ) //Table ? was not locked with LOCK TABLES
				{
					$db->sql_query('UNLOCK TABLES');
					$is_error = $db->sql_query($query['query']) ? false : true;
				}
				elseif( $error_try )
				{
					$fr->seek($query['pos']);
					$sqlreader->set_method(SQL_READER_PMA);
					$query = $sqlreader->get_query();
					$sqlreader->set_method(SQL_READER_FAST);
					if( is_array($query) && $db->sql_query($query['query']) )
					{
						$is_error = false;
					}
				}
				if( $is_error )
				{
					$gentime['sql']->stop();
					$rt_data['errors']++;
					$err = $db->sql_error();
					$c = count($errors);
					$errors[$c]['pos'] = $query['pos'];
					$errors[$c]['code'] = $err['code'];
					$errors[$c]['msg'] = $err['message'];
					if( $c + 1 == $rt_data['max_errors'] )
					{
						break;
					}
				}
			}
			$gentime['sql']->stop();
			$time1 = time();
			if( $time1 >= $time0 )
			{
				$time0 = $time1 + 30;
				header('X-dblPing: Pong');
			}
			$gentime['php']->start();
		}
		// je¶li jakie¶ tabele zablokowane, odblokuj
		if( count($locked) > 0 )
		{
			$db->sql_query('UNLOCK TABLES');
		}
		$gentime['php']->stop();
		$rt_data['offset'] = $fr->tell();
		$fr->close();
		$do_redir = ( $query != false );

		echo generate_progress($rt_data,
			($do_redir
			? substr($query['query'], 0, 300)
			: 'Zakoñczono wczytywanie' )
			);
		flush();

		$rt_data['php_time'] += $gentime['php']->elapsed();
		$rt_data['sql_time'] += $gentime['sql']->elapsed();

		$queries = array(
"UPDATE _dbloader SET value = '" . $rt_data['offset'] . "' WHERE config = 'offset' LIMIT 1",
"UPDATE _dbloader SET value = '" . $rt_data['loaded_queries'] . "' WHERE config = 'loaded_queries' LIMIT 1",
"UPDATE _dbloader SET value = '" . $rt_data['omitted_queries'] . "' WHERE config = 'omitted_queries' LIMIT 1",
"UPDATE _dbloader SET value = '" . $rt_data['php_time'] . "' WHERE config = 'php_time' LIMIT 1",
"UPDATE _dbloader SET value = '" . $rt_data['sql_time'] . "' WHERE config = 'sql_time' LIMIT 1",
"UPDATE _dbloader SET value = '" . $rt_data['errors'] . "' WHERE config = 'errors' LIMIT 1",
"UPDATE _dbloader SET value = '" . ($rt_data['lock_tables'] ? escape_str(strval(array_pop($locked))) : '') . "' WHERE config = 'last_lock' LIMIT 1");
		if( !$do_redir )
		{
			$queries[] = "UPDATE _dbloader SET value = '1' WHERE config = 'completed' LIMIT 1";
		}
		for($i = 0; $i < count($errors); $i++)
		{
			$queries[] = "
INSERT INTO `_dbloader_err` ( pos , code , message ) 
VALUES ( '".$errors[$i]['pos']."', '".$errors[$i]['code']."', '".escape_str($errors[$i]['msg'])."')";
		}
		$save_error = false;
		foreach($queries as $query)
		{
			if( !$db->sql_query($query) )
			{
				$err = $db->sql_error();
				echo 'B³±d zapisu konfiguracji ('.$err['code'].': '.xhtmlspecialchars($err['message']).')<br />'.xhtmlspecialchars($query).'<hr />';
				$save_error = true;
			}
		}
		if( $rt_data['errors'] >= $rt_data['max_errors'] )
		{
			$redir = $PHP_SELF.'?mode=dbread&step=error_notify&' . $csrf_query;
		}
		elseif( $do_redir )
		{
			$redir = $PHP_SELF.'?mode=dbread&step=go&' . $csrf_query;
		}
		else
		{
			$redir = $PHP_SELF.'?mode=dbread&step=completed&' . $csrf_query;
		}
		if( $save_error )
		{
			echo '<br /><br /><a href="' . $redir . '">Kontynuuj</a>';
			exit;
		}
		echo '<meta http-equiv="refresh" content="0;url=' . $redir . '"></body></html>';
	}
	//
	// error_notify
	//
	elseif( $step == 'error_notify' )
	{
		echo generate_error_notify($rt_data, true);
	}
	//
	// error_notify_continue
	//
	elseif( $step == 'error_notify_continue' )
	{
		$redir = $PHP_SELF;
		if( isset($_POST['continue']) )
		{
			$db->sql_query("UPDATE _dbloader SET value = '" . ($rt_data['max_errors'] + $rt_data['max_queries']) . "' WHERE config = 'max_errors' LIMIT 1");
			$alt_engine = ($_POST['new_engine'] == 'std') ? 0 : 1;
			$db->sql_query("UPDATE _dbloader SET value = "
				. $alt_engine . " WHERE config = 'alt_engine' LIMIT 1");
			$max_queries = intval($_POST['new_max_queries']);
			$max_queries = ( $max_queries < 2 ) ? 2 : $max_queries;
			$db->sql_query("UPDATE _dbloader SET value = "
				. $max_queries . " WHERE config = 'alt_engine' LIMIT 1");
			$redir = "$PHP_SELF?mode=dbread&step=go&" . $csrf_query;
		}
		elseif( isset($_POST['break']) )
		{
			if( $rt_data['tables'] != '' && $_POST['delete_new_tables'] == 'on' )
			{
				$created_tables = phpbb_safe_unserialize($rt_data['tables']);
				$created_tables = is_array($created_tables) ? $created_tables : array();
				$del_tables = array_diff(tables_in_db(), $created_tables);
				foreach( $del_tables as $table )
				{
					$db->sql_query("DROP TABLE IF EXISTS `" . escape_str($table) . "`");
				}
			}
			$redir = "$PHP_SELF?mode=main&droptables=1&" . $csrf_query;
		}
		header('HTTP/1.1 301');
		header("Location: $redir");
		exit;
	}
	//
	// completed
	//
	elseif( $step == 'completed' )
	{
		$template->assign_block_vars('dbread', array(
			'DB' => htmlspecialchars($dbuser.'.'.$dbname.' @ '.$dbhost, ENT_QUOTES, 'ISO-8859-2'),
			'SQL_FILE' => htmlspecialchars((string) $rt_data['file_name'], ENT_QUOTES, 'ISO-8859-2'))
		);

		$php_time = $rt_data['php_time'];
		$sql_time = $rt_data['sql_time'];
		$time_total = $rt_data['php_time'] + $rt_data['sql_time'];

		$template->assign_block_vars('dbread.completed', array(
			'QUERIES_LOADED' => $rt_data['loaded_queries'],
			'QUERIES_OMITTED' => $rt_data['omitted_queries'],
			'QUERIES_MAX' => $rt_data['max_queries'],
			'ERRORS_MAX' => $rt_data['max_errors'],
			'LOCK_TABLES' => $rt_data['lock_tables'] ? 'tak' : 'nie',
			'TIME_PHP' => human_time($php_time),
			'TIME_SQL' => human_time($sql_time),
			'TIME_TOTAL' => human_time($time_total) )
		);
		if( $rt_data['errors'] > 0 )
		{
			$template->assign_block_vars('dbread.completed.errors', array(
				'ERRORS' => $rt_data['errors'])
			); 
		}
		$template->assign_vars(array(
			'TITLE' => 'Wczytywanie zrzutu bazy danych - podsumowanie',
			'PAGE_GENTIME' => $page_time->elapsed())
		);
		$template->pparse('body');
	}
	//
	// show_errors
	//
	elseif( $step == 'show_errors' )
	{
		// ustaw limit czasu wykonywania skryptu, nie dzia³a w Safe Mode
		@set_time_limit($execution_time);

		$template->assign_block_vars('dbread', array(
			'DB' => htmlspecialchars($dbuser.'.'.$dbname.' @ '.$dbhost, ENT_QUOTES, 'ISO-8859-2'),
			'SQL_FILE' => htmlspecialchars((string) $rt_data['file_name'], ENT_QUOTES, 'ISO-8859-2'))
		);

		$db->sql_query("SELECT * FROM _dbloader_err");

		$fr = new FileReader($rt_data['file_name'], $rt_data['file_size']);
		$reader_engine = ( $rt_data['alt_engine'] ) ? SQL_READER_FAST : SQL_READER_PMA;
		$sqlreader = new SQLReader($fr, $reader_engine);

		for($i = 0; $i < $rt_data['errors']; $i++)
		{
			$row = $db->sql_fetchrow();
			$fr->seek($row['pos']);
			$query = $sqlreader->get_query();
			$template->assign_block_vars('dbread.error', array(
				'ID' => $i + 1,
				'POS' => $row['pos'],
				'CODE' => $row['code'],
				'MESSAGE' => xhtmlspecialchars($row['message']),
				'QUERY' => nl2br(xhtmlspecialchars($query['query'])))
			);
		}
		$template->assign_vars(array(
			'TITLE' => 'Wczytywanie zrzutu bazy danych - raport o b³êdach',
			'PAGE_GENTIME' => $page_time->elapsed())
		);
		$template->pparse('body');
	}
}
elseif( $mode == 'misc' )
{
	$template->assign_vars(array(
		'TITLE' => 'Inne funkcje')
	);
	if( !$config_size_ok || $db_error)
	{
		message_die('', true, true);
	}
	// Wykonaj zadany kod
	$func = ( isset($_GET['func']) ) ? $_GET['func'] : '';
	if( $func == 'dbcreate' )
	{
		$res = '';
		$db_name = (isset($_POST['dbcreate_dbname']) && is_scalar($_POST['dbcreate_dbname'])) ? trim((string) $_POST['dbcreate_dbname']) : '';
		if (!preg_match('/^[A-Za-z0-9_$]{1,64}$/D', $db_name))
		{
			$res = '<span style="color:red">Invalid database name.</span>';
		}
		else
		{
			$query = (isset($_POST['dbcreate_create']) ? 'CREATE' : 'DROP') . " DATABASE `" . $db_name . '`';
			if( !$db->sql_query($query) )
			{
				$err = $db->sql_error();
				$res = '<div class="code">' . htmlspecialchars($query, ENT_QUOTES, 'ISO-8859-2') . '</div><br /><span style="color:red">Error code: ' . htmlspecialchars((string) $err['code'], ENT_QUOTES, 'ISO-8859-2') . '<br />Message: ' . htmlspecialchars($err['message'], ENT_QUOTES, 'ISO-8859-2') . '</span>';
			}
			else
			{
				$res = '<div class="code">' . htmlspecialchars($query, ENT_QUOTES, 'ISO-8859-2') . '</div><br /><span style="color:green">Database <b>' . htmlspecialchars($db_name, ENT_QUOTES, 'ISO-8859-2') . '</b> was ' . ( isset($_POST['dbcreate_create']) ? 'created' : 'dropped' ) . '.</span>';
			}
		}
		$_SESSION['dbcreate_res'] = $res;
	}
	elseif( $func == 'bbdrop' )
	{
		$res = '';
		include 'dbdata.inc.php'; //$dbs, $dbs_for
		$dbs = append_prefix($table_prefix, $dbs);
		if( isset($_POST['bbdrop_check']) && strtolower($_POST['bbdrop_check']) == 'skasuj' )
		{
			foreach( $dbs as $dbs_item )
			{
				$table_name = ( !is_array($dbs_item) ) ? $dbs_item : $dbs_item['table'];
				if( !$db->sql_query("DROP TABLE IF EXISTS `$table_name`") )
				{
					$err = $db->sql_error();
					$res = ( $res != '' ) ? '<hr />'.$res : $res;
					$res = '<span style="color:red">Error code: ' . $err['code'] . '<br />Message: ' . $err['message'] . '</span>';
				}
				if( $res == '' )
				{
					$res = '<span style="color:green">Tabele zosta³y usuniête.</span>';
				}
			}
		}
		else
		{
			$res = 'Wpierw wype³nij pole tekstowe.';
		}
		$_SESSION['bbdrop_res'] = $res . '<br />';
	}
	if( $func != '' )
	{
		header('HTTP/1.1 301');
		header("Location: $PHP_SELF?mode=misc");
		exit;
	}
	// Przygotuj szablon
	$db->sql_query("SHOW DATABASES");
	$result = $db->sql_fetchrowset();
	$db_list = array();
	foreach( $result as $db_name )
	{
		$db_list[] = $db_name['Database'];
	}
	if( count($db_list) == 0 )
	{
		$db_list[] = '<i>brak baz</i>';
	}
	$template->assign_block_vars('misc', array(
		'DBLIST' => implode('<br />', array_map(function($name) { return htmlspecialchars((string) $name, ENT_QUOTES, 'ISO-8859-2'); }, $db_list)),
		'DBCREATE_RESULT' => session_load_once('dbcreate_res'),
		'BBDROP_RESULT' => session_load_once('bbdrop_res'))
	);
	$template->assign_var('PAGE_GENTIME', $page_time->elapsed());
	$template->pparse('body');
}
else
{
	if( !$db_error )
	{
		$db->sql_query("SELECT * FROM _dbloader");
		$tbl_exists = ($db->sql_affectedrows() > 0 ) ? true : false;
		$tables_deleted = false;
		if( $tbl_exists )
		{
			$check_data = $db->sql_fetchrowset();
			$check_data = config_assoc($check_data, 'config', 'value');

			if (isset($_GET['droptables']))
			{
				$db->sql_query("DROP TABLE IF EXISTS _dbloader");
				$db->sql_query("DROP TABLE IF EXISTS _dbloader_err");
				$tables_deleted = true;
			}
			elseif ($check_data['max_queries'] > 0 && empty($check_data['completed']))
			{
				echo generate_error_notify($check_data, false);
				exit;
			}
		}
	}
	$content = '<b>config.'.$phpEx.'</b><br />';
	if( $is_config )
	{
		$content .= '<span style="color:green">istnieje</span> <img src="'.$img_path.'icon_mini_register.gif" alt="Ok"><br />';
		if( $config_size_ok )
		{
			$content .= '<span style="color:green">rozmiar poprawny</span> <img src="'.$img_path.'icon_mini_register.gif" alt="Ok"><br />';
		}
		else
		{
			$content .= '<span style="color:red">prawdopodobnie pusty (ma³y rozmiar)</span> <img src="'.$img_path.'icon_mini_ignore.gif" alt="B³±d"><br />';  
		}
	}
	else
	{
		$content .= '<span style="color:red">brak pliku</span> <img src="'.$img_path.'icon_mini_ignore.gif" alt="B³±d"><br />';
	}

	$content .= '<br /><b>baza danych</b><br />';
	if( !$db_error )
	{
		$content .= '<span style="color:green">nawi±zano po³±czenie ('.htmlspecialchars($dbuser, ENT_QUOTES, 'ISO-8859-2').'.'.htmlspecialchars($dbname, ENT_QUOTES, 'ISO-8859-2').' @ '.htmlspecialchars($dbhost, ENT_QUOTES, 'ISO-8859-2').')</span> <img src="'.$img_path.'icon_mini_register.gif" alt="Ok"><br />';
		$content .= ( $tables_deleted ) ? '<span style="color:green">usuniêto tymczasowe tabele _dloader i _dbloader_err</span> <img src="'.$img_path.'icon_mini_register.gif" alt="Ok"><br />' : '';
		$content .= ( $not_supported ) ? '<span style="color:red">nieobs³ugiwany typ bazy danych: <b>'.$dbms.'</b>, skrypt mo¿e nie dzia³aæ poprawnie</span> <img src="'.$img_path.'icon_mini_ignore.gif" alt="B³±d"><br />' : '';
		if( $cfg_table_ex )
		{
			$content .= '<span style="color:green">znaleziono tabelê '.$table_prefix.'config</span> <img src="'.$img_path.'icon_mini_register.gif" alt="Ok"><br />';
		}
		else
		{
			$content .= '<span style="color:red">nie znaleziono tabeli '.$table_prefix.'config</span> <img src="'.$img_path.'icon_mini_ignore.gif" alt="B³±d"><br />';
		}
	}
	else
	{
		$content .= '<span style="color:red">nie nawi±zano po³±czenia</span> <img src="'.$img_path.'icon_mini_ignore.gif" alt="B³±d"><br />';
		if( $config_size_ok )
		{
			$sql_error = $db->sql_error($connect);
			$content .= '<span style="color:red"> &nbsp; Error code: ' . $sql_error['code'] . '<br />';
			$content .= ' &nbsp; Error message: ' . $sql_error['message'] . '</span><br />';
		}
	}
	$content .= '<br /><b>serwer</b><br />';
	$gz = function_exists('gzopen');
	$bz2 = function_exists('bzdecompress');
	$content .= ( $is_limited ) ? '<span style="color:red">Safe Mode lub serwer Lycos</span><br />' : '';
	$content .= '<span style="color:'.($gz ? 'green' : 'red').'">obs³uga gzip (.gz)</span> <img src="'.$img_path.'icon_mini_'.($gz ? 'register' : 'ignore').'.gif" alt="'.($gz ? 'Ok' : 'B³±d').'"><br />';
	$content .= '<span style="color:'.($bz2 ? 'green' : 'red').'">obs³uga <a href="http://sources.redhat.com/bzip2/" target="_blank">&raquo; Bzip2</a> (.bz2)</span> <img src="'.$img_path.'icon_mini_'.($bz2 ? 'register' : 'ignore').'.gif" alt="'.($bz2 ? 'Ok' : 'B³±d').'"><br />';
	
	$pg_title = 'phpBB DumpLoader ' . $version;
	$pg_title .= ( $has_access ) ? '' : ' &nbsp; <span style="color:red">wy³±czony</span>';
	$conf_current = '<?php
// twój adres IP
$twoje_ip = \''.$twoje_ip.'\';
... ';
	$conf_proper = '<?php
// twój adres IP
$twoje_ip = \''.$_SERVER['REMOTE_ADDR'].'\';'.($_SERVER['REMOTE_ADDR'] != $twoje_ip ? ' // zmieñ tu' : '').'
... ';
	$conf_current = str_replace("\r\n", "\n", $conf_current);
	$conf_current = highlight_string($conf_current, true);
	$conf_current = explode("\n", $conf_current);
	$conf_current = $conf_current[1];
	$conf_proper = str_replace("\r\n", "\n", $conf_proper);
	$conf_proper = highlight_string($conf_proper, true);
	$conf_proper = explode("\n", $conf_proper);
	$conf_proper = $conf_proper[1];
	$template->assign_vars(array(
		'TITLE' => $pg_title)
	);
	$template->assign_block_vars('index', array(
		'FAST_CHECK' => $content,
		'CONF_CURRENT' => nl2br($conf_current),
		'CONF_PROPER' => nl2br($conf_proper))
	);
	$template->assign_var('PAGE_GENTIME', $page_time->elapsed());
	$template->pparse('body');
}
if (!empty($db))
{
	$db->sql_close();
}
?>