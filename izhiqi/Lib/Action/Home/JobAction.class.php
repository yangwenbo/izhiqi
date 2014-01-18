<?php
class JobAction extends Action
{
	public function getJobclassSon()
	{
		$id=intval($this->_param("id"))?intval($this->_param("id")):0;
		$result=Jobhelp::getJobclassListById($id);
		if($result)
		{
           $this->ajaxReturn($result,'',100,'JSON');
		}
		$this->ajaxReturn(array(),'获取的子列表不存在',101,'JSON');
	}
	
}

?>