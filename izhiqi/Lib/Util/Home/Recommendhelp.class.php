<?php
class Recommendhelp
{
	public static function insertRecommend($data){
        D('Recommend')->data($data)->add();
        return D('Recommend')->getLastInsID();
	}
	public static function updateRecommend($where,$data){
        return D('Recommend')->where($where)->save($data);
	}
	public static function getRecommend($where,$order){
        return D('Recommend')->where($where)->order($order)->find();
	}
	public static function getRecommendList($where,$order,$limit,$field){
        return D('Recommend')->where($where)->order($order)->field($field)->limit($limit)->select();
	}
	public static function getRecommendCount($where){
        return D('Recommend')->where($where)->count();
	}
}

?>