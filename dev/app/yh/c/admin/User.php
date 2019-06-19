<?php

declare(strict_types = 1);
namespace Apptest\yh\c\admin;
defined('IN_SYS') || exit('ACC Denied');

use Apptest\yh\m\MainUser as GaaraUser;
use Gaara\Core\Request;
use Gaara\Core\Controller;
use Gaara\Core\Response;
use PDOException;
use Apptest\yh\s\Token;

/**
 * 管理员, 对用户的权限管理 (登入的启用/禁用, 支付的启用/禁用)
 */
class User extends Controller {
    
    /**
     * 查询用户信息
     * @param Request $request
     * @param UserMerchant $merchant
     * @return type
     */
    public function select(GaaraUser $GaaraUser) {
        return $this->returnData(function() use ($GaaraUser){
            return $GaaraUser->getAll();
        });
    }

    /**
     * 新增用户信息
     * @param Request $request
     * @param UserMerchant $merchant
     */
    public function create() {
        return $this->fail( '管理员不可以新增用户');
    }

    /**
     * 更新用户信息
     * @param Request $request
     * @param GaaraUser $GaaraUser
     */
    public function update(Request $request, GaaraUser $GaaraUser) {
        $user_id = (int)$this->input('id');
        $userInfo = $request->input;
        // 原数据
        $userOldInfo = $GaaraUser->getId( $user_id );
        if(empty($userOldInfo))
            return $this->fail( '要修改的用户不存在');
    
        $GaaraUser->orm = $userInfo;
       
        // 写入数据库, 若失败则删除已保存的文件
        try{
            $res = $GaaraUser->save($userOldInfo['id']);
            Token::removeToken($user_id);
            return $this->returnData($res);
        }catch(PDOException $pdo){
            return $this->fail( $pdo->getMessage());
        }
    }

    /**
     * 删除用户信息
     * @return Response
     */
    public function destroy() {
        return $this->fail( '管理员不可以删除用户');
    }
}
