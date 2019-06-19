<?php

declare(strict_types = 1);
namespace Apptest\yh\c\merchant;
defined('IN_SYS') || exit('ACC Denied');

use Apptest\yh\m\UserApplication;
use Gaara\Core\Request;
use Gaara\Core\Controller;
use PDOException;

/**
 * 应用操作
 */
class Application extends Controller {
    
    /**
     * 查询商户下所有应用信息
     * @param Request $request
     * @param UserApplication $application
     * @return type
     */
    public function select(Request $request, UserApplication $application) {
        $merchant_id = (int)$request->userinfo['id'];
        
        return $this->returnData(function() use ($application, $merchant_id){
            return $application->getAllByMerchantId( $merchant_id );
        });
    }

    /**
     * 新增一个应用信息
     * @param Request $request
     * @param UserApplication $application
     */
    public function create(Request $request, UserApplication $application) {
        $userinfo = $request->userinfo;
        $applicationInfo = $request->input;
        
        $application->orm = $applicationInfo;
        $application->orm['merchant_id'] = $userinfo['id'];
        
        // 保存文件
        foreach($request->file as $k => $file){
            if($file->is_img() && $file->is_less()){
                if($file->move_uploaded_file())
                    $application->orm[$k] = $file->saveFilename;
            }else {
                $request->file->cleanAll();
                return $this->fail( '上传类型不为图片, 或者大于8m');
            }
        }
        
        // 写入数据库, 若失败则删除已保存的文件
        try{
            $res = $application->create();
            return $this->returnData($res);
        }catch(PDOException $pdo){
            $request->file->cleanAll();
            return $this->fail( $pdo->getMessage());
        }
    }

    /**
     * 更新应用信息
     * @param Request $request
     * @param UserApplication $application
     */
    public function update(Request $request, UserApplication $application) {
        $userid = $request->userinfo['id'];
        $applicationInfo = $request->input;
        $app_id = (int)$request->input('id');
        // 原数据
        $applicationOldInfo = $application->getInfoByIdWithMerchant($app_id, $userid );
        if(empty($applicationOldInfo))
            return $this->fail( '要修改的应用不存在');
        // 将要被替换的文件
        $oldFileArr = [];
        $application->orm = $applicationInfo;
        $application->orm['modify_at'] = date('Y-m-d H:i:s');
        // 保存文件
        foreach($request->file as $k => $file){
            if($file->is_img() && $file->is_less()){
                if($file->move_uploaded_file()){
                    $application->orm[$k] = $file->saveFilename;
                    $oldFileArr[] = $applicationOldInfo[$k];
                }
            } else {
                $request->file->cleanAll();
                return $this->fail( '上传类型不为图片, 或者大于8m');
            }
        }
        
        // 写入数据库, 若失败则删除已保存的文件
        try{
            $res = $application->save($applicationOldInfo['id']);
            $this->clean($oldFileArr);
            return $this->returnData($res);
        }catch(PDOException $pdo){
            $request->file->cleanAll();
            return $this->fail( $pdo->getMessage());
        }
    }

    /**
     * 删除应用信息
     * @return type
     */
    public function destroy(Request $request, UserApplication $application) {
        $userid = (int)$request->userinfo['id'];
        $app_id = (int)$request->input('id');
        // 原数据
        $applicationOldInfo = $application->getInfoByIdWithMerchant($app_id, $userid );
        if(empty($applicationOldInfo))
            return $this->fail( '要删除的应用不存在');
        //数据库中的文件字段,都以 _file 结尾 eg : organization_file
        $end_string = '_file';
        // 将要被替换的文件
        $oldFileArr = []; 
        foreach($applicationOldInfo as $k => $v){
            if( strrchr($k, $end_string) === $end_string ){
                $oldFileArr[] = $v;
            }
        }
        
        try{
            $res = $application->delById( $applicationOldInfo['id'] );
            $this->clean($oldFileArr);
            return $this->returnData($res);
        }catch(PDOException $pdo){
            return $this->fail( $pdo->getMessage());
        }
    }
    
    /**
     * 删除数组中的文件
     * @param array $arr
     */
    private function clean(array $arr){
        foreach ($arr as $v) {
            if(is_file(ROOT.$v) && !is_dir(ROOT.$v)){
                unlink(ROOT.$v);
            }elseif(is_file($v) && !is_dir($v)){
                unlink($v);
            }
        }
    }
}
