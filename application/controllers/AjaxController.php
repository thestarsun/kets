<?php

class AjaxController extends Zend_Controller_Action {

    private $_managerQ;
    private $_month;
    
    public function init() {
        $this->_managerQ = new Models_QuestManager();
        $this->_month = array(1=>"January", 2=>"February", 3=>"March", 4=>"April", 5=>"May", 6=>'June', 7=> "July", 8=>"August", 9=>"September", 11=>"November", 10=>"October", 12=>"December");
    }

    public function indexAction() {
        
    }
    public function calendarAction(){
        $this->_helper->layout()->disableLayout();
        $params = $this->_getAllParams();
        $this->view->month = $this->_month[$params['month']];
        $this->view->monthNumber = $params['month'];
        $this->view->year = $params['year'];
        $this->view->L = $params['l'];
        $this->view->week_start = $params['day'];
    }

    public function cityAction() {
        $this->view->cities = $this->_managerQ->getCityByID($this->_getParam('id'));
        $this->_helper->layout()->disableLayout();
//        $this->renderScript('/default/ajax/city.phtml');
    }
    
    public function getcitiesforregionAction(){
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->json(array('data'=> json_encode($this->_managerQ->getcitiesbyregion($this->_getParam('regionID')))));
        
    }
    
    public function getdoctorstypeforcityAction(){
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->json(array('data'=> json_encode($this->_managerQ->getdoctorstypeforcity($this->_getParam('cityID'), $this->_getParam('clinicType'), $this->_getParam('searchText'), $this->_getParam('uds_lang')))));
    }
    
    public function gethospitalAction(){
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->json(array('data'=> json_encode($this->_managerQ->gethospital($this->_getAllParams()))));
    }
    
    public function getcalendarAction(){
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $calendar = $this->_managerQ->getcalendar($this->_getAllParams());
        $this->_helper->json(array('data'=> json_encode($calendar['schedule']), 'info' =>json_encode($calendar['info'])));
    }
}

