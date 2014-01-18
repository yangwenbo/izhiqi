<?php

class SearchAction extends Action {
    public function _initialize(){
        $this->assign('tab','search');
    }
    /**
      职位搜索
     */
    public function job() {
        $jobclass_id = intval($this->_param('id')) > 0 ? intval($this->_param('id')) : 1;
        $arrLevel1 = Jobhelp::getLevel1();
        $arrSon = Jobhelp::getJobclassListAll($jobclass_id);
        $this->assign('arrLevel1', $arrLevel1);
        $this->assign('arrSon', $arrSon);
        $this->assign('jobclass_id', $jobclass_id);
        $this->display();
    }

    /**
      职位搜索列表
     */
    public function jobList() {
        $page=intval($this->_param('p'))?intval($this->_param('p')):1;
        $perpage=10;
        $start=($page-1)*$perpage;

        $jobclass_id = intval($this->_param('id'))?intval($this->_param('id')):0;
        $arrJob = Jobhelp::getJobsByJobclassid($jobclass_id,$start,$perpage);
        $arrJobclass=  Jobhelp::getJobclassById($jobclass_id);
        $arrParentJobclass= Jobhelp::getJobclassById($arrJobclass['parent_id']);
        
        if(intval($arrJob['count'])>0)
        {
            import('ORG.Util.Page');
            $objPage=new Page($arrJob['count'],$perpage,$page);
            $this->assign('page',$objPage->show());
        }

        $this->assign('arrJob', $arrJob);
        $this->assign('arrJobclass',$arrJobclass);
        $this->assign('arrParentJobclass',$arrParentJobclass);
        $this->display();
    }
    public function jobInfo()
    {
        $jobid=intval($this->_param('jobid'))?intval($this->_param('jobid')):0;
        $where="id=".$jobid;
        $jobinfo=  Jobhelp::getJob($where);
        $enterpriseinfo=  Enterprisehelp::getEnterpriseinfo($jobinfo['eid']);
        $enterpriseinfo['scale']=  Maphelp::getCompanyScale($enterpriseinfo['scale']);
        $this->assign('jobinfo',$jobinfo);
        $this->assign('enterpriseinfo',$enterpriseinfo);
        
        $this->display();
    }
}

?>