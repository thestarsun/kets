<?php

class IndexController extends Zend_Controller_Action {

    private $qM;

    public function init() {
        $this->qM = new Models_QuestManager();
    }

    public function indexAction() {
        $this->view->lang = Zend_Registry::get('uds_lang');
        $this->view->clinicType = $this->qM->getClinicType();
        $this->view->regions = $this->qM->getRegions();
        $this->view->city = $this->qM->getCity();
        
        $this->view->month = date('F');
        $this->view->monthNumber = date('n');
        $this->view->year = date('Y');
        $this->view->L = date('L');
        $week_day = date('N');
        $this->view->week_start = ($week_day == 1) ? date('j') : date('j') + 1 - date('N');
//       $this->view->week_end = ($week_day == 7)?date('j'):7-date('N')+date('j');

        if ($this->getRequest()->isGET()) {
            $params = $this->_getAllParams();
            if (!empty($params['clinic']) && !empty($params['doctor_speciality'])) {
                $data = array();
                $data['clinicID'] = $params['clinic'];
                $data['doctorTypeID'] = $params['doctor_speciality'];
                $this->view->calendar = $this->qM->getcalendar($data);
            }
            if (!empty($params['city']) && !empty($params['doctor_speciality'])) {
                $data = array();
                $data['cityID'] = $params['city'];
                $data['clinicType'] = $params['clinic_managnent_form'];
                $data['doctortype'] = $params['doctor_speciality'];
                $this->view->hospitals = $this->qM->gethospital($data);
            }
            $params['city'] =(!empty($params['city']))?$params['city']:25;
            $params['clinic_managnent_form'] =(!empty($params['clinic_managnent_form']))?$params['clinic_managnent_form']:0;
            $params['search_doc_spec'] =(!empty($params['search_doc_spec']))?$params['search_doc_spec']:0;
            $this->view->doctorType = $this->qM->getdoctorstypeforcity($params['city'], $params['clinic_managnent_form'], $params['search_doc_spec'], Zend_Registry::get('uds_lang'));
            $this->view->doctor_speciality = !empty($params['doctor_speciality'])?$params['doctor_speciality']:'';
            $this->view->clinic_managnent_form = !empty($params['clinic_managnent_form'])?$params['clinic_managnent_form']:'';
            $this->view->search_doctor_speciality = !empty($params['search_doc_spec'])?$params['search_doc_spec']:'';
        }
    }

    public function forclinicsAction() {
        $this->view->staticData = $this->qM->getPage('for_clinic', Zend_Registry::get('uds_lang'));
    }

    public function aboutserviceAction() {
        $this->view->staticData = $this->qM->getPage('about_service', Zend_Registry::get('uds_lang'));
    }
    
    public function calendarAction(){
//        $this->_helper->layout()->disableLayout();
//        $this->_helper->viewRenderer->setNoRender(true);
        $this->view->calendar = $this->qM->getcalendar($this->_getAllParams());
        $a = Zend_Registry::get('uds_lang');;
        $this->view->lang = strtoupper($a);
    }

}

