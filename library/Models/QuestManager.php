<?php

class Models_QuestManager extends App_Zenddb {
    
     public function getUserIdByPhoneOrEmail($phone, $email) {
        $select = $this->db->select()
                           ->from(array('t' => 'tbl_users'), array('t.idtbl_users'))
                           ->where('t.Tel = ?', $phone)
                           ->orWhere('t.Email = ?', $email);
        $result = $this->db->fetchRow($select);
        return $result['idtbl_users'];
    }
    
    public function saveUser($data){
         $this->db->insert('tbl_users', $data);
         
    }
    
    public function selectIDFromActivationRegistration($email){
        $select = $this->db->select()
                           ->from(array('t' => 'tbl_registration'), array('t.idtbl_registration'))
                           ->where('Mail = ?', $email);
        $result = $this->db->fetchRow($select);
        return $result['idtbl_registration'];
    }
        
    public function selectPhoneFromCustumers($email){
        $select=$this->db->select()
                         ->from(array('t' => 'tbl_users'),array('t.Tel'))
                         ->where('Email = ?', $email);
        $result = $this->db->fetchRow($select);
        return $result['Tel'];
    }
    
    public function updateCustomersActivateUser($email){
        $where = array($this->db->quoteInto('Email = ?', $email));
        $this->db->update('tbl_users', array('Lock' => '0'), $where);
      
    }
    
    public function selectUserByEmail($email){
        $select=$this->db->select()
                         ->from(array('t' => 'tbl_users'),array('t.idtbl_users'))
                         ->where('Email = ?', $email);
        $result = $this->db->fetchRow($select);
        return $result['idtbl_users'];
    }
    
    public function updatePass($pass, $email){
        $where = array($this->db->quoteInto('Email = ?', $email));
        $this->db->update('tbl_users', array('pass' => $pass), $where);
    }
    
    public function getUser($type, $data, $pass){
        $select = $this->db->select()
                         ->from(array('t' => 'tbl_users'),array('t.idtbl_users'))
                         ->where($type.' = ?', $data)
                         ->where('pass = ?', $pass)
                         ->where('`Lock` = 0')
                         ->limit('1');
        $result = $this->db->fetchRow($select);
        if(!empty($result['idtbl_users']))
            return $result['idtbl_users'];
        else 
            return false;
    }
    
    public function getRegions(){
        $lang = Zend_Registry::get('uds_lang');
        $select = $this->db->select()
                         ->from(array('t' => 'tbl_city'),array('t.idtbl_city', $lang.'_name'))
            ->where('t.parentID = 0');
        $result = $this->db->fetchAll($select);
        
        $res = array();
        foreach($result as $row){
            $res[$row['idtbl_city']] = $row[$lang.'_name'];
        }
        return $res;
    }
    
    public function getCityByID($id){
        $lang = Zend_Registry::get('uds_lang');
        $select = $this->db ->select()
                            ->from(array('t' => 'tbl_city'),array('t.idtbl_city', $lang.'_name'))
                            ->where('t.parentID =?',$id);
        $result = $this->db->fetchAll($select);
        $res = array();
        foreach($result as $row){
            $res[$row['idtbl_city']] = $row[$lang.'_name'];
        }
        return $res;
    }
    public function getCity(){
        $lang = Zend_Registry::get('uds_lang');
        $select = $this->db->select()
                           ->from(array('t' => 'tbl_city'),array('t.idtbl_city', $lang.'_name'))
                           ->where('parentID =1');
        $result = $this->db->fetchAll($select);
        $res = array();
        foreach($result as $row){
            $res[$row['idtbl_city']] = $row[$lang.'_name'];
        }
        return $res;
    }
    
    public function saveActivateRegistration($data){
        $this->db->insert('tbl_registration', $data);
//         return $this->db->lastInsertId();
    }
    
    public function updateUser($id, $data){
        $where = array($this->db->quoteInto('idtbl_users = ?', $id));
        $this->db->update('tbl_users', $data, $where);
    }
    public function getUserData($id){
        $select = $this->db->select()
                         ->from(array('t' => 'tbl_users'))
                         ->where('idtbl_users = ?', $id)
                         ->where('`Lock` = 0')
                         ->limit('1');
        $result = $this->db->fetchRow($select);
        if(!empty($result))
            return $result;
        else 
            return false;
    }
    public function getClinicType(){
        $select = $this->db->select()
                ->from(array('tbl_clinic_type'));
        return $this->db->fetchAll($select);
    }
    
    public function getDoctorType(){
        $select = $this->db->select()
                ->from(array('tbl_type_doc'));
        $result = $this->db->fetchAll($select);
        return $result;
    }
    public function getHospitals(){
        $select = $this->db->select()
                ->from(array('tbl_clinic'));
        $result = $this->db->fetchAll($select);
        return $result;
    }
    
    public function getcitiesbyregion($id){
        $select = $this->db->select()
                         ->from(array('t' => 'tbl_city'),array('t.idtbl_city', 'ua_name'))
            ->where('t.parentID =?',$id);
        return $this->db->fetchAll($select);
        
    }
    
    public function getdoctorstypeforcity($city_id, $clinic_type_id = null, $text = null, $lang = null){
        $select = $this->db->select()
            ->from(array('t' => 'vw_type_doc_city'),array('t.TypeDocID', $lang.'_name'));
        if(!empty($city_id)) 
            $select->where('t.CityID =?',$city_id);
        if(!empty($clinic_type_id))
            $select->where('t.TypeClinicID =?',$clinic_type_id);
        if(!empty($text))
            $select->where("t.".$lang."_name like '%".$text."%'");
        $select->group('TypeDocID');
//        echo $select->assemble();
//        die;
        return $this->db->fetchAll($select);
    }
    
    public function gethospital($params){
        $select = $this->db->select()
            ->from(array('t' => 'vw_clinic_list'),array('*'));
        if(!empty($params['cityID'])) 
            $select->where('t.ClinicCityID =?',$params['cityID']);
        if(!empty($params['regionID'])) 
            $select->where('t.RegionID =?',$params['regionID']);
        if(!empty($params['clinicType']))
            $select->where('t.ClinicTypeID =?',$params['clinicType']);
        if(!empty($params['doctortype']))
            $select->where("t.TypeDocID =?",$params['doctortype']);
        $select->group('ClinicID');
        $select->order('Rating DESC');
        return $this->db->fetchAll($select);
    }
    
    public function getcalendar($params){
        $select = $this->db->select()
            ->from(array('t' => 'vw_calendar_list'),array('*'));
        if(!empty($params['clinicID'])) 
            $select->where('t.ClinicID =?',$params['clinicID']);
        if(!empty($params['doctorTypeID'])) 
            $select->where('t.TypeDocID =?',$params['doctorTypeID']);
        
        $res =$this->db->fetchAll($select);
        if(!empty($params['clinicID'])) 
            $select1 = $this->db->select()->from(array('t' => 'vw_clinic_list'),array('*'))->where('t.ClinicID =?',$params['clinicID']);
        $info =$this->db->fetchRow($select1);
        
        foreach($res as &$r){
            foreach ($r as &$row){
                htmlspecialchars_decode($row);
            }
        }
        return array('schedule'=>$res, 'info'=>$info);
        
    }
    
    public function getPage($tableName, $lang){
    $select = $this->db->select()
            ->from(array('t' => 'tbl_page'), array('text'))
            ->where('lang = ?', $lang)
            ->where('slug = ?', $tableName);
    $result = $this->db->fetchOne($select);
    return $result;
    }
}
