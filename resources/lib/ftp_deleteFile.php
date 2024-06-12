<?php

require_once __DIR__ . '/FtpClient.php';

$protocol   = SdkRestApi::getParam('transferProtocol');
$host       = rtrim(SdkRestApi::getParam('host'), '/');
$user       = SdkRestApi::getParam('user');
$password   = SdkRestApi::getParam('password');
$port       = SdkRestApi::getParam('port');
$path       = trim(SdkRestApi::getParam('folderPath'), '/');
$fileName   = SdkRestApi::getParam('fileName');

$ftp = new FtpClient($protocol, $host, $user, $password, $port);

return json_encode($ftp->deleteFile($path . '/' . $fileName));


