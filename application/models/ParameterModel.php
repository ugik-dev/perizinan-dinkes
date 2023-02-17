<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ParameterModel extends CI_Model
{


	public function calculateScore($data, $soal, $answer, $jwb_terisi)
	{

		$id_perizinan = $data['id_perizinan'];
		// $query = 'SELECT sum(poin) as score from bank_soal  left join bank_opsi on bank_soal.id_bank_soal = bank_opsi.id_bank_soal 
		// 	where 
		// 	 bank_soal.id_bank_soal in (' . $soal . ') and bank_opsi.token_opsi in (' . $answer . ') ';
		// $res = $this->db->query($query);
		// $res = $res->result_array();

		$query2 = 'SELECT count(*) as benar from bank_soal  left join bank_opsi on bank_soal.id_bank_soal = bank_opsi.id_bank_soal 
			where bank_opsi.status = "Y" and bank_soal.id_perizinan = ' . $id_perizinan . '
			and bank_soal.id_bank_soal in (' . $soal . ') and bank_opsi.token_opsi in (' . $answer . ') ';
		$res2 = $this->db->query($query2);
		$res2 = $res2->result_array();



		$benar = $res2[0]['benar'];
		// $score = $res[0]['score'];
		$dpr = $this->getAllMapel(['id_perizinan' => $data['id_perizinan']])[$data['id_perizinan']];
		$score = (int)$benar / (int)$dpr['jml_soal'] * 100;
		$jwb_salah = $jwb_terisi - $benar;

		$this->db->set('score', $score);
		$this->db->set('benar', $benar);
		$this->db->set('salah', $jwb_salah);
		$this->db->set('kosong', ((int)$dpr['jml_soal'] - (int)$jwb_terisi));
		$this->db->set('menkes', $dpr['menkes_active']);
		$this->db->set('sktim', $dpr['sktim_active']);
		$this->db->set('exam_lock', 'Y');
		if ($score >= $dpr['passinggrade']) {

			$this->db->set('hasil', 'Y');
			$this->db->set('status_approval', '1');
		} else
			$this->db->set('hasil', 'N');
		$this->db->where('token', $data['token']);
		$this->db->update('session_exam_user');
	}

	public function getPassingGrade()
	{
		$this->db->from('jenis_jurusan');
		$this->db->join('passing_grade', 'id_jenis_jurusan = id_jurusan', 'left');
		$res = $this->db->get();
		return DataStructure::keyValue($res->result_array(), 'id_jenis_jurusan');
	}

	public function getYourHistory($filter = [])
	{
		$this->db->select("u.* ,r.*");
		$this->db->from('session_exam_user as u');
		$this->db->join('ref_perizinan as r', 'r.id_perizinan = u.id_perizinan');
		// $this->db->join('session_exam as r', 'r.id_session_exam = u.id_session_exam');
		// $this->db->join('kabupaten as k', 'k.id_kabupaten = u.id_kabupaten','left');

		if (!empty($filter['id_session_exam_user'])) $this->db->where('u.id_session_exam_user', $filter['id_session_exam_user']);
		if (!empty($filter['token'])) $this->db->where('u.token', $filter['token']);
		if (!empty($filter['id_user'])) $this->db->where('u.id_user', $filter['id_user']);
		$res = $this->db->get();
		if (!empty($filter['token'])) {
			return DataStructure::keyValue($res->result_array(), 'token');
		}
		return DataStructure::keyValue($res->result_array(), 'id_session_exam_user');
	}

	public function getSertifikat($filter)
	{
		$this->db->select('sign.nama as sign_nama, sign.jabatan sign_jabatan,  sign.pangkat_gol pangkat_gol, sign_key, sign.nip sign_nip, sign.tanggal sign_tanggal');
		$this->db->select("u.* , r.*, s.*, m.*,us.nama, us.username, ud.alamat,ud.tanggal_lahir, ud.tempat_lahir");
		$this->db->from('session_exam_user as u');
		$this->db->join('ref_perizinan as r', 'r.id_perizinan = u.id_perizinan');
		$this->db->join('ref_menkes as m', 'm.menkes_id = u.menkes', 'LEFT');
		$this->db->join('ref_sktim as s', 's.sktim_id = u.sktim', 'LEFT');
		$this->db->join('user as us', 'us.id_user = u.id_user', 'LEFT');
		$this->db->join('data_pendaftar as ud', 'us.id_data = ud.id_data', 'LEFT');
		$this->db->join('sign', 'sign.id_sign = u.sign_kadin', 'LEFT');

		if (!empty($filter['token'])) $this->db->where('u.token', $filter['token']);
		if (!empty($filter['sign_key'])) $this->db->where('sign.sign_key', $filter['sign_key']);
		// $this->db->where('u.hasil', 'Y');

		$res = $this->db->get();
		// if (!empty($filter['token'])) {
		if (!empty($filter['sign_key'])) return DataStructure::keyValue($res->result_array(), 'sign_key');
		else return DataStructure::keyValue($res->result_array(), 'token');
		// }
		// return DataStructure::keyValue($res->result_array(), 'id_session_exam_user');
	}

	public function getExam($filter)
	{
		$this->db->select("u.* ,(durasi) as limit_time");
		$this->db->from('session_exam_user as u');
		$this->db->join('ref_perizinan as r', 'r.id_perizinan = u.id_perizinan');
		// $this->db->join('kabupaten as k', 'k.id_kabupaten = u.id_kabupaten','left');

		if (!empty($filter['id_session_exam_user'])) $this->db->where('u.id_session_exam_user', $filter['id_session_exam_user']);
		if (!empty($filter['token'])) $this->db->where('u.token', $filter['token']);
		if (!empty($filter['id_user'])) $this->db->where('u.id_user', $filter['id_user']);
		// if (!empty($filter['ip_address'])) $this->db->where('u.ip_address', $filter['ip_address']);
		$res = $this->db->get();
		if (!empty($filter['token'])) {
			return DataStructure::keyValue($res->result_array(), 'token');
		}
		return DataStructure::keyValue($res->result_array(), 'id_session_exam_user');
	}

	public function startExam($filter)
	{

		$this->db->set('start_time', $filter['start_time']);
		$this->db->where('id_session_exam_user', $filter['id_session_exam_user']);
		$this->db->where('token', $filter['token']);
		$this->db->where('id_user', $filter['id_user']);
		$this->db->update('session_exam_user');
		// if (!empty($filter['ip_address'])) $this->db->where('u.ip_address', $filter['ip_address']);
		// $res = $this->db->get();
		// if (!empty($filter['token'])) {
		// 	return DataStructure::keyValue($res->result_array(), 'token');
		// }
		// return DataStructure::keyValue($res->result_array(), 'id_session_exam_user');
	}

	public function getAllUser($filter = [])
	{
		if (isset($filter['isSimple'])) {
			$this->db->select('u.id_user, u.username, u.photo, u.nama, u.id_role');
		} else {
			$this->db->select("u.*, r.*");
		}
		$this->db->from('user as u');
		$this->db->join('role as r', 'r.id_role = u.id_role');
		// $this->db->join('kabupaten as k', 'k.id_kabupaten = u.id_kabupaten','left');

		if (isset($filter['username'])) $this->db->where('u.username', $filter['username']);
		if (isset($filter['id_user'])) $this->db->where('u.id_user', $filter['id_user']);
		$res = $this->db->get();
		return DataStructure::keyValue($res->result_array(), 'id_user');
	}


	public function getAllBankSoal($filter = [])
	{
		if (!empty($filter['full']))
			$this->db->select("*");
		else {
			$this->db->select("*");
		}
		$this->db->from('bank_soal as u');
		$this->db->join('ref_perizinan as r', 'r.id_perizinan = u.id_perizinan');
		$this->db->join('bank_opsi as k', 'k.id_bank_soal = u.id_bank_soal', 'left');
		$this->db->where('k.status', 'Y');
		if (!empty($filter['id_bank_soal'])) $this->db->where('u.id_bank_soal', $filter['id_bank_soal']);
		if (!empty($filter['id_perizinan'])) $this->db->where('u.id_perizinan', $filter['id_perizinan']);
		if (!empty($filter['limit'])) $this->db->limit($filter['limit']);
		if (!empty($filter['order_random'])) $this->db->order_by('rand()');
		$res = $this->db->get();
		if (!empty($filter['result_array'])) {
			return $res->result_array();
		}
		return DataStructure::keyValue($res->result_array(), 'id_bank_soal');
	}

	public function getShuffleSoal($id, $jawabn = false)
	{
		// if (!empty($filter['full']))
		$this->db->select("u.soal, u.image");
		$this->db->from('bank_soal as u');
		if ($jawabn) {
			$this->db->select("token_opsi,name_opsi,pembahasan,pembahasan_img");
			$this->db->join('bank_opsi as k', 'k.id_bank_soal = u.id_bank_soal', 'left');
			$this->db->where('k.status', 'Y');
		}
		// else {
		// 	$this->db->select("*");
		// }
		// $this->db->join('ref_perizinan as r', 'r.id_perizinan = u.id_perizinan');
		$this->db->where('u.id_bank_soal', $id);

		$res = $this->db->get();
		// if (!empty($filter['result_array'])) {
		$data['soal'] = $res->result_array()[0];

		$this->db->select("name_opsi,token_opsi");
		$this->db->from('bank_opsi as s');
		$this->db->where('s.id_bank_soal', $id);

		$res = $this->db->get();
		// if (!empty($filter['result_array'])) {
		$data['opsi'] = $res->result_array();
		if ($jawabn) {
		} else {
			shuffle($data['opsi']);
		}
		return $data;
		// }
		// return DataStructure::keyValue($res->result_array(), 'id_bank_soal');
	}


	public function getAllSession($filter = [])
	{
		$this->db->select("u.*, r.*, us.nama, us.username, us.alamat");
		if (!empty($filter['full'])) {
		} else {
		}
		$this->db->from('session_exam_user as u');
		$this->db->join('user as us', 'us.id_user = u.id_user');
		$this->db->join('ref_perizinan as r', 'r.id_perizinan = u.id_perizinan');
		if (!empty($filter['id_perizinan'])) $this->db->where('u.id_perizinan', $filter['id_perizinan']);
		// if (!empty($filter['id_session_exam'])) $this->db->where('u.id_session_exam', $filter['id_session_exam']);
		// if (isset($filter['id_user'])) $this->db->where('u.id_user', $filter['id_user']);
		$res = $this->db->get();
		return DataStructure::keyValue($res->result_array(), 'id_session_exam_user');
	}

	public function getAvaliableSession($filter = [])
	{
		$cur_date = date('Y-m-d H:i:s');
		// echo $cur_date;
		// die();
		$this->db->select("u.open_end >= '" . $cur_date . "' as x ,j.nama_jenis_jurusan,exam_lock ,u.*,r.*, k.id_session_exam_user,k.token,k.id_user,k.score, k.score_arr");
		$this->db->from('session_exam as u');
		$this->db->join('ref_perizinan as r', 'r.id_perizinan = u.id_perizinan');
		$this->db->join('session_exam_user as k', " (k.id_session_exam = u.id_session_exam and k.id_user = '" . $this->session->userdata('id_user') . "') OR id_session_exam_user is NULL  AND u.open_end >= '" . $cur_date . "' ", 'left');
		// $this->db->join('user as us', 'us.id_user = k.id_user', 'right');
		$this->db->where('( k.id_session_exam is null or k.id_user = "' . $this->session->userdata('id_user') . '" )');
		// $this->db->where('us.id_user = "null" or us.id_user = "' . $this->session->userdata('id_user') . '"');
		// if (!empty($filter['start_exam']))
		// $this->db->where('u.open_start <= "' . $cur_date . '"');
		$this->db->where('open_end >= "' . $cur_date . '"');
		$this->db->or_where('k.score is not null');

		if (!empty($filter['id_perizinan'])) $this->db->where('u.id_perizinan', $filter['id_perizinan']);
		if (!empty($filter['id_session_exam'])) $this->db->where('u.id_session_exam', $filter['id_session_exam']);
		// if (isset($filter['id_user'])) $this->db->where('u.id_user', $filter['id_user']);
		$res = $this->db->get();
		// echo
		// $this->db->last_query();
		// die();
		return DataStructure::keyValue($res->result_array(), 'id_session_exam');
	}


	public function getOpsi($filter = [])
	{
		if (!empty($filter['full']))
			$this->db->select("*");
		else {
		}
		$this->db->from('bank_opsi as u');
		// $this->db->join('ref_perizinan as r', 'r.id_perizinan = u.id_perizinan');
		// $this->db->join('kabupaten as k', 'k.id_kabupaten = u.id_kabupaten','left');

		// if (isset($filter['username'])) $this->db->where('u.username', $filter['username']);
		if (!empty($filter['id_bank_soal'])) $this->db->where('u.id_bank_soal', $filter['id_bank_soal']);
		$res = $this->db->get();
		return DataStructure::keyValue($res->result_array(), 'id_opsi');
	}


	public function getAllMapel($filter = [])
	{
		$this->db->select("u.*");
		$this->db->from('ref_perizinan as u');
		// $this->db->join('role as r', 'r.id_role = u.id_role');
		// $this->db->join('kabupaten as k', 'k.id_kabupaten = u.id_kabupaten','left');
		$this->db->where('u.status', 1);
		if (!empty($filter['id_perizinan'])) $this->db->where('u.id_perizinan', $filter['id_perizinan']);
		$res = $this->db->get();
		return DataStructure::keyValue($res->result_array(), 'id_perizinan');
	}

	public function getMyExam($filter = [])
	{
		$this->db->select("u.*, k.hasil, k.score, k.token");
		$this->db->from('ref_perizinan as u');
		// $this->db->join('role as r', 'r.id_role = u.id_role');
		$this->db->join('session_exam_user as k', 'k.id_perizinan = u.id_perizinan AND (hasil = "Y" OR hasil = "W")', 'left');
		$this->db->where('u.status', 1);
		if (!empty($filter['id_perizinan'])) $this->db->where('u.id_perizinan', $filter['id_perizinan']);
		$res = $this->db->get();
		return DataStructure::keyValue($res->result_array(), 'id_perizinan');
	}


	public function getAllJurusan($filter = [])
	{
		$this->db->select("*");
		$this->db->from('jenis_jurusan as u');

		if (!empty($filter['id_jenis_jurusan'])) $this->db->where('u.id_jenis_jurusan', $filter['id_jenis_jurusan']);
		$res = $this->db->get();
		return DataStructure::keyValue($res->result_array(), 'id_jenis_jurusan');
	}


	public function addMapel($data)
	{
		$dataInsert = DataStructure::slice($data, ['nama_perizinan', 'status']);
		$this->db->insert('ref_perizinan', $dataInsert);
		ExceptionHandler::handleDBError($this->db->error(), "Insert ref_perizinan", "ref_perizinan");
		return $this->db->insert_id();
	}

	public function editMapel($data)
	{
		$dataInsert = DataStructure::slice($data, ['nama_perizinan', 'status']);
		$this->db->set($dataInsert);
		$this->db->where('id_perizinan', $data['id_perizinan']);
		$this->db->update('ref_perizinan');
		ExceptionHandler::handleDBError($this->db->error(), "Insert ref_perizinan", "ref_perizinan");

		return $data['id_perizinan'];
	}


	public function deleteMapel($data)
	{
		$this->db->where('id_perizinan', $data['id_perizinan']);
		$this->db->delete('ref_perizinan');
		ExceptionHandler::handleDBError($this->db->error(), "Insert ref_perizinan", "ref_perizinan");

		return $data['id_perizinan'];
	}

	public function addKelas($data)
	{
		$dataInsert = DataStructure::slice($data, ['nama_jenis_kelas', 'id_jenis_jurusan', 'sub_kelas']);
		$this->db->insert('jenis_kelas', $dataInsert);
		ExceptionHandler::handleDBError($this->db->error(), "Insert ref_perizinan", "jenis_kelas");
		return $this->db->insert_id();
	}

	public function editKelas($data)
	{
		$dataInsert = DataStructure::slice($data, ['nama_jenis_kelas', 'id_jenis_jurusan', 'sub_kelas']);
		$this->db->set($dataInsert);
		$this->db->where('id_jenis_kelas', $data['id_jenis_kelas']);
		$this->db->update('jenis_kelas');
		ExceptionHandler::handleDBError($this->db->error(), "Insert ref_perizinan", "jenis_kelas");

		return $data['id_jenis_kelas'];
	}


	public function deleteKelas($data)
	{
		$this->db->where('id_jenis_kelas', $data['id_jenis_kelas']);
		$this->db->delete('jenis_kelas');
		ExceptionHandler::handleDBError($this->db->error(), "Insert ref_perizinan", "jenis_kelas");

		return $data['id_jenis_kelas'];
	}

	public function getUser($idUser = NULL)
	{
		$row = $this->getAllUser(['id_user' => $idUser]);
		if (empty($row)) {
			throw new UserException("User yang kamu cari tidak ditemukan", USER_NOT_FOUND_CODE);
		}
		return $row[$idUser];
	}

	public function editPhoto($idUser, $newPhoto)
	{
		$this->db->set('photo', $newPhoto);
		$this->db->where('id_user', $idUser);
		$this->db->update('user');
		return $newPhoto;
	}

	public function getUserByUsername($username = NULL)
	{
		$row = $this->getAllUser(['username' => $username]);
		if (empty($row)) {
			throw new UserException("User yang kamu cari tidak ditemukan", USER_NOT_FOUND_CODE);
		}
		return array_values($row)[0];
	}

	public function addTA($data)
	{
		$dataInsert = DataStructure::slice($data, ['deskripsi', 'semester', 'start', 'end']);
		$this->db->insert('tahun_ajaran', $dataInsert);
		ExceptionHandler::handleDBError($this->db->error(), "Add Tahun Ajaran", "tahun_ajaran");
		return $this->db->insert_id();
	}

	public function SubmitExam($data)
	{
		if ($data['autosave'] == 'false') {
			$this->db->set('exam_lock', 'Y');
		}
		if (!empty($this->session->userdata()['id_user']))
			$this->db->where('id_user', $this->session->userdata('id_user'));
		else
			$this->db->where('ip_address', $this->input->ip_address());

		$this->db->set('answer', $data['answer']);
		$this->db->where('token', $data['token']);
		$this->db->update('session_exam_user');
		ExceptionHandler::handleDBError($this->db->error(), "Save Session", "save_session");
	}

	public function createExam($data)
	{
		if (!empty($this->session->userdata()['id_user']))
			$data['id_user'] = $this->session->userdata()['id_user'];
		else
			$data['ip_address'] =  $this->input->ip_address();
		$data['token'] = bin2hex(openssl_random_pseudo_bytes(32));
		$data['start_time'] = date('Y-m-d H:i:s');
		$dataInsert = DataStructure::slice($data, ['id_perizinan', 'id_user', 'generate_soal', 'token', 'ip_address', 'nama_badan', 'start_time', 'alamat_badan']);
		$this->db->insert('session_exam_user', $dataInsert);
		ExceptionHandler::handleDBError($this->db->error(), "Create Session", "create_session");
		return $this->db->insert_id();
	}

	public function editTA($data)
	{
		$dataInsert = DataStructure::slice($data, ['deskripsi', 'semester', 'start', 'end']);
		$this->db->set($dataInsert);
		$this->db->where('id_tahun_ajaran', $data['id_tahun_ajaran']);
		$this->db->update('tahun_ajaran');
		ExceptionHandler::handleDBError($this->db->error(), "Update Tahun Ajaran", "jenis_kelas");

		return $data['id_tahun_ajaran'];
	}

	public function set_current_ta($data)
	{
		// $dataInsert = DataStructure::slice($data, ['deskripsi', 'semester', 'start' , 'end']);
		$this->db->set("current", "1");
		$this->db->where('current', "2");
		$this->db->update('tahun_ajaran');
		ExceptionHandler::handleDBError($this->db->error(), "Update Tahun Ajaran", "jenis_kelas");

		$this->db->set("current", "2");
		$this->db->where('id_tahun_ajaran', $data['id_tahun_ajaran']);
		$this->db->update('tahun_ajaran');
		ExceptionHandler::handleDBError($this->db->error(), "Update Tahun Ajaran", "jenis_kelas");


		return $data['id_tahun_ajaran'];
	}


	public function deleteTA($data)
	{
		$this->db->where('id_tahun_ajaran', $data['id_tahun_ajaran']);
		$this->db->delete('tahun_ajaran');
		ExceptionHandler::handleDBError($this->db->error(), "Delete Tahun Ajaran", "tahun_ajaran");

		return $data['id_tahun_ajaran'];
	}
}
