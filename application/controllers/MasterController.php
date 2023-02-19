<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MasterController extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('MasterModel'));
    }


    public function getAllPeraturan()
    {
        try {

            $this->SecurityModel->rolesOnlyGuard(['admin']);
            $filter = $this->input->get();
            $data = $this->MasterModel->getAllPeraturan($filter);
            echo json_encode(array('data' => $data));
        } catch (Exception $e) {
            ExceptionHandler::handle($e);
        }
    }

    public function addPeraturan()
    {

        $this->SecurityModel->roleOnlyGuard('admin');
        $data = $this->input->post();
        $id = $this->MasterModel->addPeraturan($data);
        $data = $this->MasterModel->getAllPeraturan(array('menkes_id' => $id))[$id];
        echo json_encode(array('data' => $data));
    }
    public function deletePeraturan()
    {

        $this->SecurityModel->roleOnlyGuard('admin');
        $data = $this->input->post();
        $id = $this->MasterModel->deletePeraturan($data);
        echo json_encode(array('data' => $data));
    }


    public function editPeraturan()
    {

        $this->SecurityModel->roleOnlyGuard('admin');
        $data = $this->input->post();
        $this->MasterModel->editPeraturan($data);
        $data = $this->MasterModel->getAllPeraturan(array('menkes_id' => $data['menkes_id']))[$data['menkes_id']];
        echo json_encode(array('data' => $data));
        // $this->load->view('admin/Pdfallsaranaprasarana', $pageData);
    }
    // SKTIM


    public function getAllSKTim()
    {
        try {

            $this->SecurityModel->rolesOnlyGuard(['admin']);
            $filter = $this->input->get();
            $data = $this->MasterModel->getAllSKTim($filter);
            echo json_encode(array('data' => $data));
        } catch (Exception $e) {
            ExceptionHandler::handle($e);
        }
    }

    public function addSKTim()
    {

        $this->SecurityModel->roleOnlyGuard('admin');
        $data = $this->input->post();
        $id = $this->MasterModel->addSKTim($data);
        $data = $this->MasterModel->getAllSKTim(array('sktim_id' => $id))[$id];
        echo json_encode(array('data' => $data));
    }
    public function deleteSKTim()
    {
        $this->SecurityModel->roleOnlyGuard('admin');
        $data = $this->input->post();
        $id = $this->MasterModel->deleteSKTim($data);
        // $data = $this->ParameterModel->getAllSession(array('id_session_exam' => $id))[$id];
        echo json_encode(array('data' => $data));
    }


    public function editSKTim()
    {

        $this->SecurityModel->roleOnlyGuard('admin');
        $data = $this->input->post();
        $this->MasterModel->editSKTim($data);
        $data = $this->MasterModel->getAllSKTim(array('sktim_id' => $data['sktim_id']))[$data['sktim_id']];
        echo json_encode(array('data' => $data));
        // $this->load->view('admin/Pdfallsaranaprasarana', $pageData);
    }


    // perizinan

    public function getAllPerizinan()
    {
        try {

            $this->SecurityModel->rolesOnlyGuard(['admin']);
            $filter = $this->input->get();
            $data = $this->MasterModel->getAllPerizinan($filter);
            echo json_encode(array('data' => $data));
        } catch (Exception $e) {
            ExceptionHandler::handle($e);
        }
    }

    public function addPerizinan()
    {

        $this->SecurityModel->roleOnlyGuard('admin');
        $data = $this->input->post();
        $id = $this->MasterModel->addPerizinan($data);
        $data = $this->MasterModel->getAllPerizinan(array('id_perizinan' => $id))[$id];
        echo json_encode(array('data' => $data));
    }
    public function deletePerizinan()
    {
        $this->SecurityModel->roleOnlyGuard('admin');
        $data = $this->input->post();
        $id = $this->MasterModel->deletePerizinan($data);
        // $data = $this->ParameterModel->getAllSession(array('id_session_exam' => $id))[$id];
        echo json_encode(array('data' => $data));
    }


    public function editPerizinan()
    {

        $this->SecurityModel->roleOnlyGuard('admin');
        $data = $this->input->post();
        $this->MasterModel->editPerizinan($data);
        $data = $this->MasterModel->getAllPerizinan(array('id_perizinan' => $data['id_perizinan']))[$data['id_perizinan']];
        echo json_encode(array('data' => $data));
        // $this->load->view('admin/Pdfallsaranaprasarana', $pageData);
    }
}
