<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Permintaan_model extends CI_Model {

    public function __construct() {
        parent::__construct();

        $this->load->database();

    }

    private function filters($config, $set_limit = true) {
        $limit = '';
        $offset = 0;
        if(isset($config['search']) && !empty($config['search'])) {
            $this->db->like('p.nama_kapal', $this->db->escape_like_str($config['search']));
        }
        if(isset($config['perusahaan_id']) && !empty($config['perusahaan_id'])) {
            $this->db->where('p.perusahaan_id', $this->db->escape_like_str($config['perusahaan_id']));
        }
        if(isset($config['perusahaan_id']) && !empty($config['perusahaan_id'])) {
            $this->db->where('p.perusahaan_id', $this->db->escape_like_str($config['perusahaan_id']));
        }
        if(isset($config['created_by']) && !empty($config['created_by'])) {
            $this->db->where('p.created_by', $this->db->escape_like_str($config['created_by']));
        }

        if((isset($config['status_proses']) && $config['status_proses'] === '0') &&
           (isset($config['status_selesai']) && $config['status_selesai'] === '1')
          ) {
            $this->db->where('p.status', $this->db->escape_like_str($config['status_proses']));
            $this->db->or_where('p.status', $this->db->escape_like_str($config['status_selesai']));
        }

        if((isset($config['status_proses']) && $config['status_proses'] === '0') &&
           (isset($config['status_selesai']) && $config['status_selesai'] !== '1')
          ) {
            $this->db->where('p.status', $this->db->escape_like_str($config['status_proses']));
        }

        if((isset($config['status_proses']) && $config['status_proses'] !== '0') &&
           (isset($config['status_selesai']) && $config['status_selesai'] === '1')
          ) {
            $this->db->where('p.status', $this->db->escape_like_str($config['status_selesai']));
        }

        if(isset($config['per_page']) && !empty($config['per_page'])) {
            $limit = $config['per_page'];
        }
        if(isset($config['page']) && !empty($config['page'])) {
            $offset = $config['page'];
        }
        if($set_limit) {
            //if($offset > 0) {
                $this->db->limit($limit, $offset);
            //}
        }
    }

    public function select($config) {
        $this->filters($config);
        $query = $this->db->select('p.*')
                        ->select('(SELECT name FROM users WHERE id = p.created_by) AS created_by_name')
                        ->get('permintaan p');
        // echo $this->db->last_query();
        if($query != "" && $query->num_rows() > 0) {
            return $query->result();
        }
        return false;
    }

    public function select_total($config) {
        $this->filters($config, false);
        $query = $this->db->select('COUNT(p.id) AS total')
                        ->get('permintaan p');
        // echo $this->db->last_query();
        if($query != "" && $query->num_rows() > 0) {
            return $query->row()->total;
        }
        return false;
    }

    public function lists() {
        $query = $this->db->order_by('name','ASC')
                          ->get('permintaan');
        // echo $this->db->last_query();
        if(!empty($query) && $query->num_rows() > 0) {
            return $query->result();
        }
        return false;
    }

    public function data($id) {
        $query = $this->db->select('p.*')
                          ->select('pr.nama_perusahaan')
                          ->select('jt.nama AS nama_jenis_transaksi')
                          ->select('jj.nama AS nama_jenis_jasa')
                          ->select('(SELECT name FROM users WHERE id = p.created_by) AS created_by_name')
                          ->where('p.id', $this->db->escape_str($id))
                          ->join('perusahaan pr', 'pr.id = p.perusahaan_id', 'LEFT')
                          ->join('jenis_transaksi jt', 'jt.id = p.jenis_transaksi_id', 'LEFT')
                          ->join('jenis_jasa jj', 'jj.id = p.jenis_jasa_id', 'LEFT')
                          ->get('permintaan p');
        // echo $this->db->last_query();
        if(!empty($query) && $query->num_rows() > 0) {
            return $query->row();
        }
        return false;
    }

    public function insert($jData, $return_id) {
        if (!empty($jData)) {
            $data = json_decode($jData);

            $saldo_perusahaan = $this->get_saldo_perusahaan($data->perusahaan_id);

            $this->db->trans_begin();
            try {

                $this->db->set('no_aju', $this->generateNoAju())
                ->set('perusahaan_id', $data->perusahaan_id)
                ->set('nama_kapal', strtoupper($data->nama_kapal))
                ->set('grt', $data->grt)
                ->set('perkiraan_kegiatan_from', UnFormatDateTime($data->perkiraan_kegiatan_from,7))
                ->set('perkiraan_kegiatan_to', UnFormatDateTime($data->perkiraan_kegiatan_to,7))
                ->set('pemilik_kapal', strtoupper($data->pemilik_kapal))
                ->set('posisi_kapal', strtoupper($data->posisi_kapal))
                ->set('jenis_transaksi_id', $data->jenis_transaksi_id)
                ->set('jenis_jasa_id', $data->jenis_jasa_id)
                ->set('jumlah', $data->jumlah)
                ->set('created', UnFormatDateTime(CurrentDateTime(), 1))
                ->set('created_by', $this->auth->get_user_id())
                ->set('modified', null)
                ->set('modified_by', null)
                ->set('last_ip', $this->input->ip_address())
                ->insert('permintaan');
                $insert_id = $this->db->insert_id();
                //echo $this->db->last_query();
                if ($this->db->trans_status() === FALSE) {
                    throw new Exception('Failed insert into permintaan: ' . $this->db->_error_message());
                }

                $sisa_saldo = ($saldo_perusahaan - $data->jumlah);
                $this->db->set('saldo', $sisa_saldo)
                        ->where('id', $this->db->escape_str($data->perusahaan_id))
                        ->update('perusahaan');
                // echo $this->db->last_query();
                if ($this->db->trans_status() === FALSE) {
                    throw new Exception('Failed update saldo into permintaan: ' . $this->db->_error_message());
                }

                $this->db->trans_commit();

                if($return_id === true){
                    return $insert_id;
                } else {
                    return true;
                }

            } catch (Exception $e) {
                $this->db->trans_rollback();
                log_message("error", $e->getMessage());
                return false;
            }
        }
        return false;
    }

    private function generateNoAju() {
        $query = $this->db->select('no_aju')
                          ->order_by('id','DESC')
                          ->limit(1)
                          ->get('permintaan');
        // echo $this->db->last_query();
        if(!empty($query) && $query->num_rows() > 0) {
            $last_no_aju = (int)$query->row()->no_aju;
            return str_pad($last_no_aju + 1, 6, "0", STR_PAD_LEFT);
        }
        return false;
    }

    public function permintaan_selesai_terakhir() {
        $query = $this->db->select('p.*')
                          ->where('p.perusahaan_id', $this->db->escape_str($this->auth->get_perusahaan()))
                          ->where('p.status', '1')
                          ->where('p.dilihat', 0)
                          ->get('permintaan p');
        // echo $this->db->last_query();
        if(!empty($query) && $query->num_rows() > 0) {
            return $query->result();
        }
        return false;
    }

    public function imports($jData) {
        if (!empty($jData)) {
            $datas = json_decode($jData);
            $this->db->trans_begin();
            try {

                foreach($datas as $data) {

                    $name_exist = $this->name_exist($data->name);

                    if(!$name_exist) {
                        $this->db->set('name', $data->name)
                                ->set('group_emails', $data->group_emails)
                                ->set('manager_emails', $data->manager_emails)
                                ->set('created', UnFormatDateTime(CurrentDateTime(), 1))
                                ->set('created_by', $this->auth->get_user_id())
                                ->set('last_ip', $this->input->ip_address())
                                ->insert('permintaan');
                        // echo $this->db->last_query();
                        if ($this->db->trans_status() === FALSE) {
                            throw new Exception('Failed insert into permintaan: ' . $this->db->_error_message());
                        }
                    }
                }

                $this->db->trans_commit();
                return true;
            } catch (Exception $e) {
                $this->db->trans_rollback();
                log_message("error", $e->getMessage());
                return false;
            }
        }
        return false;
    }

    private function name_exist($name) {
        $query = $this->db->where('name', $this->db->escape_str($name))
                          ->get('permintaan');
        // echo $this->db->last_query().'<br />';
        if(!empty($query) && $query->num_rows() > 0) {
            return true;
        }
        return false;
    }

    private function get_saldo_perusahaan($perusahaan_id) {
         $query = $this->db->select('p.saldo')
                           ->where('p.id', $this->db->escape_str($perusahaan_id))
                           ->get('perusahaan p');
        // echo $this->db->last_query();
        if(!empty($query) && $query->num_rows() > 0) {
            return $query->row()->saldo;
        }
        return false;
    }

    public function update($jData) {
        if (!empty($jData)) {
            $data = json_decode($jData);

            // $saldo_perusahaan = $this->get_saldo_perusahaan($data->perusahaan_id);

            $this->db->trans_begin();
            try {

                $this->db->set('status', $data->status)
                        ->set('modified', UnFormatDateTime(CurrentDateTime(), 1))
                        ->set('modified_by', $this->auth->get_user_id())
                        ->set('last_ip', $this->input->ip_address())
                        ->where('id', $this->db->escape_str($data->id))
                        ->update('permintaan');
                // echo $this->db->last_query();
                if ($this->db->trans_status() === FALSE) {
                    throw new Exception('Failed update into permintaan: ' . $this->db->_error_message());
                }

                /* pengurangan saldo langsung di lakukan pada saat pengajuan tersimpan */
                /*if($data->status == '1') {
                    $sisa_saldo = ($saldo_perusahaan - $data->jumlah);
                    $this->db->set('saldo', $sisa_saldo)
                            ->where('id', $this->db->escape_str($data->perusahaan_id))
                            ->update('perusahaan');
                    // echo $this->db->last_query();
                    if ($this->db->trans_status() === FALSE) {
                        throw new Exception('Failed update into permintaan: ' . $this->db->_error_message());
                    }
                }*/

                $this->db->trans_commit();
                return true;
            } catch (Exception $e) {
                $this->db->trans_rollback();
                log_message("error", $e->getMessage());
                return false;
            }
        }
        return false;
    }

    public function delete($id, $perusahaan_id, $jumlah_booking) {
        if(!is_numeric($id)) {
            return false;
        }

        $saldo_perusahaan = $this->get_saldo_perusahaan($perusahaan_id);

        $this->db->trans_begin();
        try {
            $this->db->where('id', $this->db->escape_str($id))
                    ->delete('permintaan');
            // echo $this->db->last_query();
            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Failed delete permintaan: ' . $this->db->_error_message());
            }

            $balikin_saldo = ($saldo_perusahaan + $jumlah_booking);
            $this->db->set('saldo', $balikin_saldo)
                    ->where('id', $this->db->escape_str($perusahaan_id))
                    ->update('perusahaan');
            // echo $this->db->last_query();
            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Failed update saldo into permintaan: ' . $this->db->_error_message());
            }

            $this->db->trans_commit();
            return true;
        } catch (Exception $e) {
            $this->db->trans_rollback();
            log_message("error", $e->getMessage());
            return false;
        }
    }

}