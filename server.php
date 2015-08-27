<?php
header("Content-Type: application/json;charset=utf-8"); 

require_once 'dir.func.php';
require_once 'file.func.php';
require_once 'common.func.php';
require_once 'upload.class.php';

// 获取路径
$path=$_REQUEST['path']?$_REQUEST['path']:"file";
// 获取操作
$act=$_REQUEST['act'];
// 获取文件名
$filename=$_REQUEST['filename'];
// 获取文件夹名
$dirname=$_REQUEST['dirname'];

$msg = "";

if ($act=="delFolder") {
	$msg = delFolder($path.'/'.$dirname);
}elseif ($act=="delFile") {
	$msg = delFile($path.'/'.$filename);
}elseif($act=="renameFolder"){
	$msg = renameFolder($path.'/'.$dirname,$path.'/'.$_REQUEST['newname']);
}elseif($act=="renameFile"){
	$msg = renameFile($path.'/'.$filename,$_REQUEST['newname']);
}elseif($act=="createFolder"){
	$msg = createFolder($path.'/'.$dirname);
}elseif($act=="createFile"){
	$msg = createFile($path.'/'.$filename);
}elseif($_FILES){
	for ($i=0; $i < count($_FILES) ; $i++) {
		$upload=new upload("file".$i,$path);
		$msg.=$upload->uploadFile();
	}
}

// 获取文件夹内文件夹及文件信息
$info=readDirectory($path);

// 文件夹信息格式化并用数组保存
$folderInfo = array();
for ($i=0; $i < count($info[dir]); $i++) { 
	array_push($folderInfo, $info[dir][$i]);
	array_push($folderInfo,transByte(dirSize($path.'/'.$info[dir][$i])));
	array_push($folderInfo,date("Y-m-d H:i:s",filemtime($path.'/'.$info[dir][$i])));
}

// 文件信息格式化并用数组保存
$fileInfo = array();
for ($i=0; $i < count($info[file]); $i++) { 
	array_push($fileInfo, $info[file][$i]);
	array_push($fileInfo,transByte(fileSize($path.'/'.$info[file][$i])));
	array_push($fileInfo,date("Y-m-d H:i:s",filemtime($path.'/'.$info[file][$i])));
}

// 数组打包
$tableInfo = array('folderInfo' => $folderInfo, 'fileInfo' => $fileInfo);
$arr = array('tableInfo' => $tableInfo, 'msg' => $msg);

echo json_encode($arr);
return ;

?>