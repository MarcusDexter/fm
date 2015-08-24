<?php
header("Content-Type: application/json;charset=utf-8"); 

require_once 'dir.func.php';
require_once 'file.func.php';
require_once 'common.func.php';

$path="file";
$path=$_REQUEST['path']?$_REQUEST['path']:$path;
$act=$_REQUEST['act'];
$filename=$_REQUEST['filename'];
$dirname=$_REQUEST['dirname'];

$msg;
if ($act=="delFolder") {
	$msg = delFolder($path.'/'.$dirname);
}
$info=readDirectory($path);

$folderInfo = array();
$fileInfo = array();
for ($i=0; $i < count($info[dir]); $i++) { 
	array_push($folderInfo, $info[dir][$i]);
	array_push($folderInfo,transByte(dirSize($path.'/'.$info[dir][$i])));
	array_push($folderInfo,date("Y-m-d H:i:s",filemtime($path.'/'.$info[dir][$i])));
}
for ($i=0; $i < count($info[file]); $i++) { 
	array_push($fileInfo, $info[file][$i]);
	array_push($fileInfo,transByte(fileSize($path.'/'.$info[file][$i])));
	array_push($fileInfo,date("Y-m-d H:i:s",filemtime($path.'/'.$info[file][$i])));
}
$tableInfo = array('folderInfo' => $folderInfo, 'fileInfo' => $fileInfo);


$arr = array('tableInfo' => $tableInfo, 'path' => $path, 'msg' => $msg);

echo json_encode($arr);
return ;

?>