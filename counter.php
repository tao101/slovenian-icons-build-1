<?php
// счетчик с хранением данных в текстовом файле
//error_reporting(E_ALL);
define('DATAFILE', 'jklhljkhgdfsdsf.php');
define('LOCKFILE', 'sdfghhrtfewrhgf.php');
define('PHP_HEADER', '<?php die(); ?>'); //добавляется в начало файлов данных, чтобы нельзя было смотреть в браузере
define('WAIT_FILE', 10.0); //seconds

if (!file_exists(DATAFILE)) {
	file_put_contents(DATAFILE, PHP_HEADER);
}

$cnt = WAIT_FILE;
if (file_exists(LOCKFILE)) {
	while ($cnt > 0) {
//		sleep(1);
		usleep(100000); //0.1 sec
		if (!file_exists(LOCKFILE)) {
			break;
		}
//		$cnt--;
		$cnt -= 0.1;
	}
}
if ($cnt <= 0) {
	die('lockfile timeout');
}

if (!isset($_GET['page']) || strlen($_GET['page']) > 64 || strlen($_GET['page']) < 8) {
	die('incorrect query');
}
$page = filter_var($_GET['page'], FILTER_SANITIZE_ENCODED);
$shared = isset($_GET['shared'])? filter_var($_GET['shared'], FILTER_SANITIZE_NUMBER_INT): 0;
$viewed = isset($_GET['viewed'])? filter_var($_GET['viewed'], FILTER_SANITIZE_NUMBER_INT): 0;

file_put_contents(LOCKFILE, PHP_HEADER);
$rawdata = file_get_contents(DATAFILE);
$rawdata = str_replace(PHP_HEADER, '', $rawdata);
$data = json_decode($rawdata, true);
unset($rawdata);
if (!isset($data[$page]) && (!$shared && !$viewed)) {
	unlink(LOCKFILE);
	die('not found');
}
if (isset($data[$page])) {
	unlink(LOCKFILE);
	$v = $data[$page]['v'];
	$s = $data[$page]['s'];
	$j = json_encode(array($page => array('viewed' => $v, 'shared' => $s)));
	if((!$shared && !$viewed)) {
        die($j);
    }
	echo $j;
}
if (!isset($data[$page])) {
	$data[$page] = array('v' => 0, 's' => 0);
}
$v = $data[$page]['v'];
$s = $data[$page]['s'];
if ($viewed && intval($viewed) == 1) {
	$v++;
}
if ($shared && intval($shared) == 1) {
	$s++;
}
$data[$page] = array('v' => $v, 's' => $s);
$j = json_encode($data);
file_put_contents(DATAFILE, PHP_HEADER . $j);
unlink(LOCKFILE);
