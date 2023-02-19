<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sertifikat extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        // $this->SecurityModel->roleOnlyGuard('pendaftar');
        $this->load->model(array('PendaftarModel', 'ParameterModel'));
    }

    public function index($token)
    {
        // $this->SecurityModel->rolesOnlyGuard('pendaftar');
        $this->load->model('UserModel');
        $data = $this->ParameterModel->getSertifikat(['token' => $token])[$token];
        // echo  json_encode($data);
        // die();
        // $user = $this->PendaftarModel->cek_status(['id_user' => $this->session->userdata()['id_user']])[0];
        $this->print_penjamah_makanan($data);
        // $ret_data = $user;
    }

    public function c2($token)
    {
        // $this->SecurityModel->rolesOnlyGuard('pendaftar');
        $this->load->model('UserModel');
        $data = $this->ParameterModel->getSertifikat(['sign_key' => $token])[$token];
        // echo  json_encode($data);
        // die();
        // $user = $this->PendaftarModel->cek_status(['id_user' => $this->session->userdata()['id_user']])[0];
        $this->print_penjamah_makanan($data);
        // $ret_data = $user;
    }

    function kop($pdf)
    {
        // if ($data['jen_satker'] == 1) {
        // echo json_encode($data);
        $pdf->Image(base_url('assets/img/kab_bangka.png'), 20, 5, 20, 27);
        $pdf->SetFont('Arial', '', 13);
        $pdf->SetFont('Arial', 'B', 15);
        $pdf->Cell(15, 6, '', 0, 0, 'C');
        $pdf->Cell(185, 7, 'PEMERINTAH KABUPATEN BANGKA', 0, 1, 'C');
        $pdf->Cell(15, 6, '', 0, 0, 'C');
        $pdf->SetFont('Arial', 'B', 20);
        $pdf->Cell(185, 7, 'DINAS KESEHATAN', 0, 1, 'C');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(15, 4, '', 0, 0, 'C');
        $pdf->Cell(185, 4, 'JL. AHMAD YANI. JALUR DUA (II) SUNGAILIAT', 0, 1, 'C');
        $pdf->Cell(15, 4, '', 0, 0, 'C');
        $pdf->Cell(185, 4, 'Kode Pos 33215 Telp (0717) 91952 Fax (0717) 91952', 0, 1, 'C');
        $pdf->Cell(15, 4, '', 0, 0, 'C');
        $pdf->Cell(185, 4, 'Email : dinkesbangka@gmail.com. Website : www.dinkes.bangka.go.id', 0, 1, 'C');
        $pdf->Line($pdf->GetX(), $pdf->GetY() + 3, $pdf->GetX() + 195, $pdf->GetY() + 3);
        $pdf->SetLineWidth(0.4);
        $pdf->Line($pdf->GetX(), $pdf->GetY() + 3.6, $pdf->GetX() + 195, $pdf->GetY() + 3.6);
        $pdf->SetLineWidth(0.2);
        // }
    }

    function print_penjamah_makanan($data)
    {
        require('assets/fpdf/mc_table.php');

        $pdf = new PDF_MC_Table('P', 'mm', array(215.9, 355.6));

        $pdf->SetTitle('Sertifikat Penjamah Makanan');
        $pdf->SetMargins(10, 5, 15, 10, 'C');
        $pdf->AddPage();
        // $data_satuan =  $this->GeneralModel->getSatuan(['id_satuan' => $data['id_satuan']])[0];

        $this->kop($pdf);
        if (!empty($data['unapprove'])) {
            $pdf->SetFont('Arial', 'B', 90);
            $pdf->SetTextColor(255, 192, 203);
            $pdf->RotatedText(35, 190, 'D I T O L A K', 30);
            $pdf->RotatedText(35, 300, 'D I T O L A K', 30);
        } else if (!empty($data['sign_kadin'])) {
        } else {
            $pdf->SetFont('Arial', 'B', 90);
            $pdf->SetTextColor(255, 192, 203);
            $pdf->RotatedText(55, 190, 'D R A F T', 30);
            $pdf->RotatedText(55, 300, 'D R A F T', 30);
        }
        // $pdf->SetFont('Arial', '', 9.5);
        $pdf->SetTextColor(0, 0, 0);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetFillColor(230, 230, 230);
        $pdf->Cell(190, 7, ' ', 0, 1, 'L', 0);
        $pdf->SetLineWidth(0.4);
        $pdf->Line(70, $pdf->GetY() + 4.5, 144, $pdf->GetY() + 4.5);
        $pdf->SetLineWidth(0.2);
        $pdf->Cell(195, 6, 'SERTIFIKAT KHUSUS PENJAMAH MAKANAN SIAP SAJI', 0, 1, 'C', 0);
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(195, 6, 'Nomor : ', 0, 1, 'C', 0);

        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(25, 6, ' ', 0, 1, 'L', 0);
        $pdf->MultiCell(198, 5, "Berdasarkan kepada {$data['menkes_nama']} tentang {$data['menkes_tentang']}, telah dilaksanakan Evaluasi/Kursus Higiene Sanitasi bagi penjamah makanan yang diselenggarakan oleh DINAS KESEHATAN KABUPATEN BANGKA pada tanggal " . tanggal_indonesia($data['start_time'], true) . " secara online dan sesuai dengan Keputusan Ketua Tim Evaluasi Nomor " . $data['sktim_nama'] . "  tentang Penetapan Peserta yang Lulus Evaluasi/Kursus Higiene Sanitasi makanan dan Depot Air Minum bagi penjamah makanan, dengan ini memberikan sertifikat kepada:", 0);
        $pdf->Cell(5, 6, '', 0, 1, 'L', 0);
        $pdf->Cell(45, 6, 'Nama', 0, 0, 'L', 0);
        $pdf->Cell(5, 6, ':', 0, 0, 'L', 0);
        $pdf->Cell(120, 6, $data['nama'], 0, 1, 'L', 0);
        $pdf->Cell(45, 6, 'NIK', 0, 0, 'L', 0);
        $pdf->Cell(5, 6, ':', 0, 0, 'L', 0);
        $pdf->Cell(120, 6, $data['username'], 0, 1, 'L', 0);
        $pdf->Cell(45, 6, 'Tempat/Tanggal Lahir', 0, 0, 'L', 0);
        $pdf->Cell(5, 6, ':', 0, 0, 'L', 0);
        $pdf->Cell(120, 6, $data['tempat_lahir'] . '/' . $data['tanggal_lahir'], 0, 1, 'L', 0);
        $pdf->Cell(45, 6, 'Alamat', 0, 0, 'L', 0);
        $pdf->Cell(5, 6, ':', 0, 0, 'L', 0);
        $pdf->MultiCell(148, 5, $data['alamat'], 0, 'L', 0);
        if (!empty($data['nama_badan'])) {
            $pdf->Cell(45, 6, 'Pekerjaan/Jabatan', 0, 0, 'L', 0);
            $pdf->Cell(5, 6, ':', 0, 0, 'L', 0);
            $pdf->Cell(120, 6, '', 0, 1, 'L', 0);
            $pdf->Cell(45, 6, 'Perusahaan/Unit Kerja', 0, 0, 'L', 0);
            $pdf->Cell(5, 6, ':', 0, 0, 'L', 0);
            $pdf->Cell(120, 6, $data['nama_badan'], 0, 1, 'L', 0);
        }
        $pdf->Cell(120, 6, '', 0, 1, 'L', 0);
        // $pdf->SetFont('Arial', '', 11);
        // $pdf->SetFont('Arial', '', 10);
        $cell = 'Pemegang Sertifikat ini telah memenuhi syarat dan dipandang sebagai Penjamah Makanan ';
        $pdf->Cell($pdf->GetStringWidth($cell), 3, $cell, 0, 'L');
        $pdf->SetFont('Arial', 'I', 11);
        $boldCell = "(Food Handler). ";
        $pdf->Cell($pdf->GetStringWidth($boldCell), 3, $boldCell, 0, 'L');
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(120, 12, '', 0, 1, 'L', 0);
        // $cell = 'These words do not need to be bold.';
        // $pdf->Cell($pdf->GetStringWidth($cell), 3, $cell, 0, 'L');

        // $pdf->MultiCell(18.5, 0.6,'Pemegang Sertifikat ini telah memenuhi syarat dan dipandang cakap sebagai Penjamah Makanan ', 0, 'j');

        // // $pdf->SetXY(18.6, 14.1);
        // $pdf->SetFont('Arial', 'I', 11);
        // $pdf->MultiCell(18.5, 0.6,'(Food ', 0, 'j');
        // $pdf->SetXY(1.5, 14.6);
        // $pdf->SetFont('Arial', 'I', 11);
        // $pdf->MultiCell(18.5, 0.6,'Handler). ', 0, 'j');
        $pdf->CheckPageBreak(65);

        if (!empty($data['sign_kadin'])) {
            $pdf->Cell(120, 6, '', 0, 0, 'C', 0);
            $pdf->Cell(30, 6, 'Ditetapkan di', 0, 0, 'L', 0);
            $pdf->Cell(4, 6, ':', 0, 0, 'C', 0);
            $pdf->Cell(40, 5, "Sungailiat", 0, 1, 'L', 0);

            $pdf->Cell(120, 6, '', 0, 0, 'C', 0);
            $pdf->Cell(30, 6, 'Pada Tanggal', 0, 0, 'L', 0);
            $pdf->Cell(4, 6, ':', 0, 0, 'C', 0);
            $pdf->Cell(40, 5, tanggal_indonesia($data['sign_tanggal']), 0, 1, 'L', 0);
            $pdf->Cell(120, 6, '', 0, 0, 'C', 0);
            $pdf->MultiCell(45, 5,  "Kepala Dinas Kesehatan\nKabupaten Bangka", 0, 'L', 0);

            $pdf->Cell(120, 36, '', 0, 1, 'C', 0);
            $pdf->Cell(120, 6, '', 0, 0, 'C', 0);
            $pdf->MultiCell(70, 5,  ucwords(strtolower($data['sign_nama'])), 0, 'L', 0);
            $pdf->Cell(120, 6, '', 0, 0, 'C', 0);
            $pdf->MultiCell(70, 5,  $data['pangkat_gol'], 0, 'L', 0);
            $pdf->Cell(120, 6, '', 0, 0, 'C', 0);
            $pdf->MultiCell(70, 5,  'NIP. ' . $data['sign_nip'], 0, 'L', 0);
            $pdf->Image(base_url('upload/qrcode/' . $data['sign_key'] . '.png'), 140, $pdf->getY() - 50, 35);
        } else {
            $pdf->Cell(120, 6, '', 0, 0, 'C', 0);
            $pdf->Cell(30, 6, 'Ditetapkan di', 0, 0, 'L', 0);
            $pdf->Cell(4, 6, ':', 0, 0, 'C', 0);
            $pdf->Cell(40, 6, '', 0, 1, 'L', 0);

            $pdf->Cell(120, 6, '', 0, 0, 'C', 0);
            $pdf->Cell(30, 6, 'Pada Tanggal', 0, 0, 'L', 0);
            $pdf->Cell(4, 6, ':', 0, 0, 'C', 0);
            $pdf->Cell(40, 6, '', 0, 1, 'L', 0);
            $pdf->Cell(120, 6, '', 0, 1, 'C', 0);
            $pdf->Cell(120, 6, '', 0, 0, 'C', 0);
            $pdf->MultiCell(45, 5,  'ttd', 0, 'L', 0);
            $pdf->Cell(120, 6, '', 0, 0, 'C', 0);
            $pdf->MultiCell(45, 5,  '', 0, 'L', 0);
            $pdf->Cell(120, 6, '', 0, 0, 'C', 0);
            $pdf->MultiCell(45, 5,  '', 0, 'L', 0);


            // $pdf->RotatedText(35, 300, 'Ditolak', 45);
        }
        $pdf->Cell(130, 6, '', 0, 0, 'C', 0);
        $filename = 'SPT ';

        $pdf->Output('', $filename, false);
    }
}
