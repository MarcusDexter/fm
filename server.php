<?php
header("Content-Type: application/json;charset=utf-8"); 

require_once 'dir.func.php';
require_once 'file.func.php';
require_once 'common.func.php';

$path=$_REQUEST['path']?$_REQUEST['path']:"file";
$act=$_REQUEST['act'];
$filename=$_REQUEST['filename'];
$dirname=$_REQUEST['dirname'];
$info=readDirectory($path);

if ($act=="delFolder") {
	delFolder($path);
}
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

$arr = array('tableInfo' => $tableInfo ,'path' => $path);

echo json_encode($arr);
return ;

?>