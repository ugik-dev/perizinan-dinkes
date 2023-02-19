<?php
defined('BASEPATH') or exit('No direct script access allowed');

class UserModel extends CI_Model
{

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
		if (isset($filter['ex_role'])) $this->db->where_not('u.id_role', $filter['ex_role']);
		if (isset($filter['id_user'])) $this->db->where('u.id_user', $filter['id_user']);
		$res = $this->db->get();
		return DataStructure::keyValue($res->result_array(), 'id_user');
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

	public function changePassword($data)
	{
		$this->db->set('password', md5($data['password']));
		$this->db->where('id_user', $data['id_user']);
		$this->db->update('user');
		return 'succes';
	}
	public function editUser($tmpdata)
	{
		$data = array(
			'username' => $tmpdata['username'],
			'nama' => $tmpdata['nama'],
		);

		$this->db->set($data);
		$this->db->where('id_user', $tmpdata['id_user']);
		$this->db->update('user');

		return $tmpdata;
	}


	public function getUserByUsername($username = NULL)
	{
		$row = $this->getAllUser(['username' => $username]);
		if (empty($row)) {
			throw new UserException("User yang kamu cari tidak ditemukan", USER_NOT_FOUND_CODE);
		}
		return array_values($row)[0];
	}

	public function login($loginData)
	{

		$user = $this->getUserByUsername($loginData['username']);
		if (md5($loginData['password']) !== $user['password'])
			throw new UserException("Password yang kamu masukkan salah.", WRONG_PASSWORD_CODE);
		return $user;
	}

	public function registerUser($data)
	{
		$this->cekUserByUsername($data['username']);
		$this->cekUserByEmail($data);
		$data['password_hash'] = $data['password'];
		$data['password'] = md5($data['password']);
		$permitted_activtor = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$data['activator'] =  substr(str_shuffle($permitted_activtor), 0, 20);
		// echo $act;
		$this->db->insert('user_temp', DataStructure::slice(
			$data,
			[
				'username', 'nama', 'password', 'password_hash', 'activator', 'email', 'alamat', 'phone', 'tempat_lahir', 'tanggal_lahir'
			],
			TRUE
		));
		ExceptionHandler::handleDBError($this->db->error(), "Tambah User", "User");

		$data['id']  = $this->db->insert_id();

		return $data;
	}

	public function cekUserByUsername($username = NULL)
	{
		$row = $this->getAllUser(['username' => $username, 'is_login' => TRUE]);
		if (!empty($row)) {
			throw new UserException("User yang kamu daftarkan sudah ada", USER_NOT_FOUND_CODE);
		}
	}

	public function cekUserByEmail($data)
	{
		$this->db->select("email");
		$this->db->from('user as u');
		$this->db->where('u.email', $data['email']);
		$res = $this->db->get();
		$row = $res->result_array();
		if (!empty($row)) {
			throw new UserException("Email yang kamu daftarkan sudah ada", USER_NOT_FOUND_CODE);
		}
	}

	public function activatorUser($data)
	{
		$this->db->select("*");
		$this->db->from('user_temp as u');
		$this->db->where("u.id ", $data['id']);
		$this->db->where("u.activator ", $data['activator']);
		$res = $this->db->get();
		$res = $res->result_array();
		if (empty($res)) {
			throw new UserException('Activation failed or has active please check your email or try to login', USER_NOT_FOUND_CODE);
		} else {
			$res = $res[0];
			// $this->cekUserByEmailBuyer($res[0]);
			$this->cekUserByEmail($res);
			$this->cekUserByUsername($res['username']);

			$res['id_role'] = '4';
			// $res['email'] = '4';
			$res['password'] = $res['password_hash'];
			$res['hash_pwd'] = $res['password_hash'];

			$dataPendaftar = [
				'nomor_ktp' => $res['username'],
				'tempat_lahir' => $res['tempat_lahir'],
				'tanggal_lahir' => $res['tanggal_lahir'],
				'alamat' => $res['alamat'],
			];
			$this->db->insert('data_pendaftar', $dataPendaftar);
			ExceptionHandler::handleDBError($this->db->error(), "", "data_pendaftar");
			$id_pendaftar = $this->db->insert_id();
			$res['id_data'] = $id_pendaftar;

			$res['id_user'] = $this->addUser($res);
			//	$this->addPerusahaan($res[0]);
			// $this->db->where('id', $res[0]['id']);
			// $this->db->delete('user_temp');
			// $data['password'] =  md5($data['password']);

			// return $res[0];
		};
	}

	public function addUser($data)
	{
		$data['password'] =  md5($data['password']);
		$dataInsert = DataStructure::slice($data, ['password', 'username', 'nama', 'id_data', 'id_role', 'email', 'hash_pwd', 'alamat', 'phone']);
		$this->db->insert('user', $dataInsert);
		ExceptionHandler::handleDBError($this->db->error(), "Insert Kelolahuser", "user");
		return $this->db->insert_id();
	}
}
