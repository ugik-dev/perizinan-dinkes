<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MasterModel extends CI_Model
{

    public function getAllPeraturan($filter = [])
    {
        $this->db->from('ref_menkes');
        if (!empty($filter['menkes_id'])) $this->db->where('menkes_id', $filter['menkes_id']);
        $res = $this->db->get();
        return DataStructure::keyValue($res->result_array(), 'menkes_id');
    }

    public function addPeraturan($data)
    {
        $dataInsert = DataStructure::slice($data, ['menkes_tentang', 'menkes_nama']);
        $this->db->insert('ref_menkes', $dataInsert);
        ExceptionHandler::handleDBError($this->db->error(), "Insert Peraturan", "Peraturan");
        return $this->db->insert_id();
    }

    public function editPeraturan($data)
    {
        $dataInsert = DataStructure::slice($data, ['menkes_tentang', 'menkes_nama']);
        $this->db->set($dataInsert);
        $this->db->where('menkes_id', $data['menkes_id']);
        $this->db->update('ref_menkes');
        ExceptionHandler::handleDBError($this->db->error(), "Insert Peraturan", "Peraturan");
        return  $data['menkes_id'];
    }

    public function deletePeraturan($data)
    {
        $this->db->where('menkes_id', $data['menkes_id']);
        $this->db->delete('ref_menkes');
        ExceptionHandler::handleDBError($this->db->error(), "Insert Peraturan", "Peraturan");
    }

    // sktim 
    public function getAllSKTim($filter = [])
    {
        $this->db->from('ref_sktim');
        if (!empty($filter['sktim_id'])) $this->db->where('sktim_id', $filter['sktim_id']);
        $res = $this->db->get();
        return DataStructure::keyValue($res->result_array(), 'sktim_id');
    }

    public function addSKTim($data)
    {
        $dataInsert = DataStructure::slice($data, ['sktim_nama']);
        $this->db->insert('ref_sktim', $dataInsert);
        ExceptionHandler::handleDBError($this->db->error(), "Insert SKTim", "SKTim");
        return $this->db->insert_id();
    }

    public function editSKTim($data)
    {
        $dataInsert = DataStructure::slice($data, ['sktim_nama']);
        $this->db->set($dataInsert);
        $this->db->where('sktim_id', $data['sktim_id']);
        $this->db->update('ref_sktim');
        ExceptionHandler::handleDBError($this->db->error(), "Insert SKTim", "SKTim");
        return  $data['sktim_id'];
    }

    public function deleteSKTim($data)
    {
        $this->db->where('sktim_id', $data['sktim_id']);
        $this->db->delete('ref_sktim');
        ExceptionHandler::handleDBError($this->db->error(), "Insert SKTim", "SKTim");
    }

    // perizinan

    public function getAllPerizinan($filter = [])
    {
        $this->db->from('ref_perizinan');
        $this->db->join('ref_menkes', 'menkes_active = menkes_id', 'LEFT');
        $this->db->join('ref_sktim', 'sktim_active = sktim_id', 'LEFT');
        if (!empty($filter['id_perizinan'])) $this->db->where('id_perizinan', $filter['id_perizinan']);
        $res = $this->db->get();
        return DataStructure::keyValue($res->result_array(), 'id_perizinan');
    }

    public function addPerizinan($data)
    {
        $dataInsert = DataStructure::slice($data, ['nama_perizinan', 'status', 'durasi', 'passinggrade', 'jml_soal', 'icon', 'menkes_active', 'sktim_active']);
        $this->db->insert('ref_perizinan', $dataInsert);
        ExceptionHandler::handleDBError($this->db->error(), "Insert Perizinan", "Perizinan");
        return $this->db->insert_id();
    }

    public function editPerizinan($data)
    {
        $dataInsert = DataStructure::slice($data, ['nama_perizinan', 'status', 'durasi', 'passinggrade', 'jml_soal', 'icon', 'menkes_active', 'sktim_active']);
        $this->db->set($dataInsert);
        $this->db->where('id_perizinan', $data['id_perizinan']);
        $this->db->update('ref_perizinan');
        ExceptionHandler::handleDBError($this->db->error(), "Insert Perizinan", "Perizinan");
        return  $data['id_perizinan'];
    }

    public function deletePerizinan($data)
    {
        $this->db->where('id_perizinan', $data['id_perizinan']);
        $this->db->delete('ref_perizinan');
        ExceptionHandler::handleDBError($this->db->error(), "Insert Perizinan", "Perizinan");
    }
}
