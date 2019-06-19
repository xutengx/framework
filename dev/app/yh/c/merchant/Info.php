<?php

declare(strict_types = 1);
namespace Apptest\yh\c\merchant;
defined('IN_SYS') || exit('ACC Denied');

use Apptest\yh\m\UserMerchant;
use Gaara\Core\Request;
use Gaara\Core\Controller;
use Gaara\Core\Response;
use PDOException;

/**
 * 商户操作
 */
class Info extends Controller {

    /**
     * 查询商户信息
     * @param Request $request
     * @param UserMerchant $merchant
     * @return Response
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
	 * @return Response
	 * @throws \ReflectionException
	 * @throws \Xutengx\Container\Exception\BindingResolutionException
	 */
    public function create(Request $request, UserMerchant $merchant) {
        $userinfo = $request->userinfo;
        $merchantInfo = $this->post();
        
        $merchant->orm = $merchantInfo;
        $merchant->orm['id'] = $userinfo['id'];

        // 保存文件
        foreach ($request->file() as $k => $file) {
            if ($file->isImg() && $file->isLess()) {
                if ($file->move_uploaded_file())
                    $merchant->orm[$k] = $file->saveFilename;
            }else {
                $request->file()->cleanAll();
                return $this->fail( '上传类型不为图片, 或者大于8m');
            }
        }

        // 写入数据库, 若失败则删除已保存的文件
        try {
            $res = $merchant->create();
            return $this->returnData($res);
        } catch (PDOException $pdo) {
            $request->file()->cleanAll();
            return $this->fail( $pdo->getMessage());
        }
    }

	/**
	 * 更新商户信息
	 * @param Request $request
	 * @param UserMerchant $merchant
	 * @return Response
	 * @throws \ReflectionException
	 * @throws \Xutengx\Container\Exception\BindingResolutionException
	 */
    public function update(Request $request, UserMerchant $merchant) {
        $userid = $request->userinfo['id'];
        $merchantInfo = $this->put();

        // 原数据
        $merchantOldInfo = $merchant->getInfo($userid);
        if (empty($merchantOldInfo))
            return $this->fail( '要修改的商户不存在');
        // 将要被替换的文件
        $oldFileArr = [];

        $merchant->orm = $merchantInfo;
        $merchant->orm['modify_at'] = date('Y-m-d H:i:s');
        // 保存文件
        foreach ($request->file() as $k => $file) {
            if ($file->isImg() && $file->isLess()) {
                if ($file->move_uploaded_file()) {
                    $merchant->orm[$k] = $file->saveFilename;
                    $oldFileArr[] = $merchantOldInfo[$k];
                }
            } else {
                $request->file()->cleanAll();
                return $this->fail( '上传类型不为图片, 或者大于8m');
            }
        }

        // 写入数据库, 若失败则删除已保存的文件
        try {
            $res = $merchant->save($merchantOldInfo['id']);
            $this->clean($oldFileArr);
            return $this->returnData($res);
        } catch (PDOException $pdo) {
            $request->file()->cleanAll();
            return $this->fail( $pdo->getMessage());
        }
    }

	/**
	 * 删除商户信息
	 * @param Request $request
	 * @param UserMerchant $merchant
	 * @return Response
	 * @throws \ReflectionException
	 * @throws \Xutengx\Container\Exception\BindingResolutionException
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

    /**
     * 删除数组中的文件
     * @param array $arr
     */
    private function clean(array $arr) {
        foreach ($arr as $v) {
            if (is_file(ROOT . $v) && !is_dir(ROOT . $v)) {
                unlink(ROOT . $v);
            } elseif (is_file($v) && !is_dir($v)) {
                unlink($v);
            }
        }
    }
}
