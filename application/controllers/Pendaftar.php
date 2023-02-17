<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pendaftar extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->SecurityModel->roleOnlyGuard('pendaftar');
        $this->load->model(array('PendaftarModel', 'ParameterModel'));
    }
    function cek_status()
    {
        // $status =  $this->PendaftarModel->cek_status(['id_user' => $this->session->userdata('id_user')]);
        $status =  $this->PendaftarModel->cek_status(['id_user' => $this->session->userdata('id_user')]);
        if (empty($status)) {
            redirect(base_url('pendaftar/pre'));
        }
        echo json_encode($status);
    }
    public function index()
    {
        $this->SecurityModel->roleOnlyGuard('pendaftar');
        $this->load->model('UserModel');
        $user = $this->PendaftarModel->cek_status(['id_user' => $this->session->userdata()['id_user']])[0];

        $ret_data = $user;

        $pageData = array(
            'title' => 'Data Pengguna',
            'content' => 'pendaftar/pre',
            'breadcrumb' => array(
                'Home' => base_url(),
            ),
            'contentData' => array(),
            'ret_data' => $ret_data
        );
        $this->load->view('Page', $pageData);
    }

    public function pre()
    {
        try {
            $this->load->model('UserModel');
            $data =  $this->input->post();
            $user = $this->UserModel->getAllUser(['id_user' => $this->session->userdata()['id_user']])[$this->session->userdata()['id_user']];
            // var_dump($_FILES);
            if (!empty($_FILES['file_ktp']['name'])) {
                $config['upload_path']          = './upload/ktp';
                $config['allowed_types']        = 'jpeg|jpg|png';
                $config['encrypt_name']             = true;
                $config['max_size']             = 300;
                $this->load->library('upload', $config);
                if (!$this->upload->do_upload('file_ktp')) {
                    throw new UserException($this->upload->display_errors(), UPLOAD_FAILED_CODE);
                } else {
                    $ktp = $this->upload->data();
                    $data['file_ktp'] = $ktp['file_name'];
                    // echo "ktp" . $data['file_ktp'];
                }
            }
            unset($this->upload);
            if (!empty($_FILES['swa_foto']['name'])) {
                $config2['upload_path']          = './upload/swa_foto';
                $config2['allowed_types']        = 'jpeg|jpg|png';
                $config2['encrypt_name'] = true;
                $config2['max_size']             = 500;
                $this->load->library('upload', $config2);
                if (!$this->upload->do_upload('swa_foto')) {
                    throw new UserException($this->upload->display_errors(), UPLOAD_FAILED_CODE);
                } else {
                    $swa = $this->upload->data();
                    // var_dump($swa);
                    // var_dump($config2);
                    $data['swa_foto'] = $swa['file_name'];
                    // echo "swa_foto" . $data['swa_foto'];
                }
            }
            // die();
            $data['status_data'] = 1;
            if (!empty($user['id_data'])) {
                $data['id_data'] = $user['id_data'];
                $this->PendaftarModel->editData($data);
            } else {
                $this->PendaftarModel->addData($data);
            }
            echo json_encode($user);
        } catch (Exception $e) {
            ExceptionHandler::handle($e);
        }
    }


    public function mulai_ujian()
    {
        try {
            $this->SecurityModel->userOnlyGuard(TRUE);
            $this->SecurityModel->roleOnlyGuard('pendaftar');

            $pr = $this->ParameterModel->getMyExam();
            // echo json_encode($pr);
            // die();
            $pageData = array(
                'title' => 'Daftar Layanan',
                'content' => 'pendaftar/layanan',
                'breadcrumb' => array(
                    'Home' => base_url(),
                ),
                'dataContent' => ['perizinan' => $pr]
            );

            $this->load->view('Page', $pageData);
        } catch (Exception $e) {
            ExceptionHandler::handle($e);
        }
    }

    public function riwayat()
    {
        try {
            $this->SecurityModel->userOnlyGuard(TRUE);
            $this->SecurityModel->roleOnlyGuard('pendaftar');

            $pageData = array(
                'title' => 'Riwayat',
                'content' => 'pendaftar/riwayat',
                'breadcrumb' => array(
                    'Home' => base_url(),
                ),
            );
            $this->load->view('Page', $pageData);
        } catch (Exception $e) {
            ExceptionHandler::handle($e);
        }
    }



    public function delete_task()
    {
        try {
            $this->SecurityModel->rolesOnlyGuard(array('guru'), true);
            $data = $this->input->post();
            $this->GuruModel->delete_task($data);
            // $this->KelolahmapelModel->getKelolahuser($idKelolahuser);
            echo json_encode(array('data' => $data));
        } catch (Exception $e) {
            ExceptionHandler::handle($e);
        }
    }

    public function getYourHistory()
    {
        try {
            $this->SecurityModel->userOnlyGuard(TRUE);
            // $filter['id_user'] = $this->session->userdata()['id_user'];
            if (!empty($this->session->userdata()['id_user']))
                $filter['id_user'] = $this->session->userdata()['id_user'];

            $data = $this->ParameterModel->getYourHistory($filter);
            echo json_encode(array('data' => $data));
        } catch (Exception $e) {
            ExceptionHandler::handle($e);
        }
    }


    public function getAvaliableSession()
    {
        try {

            $this->SecurityModel->userOnlyGuard(TRUE);
            $data = $this->ParameterModel->getAvaliableSession();
            echo json_encode(array('data' => $data));
        } catch (Exception $e) {
            ExceptionHandler::handle($e);
        }
    }

    public function daftarSessionExam()
    {
        try {
            $this->SecurityModel->userOnlyGuard(TRUE);
            $dataPost = $this->input->post();
            $filter['id_user'] = $this->session->userdata()['id_user'];

            $i = 0;
            $shuffle = '';
            $pr = $this->ParameterModel->getAllMapel(['id_perizinan' => $dataPost['id_perizinan']])[$dataPost['id_perizinan']];

            $data = $this->ParameterModel->getAllBankSoal(array('id_perizinan' => $dataPost['id_perizinan'], 'limit' => $pr['jml_soal'], 'order_random' => true, 'result_array' => true));
            if (empty($data))
                throw new UserException("Soal tidak ditemukan!", USER_NOT_FOUND_CODE);

            shuffle($data);
            foreach ($data as $d) {
                if ($i == 0)
                    $shuffle .= $d['id_bank_soal'];
                else
                    $shuffle .= ',' . $d['id_bank_soal'];
                $i++;
            }
            // echo json_encode($data);
            // die();



            $create = array(
                'id_perizinan' => $dataPost['id_perizinan'],
                'generate_soal' => $shuffle,
                'nama_badan' => $dataPost['nama_badan'],
                'alamat_badan' => $dataPost['alamat_badan']
            );
            $id = $this->ParameterModel->createExam($create);
            // $filter['id_session_exam_user'] = $id;

            $data = $this->ParameterModel->getExam($filter)[$id]['token'];

            echo json_encode(array('data' => $data));
        } catch (Exception $e) {
            ExceptionHandler::handle($e);
        }
    }

    public function SubmitExam()
    {
        try {
            $data = $this->input->post();
            $ans = '';
            for ($i = 0; $i < $data['count']; $i++) {
                if ($i == 0) {
                    if (!empty($data['row_' . $i])) {
                        $ans .=  $data['row_' . $i];
                    } else {
                        $ans .= '0';
                    }
                } else {
                    if (!empty($data['row_' . $i])) {
                        $ans .= ',' . $data['row_' . $i];
                    } else {
                        $ans .= ',0';
                    }
                }
            }
            $data['answer'] = $ans;
            $this->ParameterModel->SubmitExam($data);

            if ($data['autosave'] == 'false') {
                $this->calculateScore($data['token'], true);
                echo json_encode(['error' => false, 'reload' => true]);
            } else
                echo json_encode(['error' => false]);
        } catch (Exception $e) {
            ExceptionHandler::handle($e);
        }
    }

    public function ujian($token)
    {
        try {
            $this->SecurityModel->userOnlyGuard(TRUE);
            $filter['token'] = $token;
            // $filter['id_user'] = $this->session->userdata()['id_user'];
            if (!empty($this->session->userdata()['id_user']))
                $filter['id_user'] = $this->session->userdata()['id_user'];
            else {
                throw new UserException("Silahkan login ..", USER_NOT_FOUND_CODE);
            }

            $cur_date = date('Y-m-d H:i:s');

            $data = $this->ParameterModel->getExam($filter);
            if (!empty($data)) {
                $data = $data[$token];
                $ex_soal = explode(',', $data['generate_soal']);
            }
            // echo json_encode($data);
            // die();
            // if ($data['open_start'] > $cur_date) {
            //     throw new UserException("Ujian belum dimulai", USER_NOT_FOUND_CODE);
            // }
            // if (empty($data['start_time'])) {
            //     $filter['start_time'] = $cur_date;
            //     $data['start_time'] = $cur_date;
            //     $filter['id_session_exam_user'] = $data['id_session_exam_user'];

            //     $this->ParameterModel->startExam($filter);
            //     // throw new UserException("Ujian belum dimulai", USER_NOT_FOUND_CODE);
            // }
            // else {
            //     echo 'blm_mulai';
            // }
            // die();

            $c = count($ex_soal);
            // var_dump($data);
            $dateTime = new DateTime($data['start_time']);
            $dateTime->modify('+' . $data['limit_time'] . ' minutes');
            $t1 = ($dateTime->format("Y-m-d H:i:s"));

            $start = date_create(date("Y-m-d H:i:s"));
            // $start = date_create('2021-07-14 15:39:20');
            $end = date_create($t1);

            $start2 = strtotime(date("Y-m-d H:i:s"));
            $end2 = strtotime($t1);
            $diff = date_diff($end, $start);

            if ($end2 < $start2 or $data['exam_lock'] == 'Y') {
                if (empty($data['score']))
                    $this->calculateScore($data['token'], true);
                $this->pageresult($data);
                return;
            }
            $timer = $diff->h * 60 * 60;
            $timer = $timer + $diff->i * 60;
            $timer = $timer + $diff->s;
            if (!empty($data['answer'])) {
                $ans = explode(',', $data['answer']);
            } else {
                for ($j = 0; $j < $c; $j++)
                    $ans[$j] = 0;
            }
            $i = 0;
            $btn = '';
            foreach ($ex_soal as $ex) {
                $exs = $this->ParameterModel->getShuffleSoal($ex);
                $data_soal[$i] = $exs;
                if ($ans[$i] == '0' or $ans[$i] == '-')
                    $btn .= '<a data-toggle="pill" class="nav-link btn btn-primary mr-1 mt-1" id="ans_' . $i . '" href="#soal_' . $i . '" role="tab">' . ($i + 1) . '</a>';
                else
                    $btn .= '<a data-toggle="pill" class="nav-link btn btn-success mr-1 mt-1" id="ans_' . $i . '" href="#soal_' . $i . '" role="tab">' . ($i + 1) . '</a>';
                $i++;
            }

            // echo json_encode(array('data' => $data_soal));
            // $this->SecurityModel->userOnlyGuard(TRUE);
            $pageData = array(
                'title' => 'Ujian',
                // 'content' => 'public/MyTask',
                'breadcrumb' => array(
                    'Home' => base_url(),
                ),
                'data_soal' => $data_soal,
                'btn' => $btn,
                'ans' => $ans,
                'token' => $token,
                'timer' => $timer
            );
            $this->load->view('PageExam', $pageData);
        } catch (Exception $e) {
            ExceptionHandler::handle($e);
        }
    }

    function pembahasan($token)
    {
        try {
            // $this->SecurityModel->userOnlyGuard(TRUE);
            $data = $this->ParameterModel->getExam(['token' => $token])[$token];
            // echo json_encode($data);
            // die();
            if (!empty($data)) {
                $data = $data;
                $ex_soal = explode(',', $data['generate_soal']);
            }
            $c = count($ex_soal);
            // var_dump($data);

            if (!empty($data['answer'])) {
                $ans = explode(',', $data['answer']);
            } else {
                for ($j = 0; $j < $c; $j++)
                    $ans[$j] = 0;
            }
            $i = 0;
            $btn = '';
            foreach ($ex_soal as $ex) {
                $exs = $this->ParameterModel->getShuffleSoal($ex, true);
                $data_soal[$i] = $exs;
                if (empty($ans[$i]))
                    $btn .= '<a data-toggle="pill" class="nav-link btn btn-secondary mr-1 mt-1" id="ans_' . $i . '" href="#soal_' . $i . '" role="tab">' . ($i + 1) . '</a>';
                else if ($exs['soal']['token_opsi'] != $ans[$i])
                    $btn .= '<a data-toggle="pill" class="nav-link btn btn-danger mr-1 mt-1" id="ans_' . $i . '" href="#soal_' . $i . '" role="tab">' . ($i + 1) . '</a>';
                else if ($exs['soal']['token_opsi'] == $ans[$i])
                    $btn .= '<a data-toggle="pill" class="nav-link btn btn-success mr-1 mt-1" id="ans_' . $i . '" href="#soal_' . $i . '" role="tab">' . ($i + 1) . '</a>';
                $i++;
            }

            $pageData = array(
                'title' => 'Pembahasan',
                // 'content' => 'public/MyTask',
                'breadcrumb' => array(
                    'Home' => base_url(),
                ),
                'dataContent' => $data,
                'data_soal' => $data_soal,
                'btn' => $btn,
                'ans' => $ans,
            );
            $this->load->view('PagePembahasan', $pageData);
        } catch (Exception $e) {
            ExceptionHandler::handle($e);
        }
    }
    function pageresult($data)
    {
        try {
            // $this->SecurityModel->userOnlyGuard(TRUE);
            $pageData = array(
                'title' => 'Pre Register',
                'content' => 'pendaftar/result',
                'breadcrumb' => array(
                    'Home' => base_url(),
                ),
                'contentData' => $data,
            );
            $this->load->view('Page', $pageData);
        } catch (Exception $e) {
            ExceptionHandler::handle($e);
        }
    }

    function calculateScore($token, $restric = false)
    {
        $data = $this->ParameterModel->getExam(array('token' => $token))[$token];
        $dateTime = new DateTime($data['start_time']);
        $dateTime->modify('+' . $data['limit_time'] . ' minutes');
        $t1 = ($dateTime->format("Y-m-d H:i:s"));


        $start2 = strtotime(date("Y-m-d H:i:s"));
        $end2 = strtotime($t1);
        if ($end2 + 6 < $start2) {
        } else {
            $i = 1;
            $ans = '';
            $ans_ex = explode(',', $data['answer']);
            $i = 0;
            $jwb_terisi = 0;
            foreach ($ans_ex as $an) {
                if (!empty($an)) {
                    $jwb_terisi++;
                }
                if ($i == 0) {
                    $i++;
                    $ans .= '"' . $an . '"';
                } else {
                    $ans .= ',"' . $an . '"';
                }
            }
            // var_dump($ans);
            // die();
            $this->ParameterModel->calculateScore($data, $data['generate_soal'], $ans, $jwb_terisi);
        }
        // echo 'rest';
        // die();

        // $data['point_mode'] = 'avg';
        $i = 1;
        $ans = '';
        $ans_ex = explode(',', $data['answer']);
        $i = 0;
        $jwb_terisi = 0;
        foreach ($ans_ex as $an) {
            if (!empty($an)) {
                $jwb_terisi++;
            }
            if ($i == 0) {
                $i++;
                $ans .= '"' . $an . '"';
            } else {
                $ans .= ',"' . $an . '"';
            }
        }
        // var_dump($ans);
        // die();
        $this->ParameterModel->calculateScore($data, $data['generate_soal'], $ans, $jwb_terisi);
        if (!$restric)
            echo json_encode(array('error' => false));
        // die();
    }
}
