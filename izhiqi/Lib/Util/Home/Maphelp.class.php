<?php
class Maphelp
{
    public static function getCompanyScale($key)
    {
        $arrCompanyScale=C('COMPANY_SCALE');
        $val=$arrCompanyScale[$key];
        if(!$val){
            $val='';
        }
        return $val;
    }
    
}
?>
