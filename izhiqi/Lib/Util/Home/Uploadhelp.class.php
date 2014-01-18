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
    static function upload ($files, $savepah,$maxSize)
    {
        import('ORG.Net.UploadFile');
   
        $upload = new UploadFile(); // 实例化上传类
        
        $upload->maxSize = $maxSize; // 设置附件上传大小
        
        $upload->allowExts = array(
                'jpg',
                'jpeg'
        ); // 设置附件上传类型
        
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
        $maxSize=46080;
        return Uploadhelp::upload($files, $savepath,$maxSize);
    
    }
    /**
     * 上传证件
     * @return multitype:number NULL string
     */
    static public function uploadCertificate($files)
    {

        $savepath=C('UPLOAD_CERTIFICATE');
        $maxSize=300000;
        return Uploadhelp::upload($files, $savepath,$maxSize);
    
    }
    /**
     * 上传logo
     * @return multitype:number NULL string
     */
    static public function uploadLogo($files)
    {

        $savepath=C('UPLOAD_LOGO');
        $maxSize=46080;
        return Uploadhelp::upload($files, $savepath,$maxSize);
    
    }
}