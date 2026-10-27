<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2" />
<link rel="stylesheet" href="templates/subSilver.css" type="text/css" />
<title>phpBB DumpLoader {VERSION}</title>
<style type="text/css">
<!--
/* slightly modified style from subSilver */
body {
	background-color: #E5E5E5;
	scrollbar-face-color: #DEE3E7;
	scrollbar-highlight-color: #FFFFFF;
	scrollbar-shadow-color: #DEE3E7;
	scrollbar-3dlight-color: #D1D7DC;
	scrollbar-arrow-color:  #006699;
	scrollbar-track-color: #EFEFEF;
	scrollbar-darkshadow-color: #98AAB1;
}
img {
	border: 0px;
}

font,th,td,p { font-family: Verdana, Arial, Helvetica, sans-serif }
a:link,a:active,a:visited { color : #006699; }
a:hover		{ text-decoration: underline; color : #DD6900; }
hr	{ height: 0px; border: solid #D1D7DC 0px; border-top-width: 1px;}

.bodyline { background-color: #FFFFFF; border: 1px #98AAB1 solid; }

.forumline	{ background-color: #A9B8C2; border: solid #D1D7DC 0px; border-top-width: 1px; }

td.row1	{ background-color: #EFEFEF; }
td.row2	{ background-color: #DEE3E7; }
td.row3	{ background-color: #D1D7DC; }

td.rowpic {
		background-color: #FFFFFF;
		background-image: url('{STYLE_IMAGES}cellpic2.jpg');
		background-repeat: repeat-y;
}

th	{
	color: #FFA34F; font-size: 11px; font-weight : bold;
	background-color: #006699; height: 25px;
	background-image: url('{STYLE_IMAGES}cellpic3.gif');
}

td.catHead {
	background-image: url('{STYLE_IMAGES}cellpic1.gif');
	background-color:#D1D7DC; border: #FFFFFF; border-style: solid; height: 28px;
}

td.catHead {
	height: 29px;
	border-width: 0px 0px 0px 0px;
}
th.thHead {
	font-weight: bold; border: #FFFFFF; border-style: solid; height: 28px; }

h1	{
	font-weight: bold; font-size: 22px; font-family: "Trebuchet MS",Verdana, Arial, Helvetica, sans-serif;
	text-decoration: none; line-height : 120%; color : #000000;
}

.gen { font-size : 12px; }
.genmed { font-size : 11px; }
.gensmall { font-size : 10px; }
.gen,.genmed,.gensmall { color : #000000; }
a.gen,a.genmed,a.gensmall { color: #006699; text-decoration: none; }
a.gen:hover,a.genmed:hover,a.gensmall:hover	{ color: #DD6900; text-decoration: underline; }

.cattitle		{ font-weight: bold; font-size: 12px ; letter-spacing: 1px; color : #006699}
a.cattitle		{ text-decoration: none; color : #006699; }
a.cattitle:hover{ text-decoration: underline; }

.forumlink		{ font-weight: bold; font-size: 12px; color : #006699; }
a.forumlink 	{ text-decoration: none; color : #006699; }
a.forumlink:hover{ text-decoration: underline; color : #DD6900; }

.nav			{ font-weight: bold; font-size: 11px; color : #000000;}
a.nav			{ text-decoration: none; color : #006699; }
a.nav:hover		{ text-decoration: underline; }

.code {
	font-family: Courier, 'Courier New', sans-serif; font-size: 11px; color: #006600;
	background-color: #FAFAFA; border: #D1D7DC; border-style: solid;
	border-left-width: 1px; border-top-width: 1px; border-right-width: 1px; border-bottom-width: 1px
}

.quote {
	font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; color: #444444; line-height: 125%;
	background-color: #FAFAFA; border: #D1D7DC; border-style: solid;
	border-left-width: 1px; border-top-width: 1px; border-right-width: 1px; border-bottom-width: 1px
}

.copyright		{ font-size: 10px; font-family: Verdana, Arial, Helvetica, sans-serif; color: #444444; letter-spacing: -1px;}
a.copyright,a:visited		{ color: #006699; text-decoration: none;}
a.copyright:hover { color: #DD6900; text-decoration: underline;}

input,textarea, select {
	color : #000000;
	font: normal 11px Verdana, Arial, Helvetica, sans-serif;
	border-color : #000000;
	border-width : 1px;
}

input.post, textarea.post, select {
	background-color : #EFEFEF;
}
input.post2, textarea.post2, select {
	background-color : #DEE3E7;
}

input { text-indent : 2px; }

input.button {
	background-color : #EFEFEF;
	color : #000000;
	font-size: 11px; font-family: Verdana, Arial, Helvetica, sans-serif;
}

input.mainoption {
	background-color : #FAFAFA;
	font-weight : bold;
}

input.liteoption {
	background-color : #FAFAFA;
	font-weight : normal;
}

.topbkg{
	background: #dbe3ee url('{STYLE_IMAGES}cellpic_bkg.jpg') repeat-x
}
.navtit{
	font-size:10px;
	background: #e5ebf3 url('{STYLE_IMAGES}cellpic_nav.gif') repeat-x;
	color:#dd6900;
	height:21px;
	white-space:nowrap;
	text-align:center;
	border: 0px solid #91a0ae;
	border-width: 1px 0 0 0;
}
.navsec{
	font-size: 12px;
	background: #e5ebf3;
	color: #dd6900;
	border: 0px solid #91a0ae;
	border-width: 0 0 1px 0;
}

label:hover {
	color:red
}
-->
</style>
</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0" border="0">
 <tr>
  <td class="bodyline">
   <div class="topbkg" style="width:100%; height:100px">
    <table width="100%" border="0">
     <tr>
      <td style="text-align:center; height:100px">
       <h1>phpBB DumpLoader {VERSION}</h1>
      </td>
     </tr>
    </table>
   </div>
   <div style="padding:2px">
    <a class="forumlink" href="dbloader.php">Początek</a> 
    <b>|</b> <a class="forumlink" href="?mode=makeconfig">Edytuj config.php</a> 
    <!-- BEGIN forum_config_link -->
    <b>|</b> <a class="forumlink" href="?mode=forumconfig">Konfiguruj forum</a> 
    <b>|</b> <a class="forumlink" href="?mode=checkdb">Sprawdź bazę</a> 
    <!-- END forum_config_link -->
    <!-- BEGIN no_forum_config_link -->
    <b>|</b> <a class="forumlink" style="color:gray">Konfiguruj forum</a> 
    <b>|</b> <a class="forumlink" style="color:gray">Sprawdź bazę</a> 
    <!-- END no_forum_config_link -->
    <!-- BEGIN db_actions -->
    <b>|</b> <a class="forumlink" href="?mode=sqllist">Wczytaj bazę danych</a> 
    <b>|</b> <a class="forumlink" href="?mode=misc">Inne funkcje</a> 
    <!-- END db_actions -->
   </div>
<!-- BEGIN info -->
<div class="navtit" style="width:100%; height:30; padding-top:6px">
 <span class="cattitle">{info.TITLE}</span>
</div>
<div class="navsec" style="width:100%">
 {info.CONTENT}
</div>
<br />
<!-- END info -->
   <div style="text-align:center">
   <table width="99%" cellpadding="3" cellspacing="1" border="0" class="forumline" align="center">
    <tr>
     <th>{TITLE}</th>
    </tr>
    <tr>
     <td class="row1">
      <span class="gen">
       {CONTENT}
<!-- BEGIN login -->
<form action="{PHP_SELF}" method="post">
<input type="hidden" name="csrf_token" value="{CSRF_TOKEN}" />
 Skrypt jest zabezpieczony hasłem. Aby móc z nim dalej pracować musisz je
 wpisać w pole poniżej.<br /><br />
 <input type="password" name="password"> <input type="submit" value="Zaloguj">
</form>
<!-- END login -->
<!-- BEGIN index -->
Skrypt ten ma na celu ułatwienie wczytywania kopii bazy danych MySQL
dla forum phpBB modiefied by Przemo (teoretycznie powinien też działać ze
zwykłym phpBB). Obsługiwane formaty plików to: &quot;czysty&quot; .sql, .gz
i .bz2 (dwa ostatnie zależnie od serwera).<br /><br />
Aby wgrać tabele do bazy danych, upewnij się że masz poprawny plik
config.{PHP_EX} i skopiuj na serwer do katalogu z tym skryptem plik z kopią bazy.
Jeśli dopiero zamierzasz utworzyć taką kopię, najlepiej zrób to za pomocą
<a href="http://phpmyadmin.sf.net">phpMyAdmina</a> (dostępny na większości
serwerów).<br /><br />
Uwaga - staraj się unikać wczytywania plików spakowanych Bzipem
za pomocą wolnego algorytmu - z nieznanych mi przyczyn skrypt ma tendencję
do zapętlania się przy odczycie plików bz2 z użyciem tej metody.<br /><br />
{index.FAST_CHECK}<br /><br />
W celu zwiększenia bezpieczeństwa działania skrypt sprawdza
twój adres IP. Aby uaktywnić skrypt wyedytuj plik dbloader.php i zmień go
według poniższego schematu.<br /><br />
<b>Teraz początek pliku <i>/dbloader/dbloader.php</i> wygląda tak:</b>
<table cellspacing="1" cellpadding="3" border="0" width="100%">
 <tr>
  <td class="code">
   {index.CONF_CURRENT}
  </td>
 </tr>
</table><br />
<b>Jeśli skrypt jest wyłączony, i chcesz rozpocząć na nim pracę, zmień go tak:</b>
<table cellspacing="1" cellpadding="3" border="0" width="100%">
 <tr>
  <td class="code">
   {index.CONF_PROPER}
  </td>
 </tr>
</table>
<h5><span style="color:red">UWAGA! Po zakończeniu pracy DumpLoadera KONIECZNIE wyłącz skrypt (ustaw $twoje_ip na 'DISABLED')</span></h5>
<!-- END index -->
<!-- BEGIN config -->
<form action="{PHP_SELF}?mode=makeconfig" method="post">
<input type="hidden" name="csrf_token" value="{CSRF_TOKEN}" />
<table width="100%" cellpadding="2" cellspacing="1" border="0" class="forumline"> 
 <tr> 
  <td class="row1" align="right" style="width:300px"><span class="gen">Typ Bazy Danych:&nbsp;</span></td>
  <td class="row2">
   <select name="dbms">
    {config.DBMS_OPTIONS}
   </select>
  </td>
 </tr>
 <tr>
  <td class="row1" align="right"><span class="gen">Server Bazy Danych / DSN:&nbsp;</span></td>
  <td class="row2"><input type="text" name="dbhost" value="{config.DBHOST}" /></td>
 </tr>
 <tr>
  <td class="row1" align="right"><span class="gen">Nazwa Bazy Danych:&nbsp;</span></td>
  <td class="row2"><input type="text" name="dbname" value="{config.DBNAME}" /></td>
 </tr>
 <tr>
  <td class="row1" align="right"><span class="gen">Użytkownik Bazy Danych:&nbsp;</span></td>
  <td class="row2"><input type="text" name="dbuser" value="{config.DBUSER}" /></td>
 </tr>
 <tr>
  <td class="row1" align="right"><span class="gen">Hasło Bazy Danych:&nbsp;</span></td>
  <td class="row2"><input type="password" name="dbpasswd" value="{config.DBPASSWORD}" /></td>
 </tr>
 <tr>
  <td class="row1" align="right"><span class="gen">Prefiks dla tabel w bazie danych:&nbsp;</span></td>
  <td class="row2"><input type="text" name="table_prefix" value="{config.TABLE_PREFIX}" /></td>
 </tr>
 <tr>
  <td align="center" colspan="2">
   <input type="hidden" name="generate_config" value="true" />
   <input class="mainoption" type="submit" name="submit" value="Generuj" />
  </td>
 </tr>
</table>
</form>
<!-- BEGIN code -->
<form action="{PHP_SELF}?mode=makeconfig" method="post">
<input type="hidden" name="csrf_token" value="{CSRF_TOKEN}" />
 <table border="0" cellspacing="0">
  <tr>
   <th style="width:50%">Info</th>
   <th>Nowy config.{PHP_EX}</th>
  </tr>
  <tr>
   <td valign="top">
    <span class="gen">Skopiuj <b>19</b> podanych linii i zapisz je jako
	<u>config.{PHP_EX}</u> lub kliknij na przycisk <u>Ściągnij plik</u> i następnie
	wyślij plik do głównego katalogu phpBB2. Upewnij się że po <u>?&gt;</u>
	nie ma żadnych innych znaków (w tym spacji)! {config.code.SAVE_RESULT}
    </span><br /><br />
    <input type="hidden" name="dbms" value="{config.DBMS}" />
    <input type="hidden" name="dbhost" value="{config.DBHOST}" />
    <input type="hidden" name="dbname" value="{config.DBNAME}" />
    <input type="hidden" name="dbuser" value="{config.DBUSER}" />
    <input type="hidden" name="dbpasswd" value="{config.DBPASSWORD}" />
    <input type="hidden" name="table_prefix" value="{config.TABLE_PREFIX}" />
    <input type="hidden" name="download_config" value="true" />
    <input type="submit" name="submit_download_config" value="Ściągnij plik" class="mainoption" />
	<input type="submit" name="submit_save_config" value="Spróbuj zapisać" /><br />
   </td>
   <td>
    <table cellspacing="1" cellpadding="3" border="0" width="100%">
     <tr>
      <td class="code">
       {config.code.CODE}
      </td>
     </tr>
    </table>
   </td>
  </tr>
 </table>
</form>
<!-- END code -->
<!-- END config -->
<!-- BEGIN forumconfig -->
<form action="{PHP_SELF}?mode=forumconfig" method="post">
<input type="hidden" name="csrf_token" value="{CSRF_TOKEN}" />
<!-- BEGIN result -->
<div class="quote" style="padding:2px; margin:5px">
 {forumconfig.result.RESULT}
</div>
<!-- END result -->
<table width="100%" cellpadding="2" cellspacing="1" border="0" class="forumline">
 <tr>
  <td class="row1" style="width:230px"><span class="gensmall">Nazwa opcji</span></td>
  <td class="row1" style="width:138px"><span class="gensmall">Zalecana wartość</span></td>
  <td class="row1"><span class="gensmall">Wartość ustawiona w bazie danych</span></td>
 </tr>
 <tr>
  <td class="catHead" colspan="3" align="center">
   <span class="gen"><b>Generalne Ustawienia Forum</b></span>
  </td>
 </tr>
 <tr>
  <td class="row1"><span class="gen">Nazwa domeny: </span></td>
  <td class="row2"><input type="text" name="server_name" value="{forumconfig.SERVER_NAME}" /></td>
  <td class="row2"><span class="gensmall">{forumconfig.SERVER_NAME_DB}</span></td>
 </tr>
 <tr>
  <td class="row1"><span class="gen">Sprawdzanie poprawności adresu: </span></td>
  <td class="row2"><span class="gen"><input type="radio" name="check_address" value="1" {forumconfig.CHECK_ADDRESS_1} /> Tak &nbsp; <input type="radio" name="check_address" value="0" {forumconfig.CHECK_ADDRESS_0} /> Nie</span></td>
  <td class="row2"><span class="gensmall">{forumconfig.CHECK_ADDRESS_DB}</span></td>
 </tr>
 <tr>
  <td class="row1"><span class="gen">Port serwera: </span></td>
  <td class="row2"><input type="text" name="server_port" maxlength="5" value="{forumconfig.SERVER_PORT}" /></td>
  <td class="row2"><span class="gensmall">{forumconfig.SERVER_PORT_DB}</span></td>
 </tr>
 <tr>
  <td class="row1"><span class="gen">Ścieżka skryptu: </span></td>
  <td class="row2"><input type="text" name="script_path" value="{forumconfig.SCRIPT_PATH}" /></td>
  <td class="row2"><span class="gensmall">{forumconfig.SCRIPT_PATH_DB}</span></td>
 </tr>
 <tr>
  <td class="catHead" colspan="3" align="center">
   <span class="gen"><b>Ustawienia Cookies</b></span>
  </td>
 </tr>
 <tr>
  <td class="row1"><span class="gen">Domena Cookie:</span></td>
  <td class="row2"><input type="text" maxlength="255" name="cookie_domain" value="{forumconfig.COOKIE_DOMAIN}" /></td>
  <td class="row2"><span class="gensmall">{forumconfig.COOKIE_DOMAIN_DB} (powinna być taka sama jak nazwa domeny)</span></td>
 </tr>
 <tr>
  <td class="row1"><span class="gen">Nazwa Cookie</span></td>
  <td class="row2"><input type="text" maxlength="16" name="cookie_name" value="{forumconfig.COOKIE_NAME}" /></td>
  <td class="row2"><span class="gensmall">{forumconfig.COOKIE_NAME_DB} (domyślnie: phpbb2mysql, najlepiej losowa wartość)</span></td>
 </tr>
 <tr>
  <td class="row1"><span class="gen">Ścieżka Cookie</span></td>
  <td class="row2"><input type="text" maxlength="255" name="cookie_path" value="{forumconfig.COOKIE_PATH}" /></td>
  <td class="row2"><span class="gensmall">{forumconfig.COOKIE_PATH_DB}</span></td>
 </tr>
 <tr>
  <td class="row1"><span class="gen">Bezpieczne Cookie [ https ]</span></td>
  <td class="row2"><span class="gen"><input type="radio" name="cookie_secure" value="1" {forumconfig.COOKIE_SECURE_1} /> Tak &nbsp; <input type="radio" name="cookie_secure" value="0" {forumconfig.COOKIE_SECURE_0} /> Nie</span></td>
  <td class="row2"><span class="gensmall">{forumconfig.COOKIE_SECURE_DB}</span></td>
 </tr>
 <tr>
  <td align="center" colspan="3">
   <input type="hidden" name="save_config" value="true" />
   <input class="mainoption" type="submit" name="submit" value="Zapisz konfigurację" />
  </td>
 </tr>
</table>
</form>
<!-- END forumconfig -->
<!-- BEGIN db_check -->
Wybrana baza danych: <b>{db_check.DB}</b><br />
Plik definicji tabel dla wersji: <b>{db_check.TABLES_DEF_FOR}</b><br /><br />
<!-- BEGIN create -->
<div class="quote">{db_check.create.SQL}</div>
{db_check.create.RESULT}
<br /><br />
<!-- END create -->
<b>Obecność i poprawność wszystkich tabel:</b><br />
{db_check.TABES_CHECK}
<!-- END db_check -->
<!-- BEGIN sqllist -->
<b>Znalezione pliki SQL:</b><br /><br />
<table border="0" cellpadding="3" cellspacing="1" class="forumline" style="margin-left:10px">
 <tr><td class="catHead"><span class="gen"><b>Nazwa pliku</b></span></td><td class="catHead"><span class="gen"><b>Rozmiar (kB)</b></span></td></tr>
 <!-- BEGIN item -->
 <tr>
  <td class="row{sqllist.item.ROW_STYLE}"><a class="nav" href="{sqllist.item.LINK}"><img src="{STYLE_IMAGES}/icon_mini_message.gif" alt="Plik SQL" width="12" height="13"> {sqllist.item.TEXT}</a></td>
  <td class="row{sqllist.item.ROW_STYLE}"><span class="gen">{sqllist.item.SIZE}</span></td>
 </tr>
 <!-- END item -->
 <!-- BEGIN no_items --> 
 <tr>
  <td class="row2" colspan="2"><span class="gen">{sqllist.no_items.MSG}</span></td>
 </tr>
 <!-- END no_items -->
</table><br />
<!-- END sqllist -->
<!-- BEGIN dbread -->
       Wybrana baza danych: <b>{dbread.DB}</b><br />
       Plik do wczytania: <b>{dbread.SQL_FILE}</b><br /><br />
       <!-- BEGIN form -->
       <form action="{dbread.form.FORM_ACTION}" method="post">
<input type="hidden" name="csrf_token" value="{CSRF_TOKEN}" />
	    <table border="0" cellpadding="3" cellspacing="1" class="forumline" style="margin-left:10px">
         <tr>
          <td class="row2"><span class="gen">Ilość zapytań jednorazowo czytana z pliku:</span></td>
          <td class="row2"><input type="text" name="max_queries" value="{dbread.form.MAX_QUERIES_DEF}" size="5"></td>
         </tr>
         <tr>
          <td class="row2"><span class="gen">
           <label for="omit_search">Pomiń wpisy do tabel search_*</label></span><br />
           <span class="gensmall">Wszystkie rekordy dla tabel wyszukiwania zostaną pominięte.</span>
          </td>
          <td class="row2" valign="top"><input type="checkbox" name="omit_search" id="omit_search"></td>
         </tr>
         <tr>
          <td class="row2">
           <span class="gen"><label for="alt_engine">Użyj alternatywnego przetwarzania (szybsze)&nbsp;</label></span><br>
           <span class="gensmall">Przy wczytywaniu wykorzystuje szybszą metodę wczytywania<br />
           zapytań (domyślnie jest używany algorytm z phpMyAdmina).</span>
          </td>
          <td class="row2" valign="top"><input type="checkbox" name="alt_engine" checked="checked" id="alt_engine"></td>
         </tr>
         <tr>
          <td class="row2">
           <span class="gen"><label for="lock_tables">Blokuj tabele na czas wczytywania</label></span><br />
           <span class="gensmall">Podczas wczytywania tabele będą blokowane, co pozwoli na<br />
           szybsze dodawanie rekordów. Wyłącz jeśli wczytywany plik<br />
           nie jest zrzutem bazy danych ale zbiorem różnych zapytań.</span>
          </td>
          <td class="row2" valign="top"><input type="checkbox" name="lock_tables" checked="checked" id="lock_tables"></td>
         </tr>
        </table><br />
	    <input type="submit" value="Rozpocznij wgrywanie" class="mainoption">
	   </form>
       <!-- END form -->
       <!-- BEGIN completed -->
	   <br />
	   <table border="0" cellspacing="0" cellpadding="3" class="bodyline" width="100%">
        <tr><td class="catHead" align="center"><span class="gen"><b>Zakończono wczytywanie</b></span></td></tr>
	<tr>
         <td class="row2">
		  <span class="gen">Liczba wykonanych zapytań: <b>{dbread.completed.QUERIES_LOADED}</b> (pominiętych <b>{dbread.completed.QUERIES_OMITTED}</b>)<br />
		  Limit zapytań SQL: <b>{dbread.completed.QUERIES_MAX}</b><br />
		  Blokowano tabele: <b>{dbread.completed.LOCK_TABLES}</b><br /><br />
		  Limit błędów był ustawiony na: <b>{dbread.completed.ERRORS_MAX}</b><br />
          Zapytania przetworzono w <b>{dbread.completed.TIME_PHP}</b><br />
          Zapytania wykonano do bazy w <b>{dbread.completed.TIME_SQL}</b><br />
          Łącznie: ~ <b>{dbread.completed.TIME_TOTAL}</b></span>
		 </td>
        </tr>
        <tr>
		<td class="row3" align="center"><span class="gen"><a href="../">Forum - Strona główna</a></span></td>
	</tr>
       </table>
       <!-- BEGIN errors -->
       <br />
       <table border="0" cellspacing="0" cellpadding="3" class="bodyline" width="100%">
        <tr>
         <td class="catHead" align="center"><span class="gen"><b>Błędy zwrócone przez MySQL</b></span></td>
        </tr>
        <tr>
         <td class="row3">
          <span class="gen">Podczas wykonywania zapytań MySQL znalazł błędy w zapytaniach:
		  <span style="color:red"><b>{dbread.completed.errors.ERRORS}</b></span>.<br /><br />
		  <a href="{PHP_SELF}?mode=dbread&amp;step=show_errors" target="_blank">Pokaż raport o błędach</a></span>
		 </td>
        </tr>
       </table>
       <!-- END errors -->
       <!-- END completed -->
       <!-- BEGIN error -->
       <hr /><br />
       <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
         <td class="gensmall" style="width:20px" valign="top"><b>{dbread.error.ID}</b></td>
         <td class="gensmall" style="color:red">{dbread.error.CODE}: {dbread.error.MESSAGE}</td>
         <td class="gensmall" align="right">Pozycja w pliku: {dbread.error.POS}</td>
        </tr>
       </table>
	   {dbread.error.QUERY}<br /><br />
       <!-- END error -->
       <!-- END dbread -->
<!-- BEGIN misc -->
<br />
<table border="0" cellpadding="3" cellspacing="0" class="bodyline" width="100%" style="margin-left:10px; margin-right:10px">
 <tr>
  <td class="catHead">
   <span class="gen"><b>Tworzenie / usuwanie bazy danych</b></span>
  </td>
 </tr>
 <tr>
  <td class="row2">
   <span class="gen"><b>Istniejące bazy:</b></span>
   <div class="gen" style="margin-left:10px">
    {misc.DBLIST}
   </div><br />
   <span class="gen">{misc.DBCREATE_RESULT}</span>
   <form action="{PHP_SELF}?mode=misc&amp;func=dbcreate" method="post">
<input type="hidden" name="csrf_token" value="{CSRF_TOKEN}" />
    <span class="gen"><b>Nazwa bazy:</b> </span>
    <input type="text" name="dbcreate_dbname">
    <input type="submit" name="dbcreate_create" value="Utwórz"> <input type="submit" name="dbcreate_drop" value="Usuń">
    <span class="gen"><i>Uwaga: działanie tej opcji zależy od Twoich uprawnień na serwerze</i></span>
   </form>
  </td>
 </tr>
</table><br />
<table border="0" cellpadding="3" cellspacing="0" class="bodyline" width="100%" style="margin-left:10px; margin-right:10px">
 <tr>
  <td class="catHead">
   <span class="gen"><b>Deinstalacja phpBB z bazy danych</b></span>
  </td>
 </tr>
 <tr>
  <td class="row2">
   <span class="gen">{misc.BBDROP_RESULT}</span>
   <form action="{PHP_SELF}?mode=misc&amp;func=bbdrop" method="post">
<input type="hidden" name="csrf_token" value="{CSRF_TOKEN}" />
    <span class="gen">Aby usunąć tabele z bazy danych wpisz w pole poniżej <b>skasuj</b> i kliknij na <b>Usuń tabele</b>.<br />
    Uwaga - dane z bazy zostaną usunięte, jeśli chcesz tylko odinstalować wersję Przema i uzyskać czyste phpBB skorzystaj z odpowiedniej opcji w PA.</span><br />
	<input type="text" name="bbdrop_check"> <input type="submit" name="bbdrop_drop" value="Usuń tabele">
   </form>
  </td>
 </tr>
</table><br />
<!-- END misc -->
      </span>
     </td>
    </tr>
   </table></div>
   <br />
   <div class="copyright" style="text-align:center; margin-bottom:4px">
    phpBB DumpLoader &copy; 2004, 2005 <a href="http://www.crackshome.prv.pl/" class="copyright">Crack</a><br />
    Powered by <a href="http://www.phpbb.com/" class="copyright">phpBB</a> modified by <a href="http://www.przemo.org/phpBB2/" class="copyright">Przemo</a> &copy; 2003 phpBB Group
   </div>
  </td>
 </tr>
</table>
<div style="text-align:right">
 <span class="copyright">Wygenerowano w {PAGE_GENTIME}s</span>
</div>
</body>
</html>
