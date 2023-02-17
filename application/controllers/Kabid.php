<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kabid extends CI_Controller
{

    // public function __construct()
    // {
    //     parent::__construct();
    // }

    public function index()
    {
        $this->SecurityModel->roleOnlyGuard('kabid');
        $pageData = array(
            'title' => 'Beranda',
            'content' => 'Dashboard',
            'breadcrumb' => array(
                'Home' => base_url(),
            ),
        );
        $this->load->view('Page', $pageData);
    }

    public function hasil_ujian()
    {
        $this->SecurityModel->roleOnlyGuard('kabid');
        $pageData = array(
            'title' => 'Jadwal Ujian',
            'content' => 'admin/jadwal_ujian',
            'breadcrumb' => array(
                'Home' => base_url(),
            ),
        );
        $this->load->view('Page', $pageData);
    }
}
