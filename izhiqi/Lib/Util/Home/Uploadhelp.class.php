<?php
/**
 * 上传的公共方法
 * */
class Uploadhelp
{
    /**
     * 上传
     * @param unknown $files
     * @param unknown $savepah
     * @return unknown
     */
    static function upload ($files, $savepah, $maxSize, $bRemoveOrigin)
    {
        import('ORG.Net.UploadFile');
   
        $upload = new UploadFile(); // 实例化上传类

         //设置上传文件类型
        $upload->allowExts = explode(',', 'jpg,gif,png,jpeg');
         //设置附件上传目录
         //设置需要生成缩略图，仅对图像文件有效
        $upload->thumb = true;
         // 设置引用图片类库包路径
        //$upload->imageClassPath = '@.ORG.Image';
        $upload->thumbPrefix = C('AVATAR_THUMB_PREFIX');
         //设置缩略图最大宽度
        $upload->thumbMaxWidth = '130';
         //设置缩略图最大高度
        $upload->thumbMaxHeight = '130';
         //设置上传文件规则
        $upload->saveRule = 'uniqid';
         //删除原图
        $upload->thumbRemoveOrigin = $bRemoveOrigin;

        $upload->maxSize = $maxSize; // 设置附件上传大小
        
        $dirname = (string) date('Y-m-d') . '/';
        
        $path = $savepah .'/'. $dirname;
        
        if (! file_exists($path)) {

            mkdir($path, 0777);
        }
        
        $upload->savePath = $path; // 设置附件上传目录
        
        $result = array();
        $info=$upload->uploadOne($files);
        if (!$info) { // 上传错误提示错误信息

            $result['code'] = 101;
            
            $result['info'] = $upload->getErrorMsg();
            
        } else { // 上传成功 获取上传文件信息

            $result['code'] = 100;
            
            $result['info'] = $info[0];
        
        }
        return $result;
    }
    /**
     * 上传头像
     * @param unknown $files
     */
    static public function uploadAvartar($files)
    {
        
        $savepath=C('UPLOAD_AVARTAR');
        $maxSize=4000000;
        return Uploadhelp::upload($files, $savepath, $maxSize, true);
    
    }
    /**
     * 上传证件
     * @return multitype:number NULL string
     */
    static public function uploadCertificate($files)
    {

        $savepath=C('UPLOAD_CERTIFICATE');
        $maxSize=4000000;
        return Uploadhelp::upload($files, $savepath,$maxSize, false);
    
    }
    /**
     * 上传logo
     * @return multitype:number NULL string
     */
    static public function uploadLogo($files)
    {

        $savepath=C('UPLOAD_LOGO');
        $maxSize=4000000;
        return Uploadhelp::upload($files, $savepath,$maxSize, false);
    
    }
}
