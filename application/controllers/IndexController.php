<?php

class IndexController extends Zend_Controller_Action
{

    private $qM;
    
    public function init()
    {
       $this->qM = new Models_QuestManager();
    }

    public function indexAction()
    {
       $this->view->lang = Zend_Registry::get('uds_lang');
       $this->view->clinicType = $this->qM->getClinicType();
       $this->view->regions = $this->qM->getRegions();
       $this->view->city = $this->qM->getCity();
       $this->view->doctorType = $this->qM->getdoctorstypeforcity(25, 0, 0, Zend_Registry::get('uds_lang'));
       $this->view->hospitals = $this->qM->getHospitals();
       $this->view->month = date('F');
       $this->view->monthNumber = date('n');
       $this->view->year = date('Y');
       $this->view->L = date('L');
       $week_day = date('N');
       $this->view->week_start = ($week_day == 1)?date('j'):date('j')+1-date('N');
//       $this->view->week_end = ($week_day == 7)?date('j'):7-date('N')+date('j');
       
       if($this->getRequest()->isGET()){
           $params = $this->_getAllParams();
//           echo "<pre>";
//           var_dump($params);
//           echo "</pre>";
//           die('++');
           if(!empty($params['clinicID'])&&!empty($params['doctorTypeID'])){
               $this->view->calendar = $this->qM->getcalendar($this->_getAllParams());
//               echo "<pre>";
//               var_dump($this->view->calendar["schedule"]);
//               echo "</pre>";
//               die('++');
           }
//           die('++');
       }
    }
    public function forclinicsAction(){
        $this->view->staticData = $this->qM->getPage('for_clinic', Zend_Registry::get('uds_lang'));
        
    }
    
    public function aboutserviceAction(){
        $this->view->staticData = $this->qM->getPage('about_service', Zend_Registry::get('uds_lang'));
        
    }


}

