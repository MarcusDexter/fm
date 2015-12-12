<?php
header("Content-Type: application/json;charset=utf-8"); 

require_once 'dir.func.php';
require_once 'file.func.php';
require_once 'common.func.php';
require_once 'upload.class.php';

define("ROOT_PATH","..");

// 获取路径
$path=isset($_REQUEST['path'])?$_REQUEST['path']:ROOT_PATH;
// 获取操作
$act=isset($_REQUEST['act'])?$_REQUEST['act']:NULL;
// 获取文件名
$filename=isset($_REQUEST['filename'])?$_REQUEST['filename']:NULL;
// 获取文件夹名
$dirname=isset($_REQUEST['dirname'])?$_REQUEST['dirname']:NULL;

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
$i=0;
if($path!=ROOT_PATH){
	array_push($folderInfo, ROOT_PATH);
	array_push($folderInfo,0);
	array_push($folderInfo,'1970-01-01 00:00:00');
}
if(isset($info['dir'])){
	for (; $i < count($info['dir']); $i++) {
		array_push($folderInfo, $info['dir'][$i]);
		array_push($folderInfo, transByte(dirSize($path . '/' . $info['dir'][$i])));
		array_push($folderInfo, date("Y-m-d H:i:s", filemtime($path . '/' . $info['dir'][$i])));
	}
}

// 文件信息格式化并用数组保存
$fileInfo = array();
if(isset($info['file']))
for ($i=0; $i < count($info['file']); $i++) { 
	array_push($fileInfo, $info['file'][$i]);
	array_push($fileInfo,transByte(fileSize($path.'/'.$info['file'][$i])));
	array_push($fileInfo,date("Y-m-d H:i:s",filemtime($path.'/'.$info['file'][$i])));
}

// 数组打包
$tableInfo = array('folderInfo' => $folderInfo, 'fileInfo' => $fileInfo);
$arr = array('tableInfo' => $tableInfo, 'msg' => $msg);

echo json_encode($arr);
return ;

?>