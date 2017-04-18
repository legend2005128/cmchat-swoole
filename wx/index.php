<?php
/**
 * Created by PhpStorm.
 * User: mj
 * Date: 2017/4/18
 * Time: 20:57
 */
require_once 'common/function.php';

$re = curlPost('https://login.weixin.qq.com/jslogin');
var_dump($re);