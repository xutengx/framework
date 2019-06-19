<?php

declare(strict_types = 1);
namespace Apptest\yh\c\admin;

use Apptest\yh\m\UserMerchant;
use Gaara\Core\Request;
use Gaara\Core\Controller;
use PDOException;

/**
 * 通道信息相关
 */
class Passageway extends Controller {

    /**
     * 查询所有通道信息
     * @param Request $request
     * @param UserMerchant $merchant
     * @return type
     */
    public function select(Request $request, UserMerchant $merchant) {
        $userid = (int) $request->userinfo['id'];

        return $this->returnData(function() use ($merchant, $userid) {
                    return $merchant->getInfo($userid);
                });
    }

    /**
     * 新增商户信息
     * @param Request $request
     * @param UserMerchant $merchant
     */
    public function create(Request $request, UserMerchant $merchant) {
        $userinfo = $request->userinfo;
        $merchantInfo = $request->input;

        $merchant->orm = $merchantInfo;
        $merchant->orm['id'] = $userinfo['id'];

        // 保存文件
        foreach ($request->file as $k => $file) {
            if ($file->is_img() && $file->is_less()) {
                if ($file->move_uploaded_file())
                    $merchant->orm[$k] = $file->saveFilename;
            }else {
                $request->file->cleanAll();
                return $this->fail( '上传类型不为图片, 或者大于8m');
            }
        }

        // 写入数据库, 若失败则删除已保存的文件
        try {
            $res = $merchant->create();
            return $this->returnData($res);
        } catch (PDOException $pdo) {
            $request->file->cleanAll();
            return $this->fail( $pdo->getMessage());
        }
    }

    /**
     * 更新商户信息
     * @param Request $request
     * @param UserMerchant $merchant
     */
    public function update(Request $request, UserMerchant $merchant) {
        $userid = $request->userinfo['id'];
        $merchantInfo = $request->input;
        // 原数据
        $merchantOldInfo = $merchant->getInfo($userid);
        if (empty($merchantOldInfo))
            return $this->fail( '要修改的商户不存在');
        // 将要被替换的文件
        $oldFileArr = [];

        $merchant->orm = $merchantInfo;
        $merchant->orm['modify_at'] = date('Y-m-d H:i:s');
        // 保存文件
        foreach ($request->file as $k => $file) {
            if ($file->is_img() && $file->is_less()) {
                if ($file->move_uploaded_file()) {
                    $merchant->orm[$k] = $file->saveFilename;
                    $oldFileArr[] = $merchantOldInfo[$k];
                }
            } else {
                $request->file->cleanAll();
                return $this->fail( '上传类型不为图片, 或者大于8m');
            }
        }

        // 写入数据库, 若失败则删除已保存的文件
        try {
            $res = $merchant->save($merchantOldInfo['id']);
            $this->clean($oldFileArr);
            return $this->returnData($res);
        } catch (PDOException $pdo) {
            $request->file->cleanAll();
            return $this->fail( $pdo->getMessage());
        }
    }

    /**
     * 删除商户信息
     * @return type
     */
    public function destroy(Request $request, UserMerchant $merchant) {
        $userid = (int) $request->userinfo['id'];

        //数据库中的文件字段,都以 _file 结尾 eg : organization_file
        $end_string = '_file';
        // 原数据
        $merchantOldInfo = $merchant->getInfo($userid);
        // 将要被替换的文件
        $oldFileArr = [];
        foreach ($merchantOldInfo as $k => $v) {
            if (strrchr($k, $end_string) === $end_string) {
                $oldFileArr[] = $v;
            }
        }

        try {
            $res = $merchant->delById($userid);
            $this->clean($oldFileArr);
            return $this->returnData($res);
        } catch (PDOException $pdo) {
            return $this->fail( $pdo->getMessage());
        }
    }
}    