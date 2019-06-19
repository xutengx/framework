<?php

namespace Apptest\Dev\file\Contr;

use \Gaara\Core\Controller;
defined('IN_SYS') || exit('ACC Denied');
/*
 * 文件服务, 测试类, 可做调用方法参考
 */

class demoContr extends Controller\Controller {

    private $upload_url = 'http://192.168.43.128/git/php_/project/index.php?path=file/index/upload';

    public function construct() {
        $this->upload_url = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'] . '?path=file/index/upload';
        $language = \Request::get('language');
        if ($language === 'en') {
            $this->language = 1;
        }
        $this->language_array = [
            "文件服务地址" => [
                "文件服务地址1",
                "File System Service Address1"
            ],
            "今日流水" => [
                "今日流水",
                "Daily"
            ],
            "本周流水" => [
                "本周流水",
                "Weekly"
            ],
            "本月流水" => [
                "本月流水",
                "Monthly"
            ],
            "中文" => [
                "中文",
                "ENGLISH"
            ]
        ];
    }

    public function indexDo() {
        $this->assign('url', $this->upload_url);
        $this->display();
    }

    public function upload_demo() {
        foreach ($_FILES as $v) {
            $file_url = './data/' . time() . $v['name'];
            $temp[] = $file_url;
            if (move_uploaded_file($v['tmp_name'], $file_url)) {
                $data[$v['name']] = new \CURLFile(\realpath($file_url));
            }
        }
        $data['version'] = 1;
        $result = obj('Tool')->sendPost($this->upload_url, ($data));
        foreach ($temp as $v) {
            unlink($v);
        }
        var_dump($result);
        var_dump(json_decode($result));
        exit;
    }
}
