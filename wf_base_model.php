<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class WF_Base_Model extends CI_Model {

	public $_db, $table_name, $primary_key, $columns, $columns_data, $_dbprefix;
	public $cache = false;

	public function __construct()
	{
		parent::__construct();
		$this->_db = $this->db;
		if ($this->cache) $this->_db->cache_on(); else $this->_db->cache_off();
		$this->_fetch_table();
	}

	public function where($data)
	{
		foreach ($data as $key => $value) {
			$this->_db->where($key, $value);
		}
		return $this;
	}

	public function or_where($data)
	{
		foreach ($data as $key => $value) {
			$this->_db->or_where($key, $value);
		}
		return $this;
	}

	public function order_by($column, $direction = 'ASC')
	{
		$this->_db->order_by($column, $direction);
		return $this;
	}

	public function get_result($limit = null, $offset = null)
	{
		$qr = $this->_db->get($this->table_name, $limit, $offset);
		return $qr->result();
	}

	public function get_row($limit = null, $offset = null)
	{
		$qr = $this->_db->get($this->table_name, $limit, $offset);
		return $qr->row();
	}

	public function get_by_id($id)
	{
		$this->_db->where(array($this->primary_key => $id));
		return $this->_db->get($this->table_name)->row();
	}

	public function query_result($query)
	{
		$qr = $this->_db->query($query);
		return $qr->result();
	}

	public function query_row($query)
	{
		$qr = $this->_db->query($query);
		return $qr->row();
	}

	public function insert($data)
	{
		$data = $this->_fix_before_change($data);
		$this->_db->insert($this->table_name, $data);
		return $this->_db->affected_rows();
	}

	public function update($data, $id)
	{
		$data = $this->_fix_before_change($data);
		$this->_db->where(array( $this->primary_key => $id));
		$this->_db->update($this->table_name, $data);
		return $this->_db->affected_rows();
	}

	public function delete($id)
	{
		$this->_db->where(array($this->primary_key => $id));
		$this->_db->delete($this->table_name);
		return $this->_db->affected_rows();
	}

	public function get_table_name($prefix = false)
	{
		if ($prefix)
			return $this->db->dbprefix($this->table_name);
		else
			return $this->table_name;
	}

	public function _fetch_table()
	{
		$class_name = strtolower(get_class($this));
		$this->table_name =  preg_replace('/((^(mdl_))|((_mdl)$)|((_model)$))/', '', $class_name);
		$this->columns = $this->_db->list_fields($this->table_name);
		$this->columns_data = $this->_db->field_data($this->table_name);
		foreach ($this->columns_data as $col) {
			if ($col->primary_key) {
				$this->primary_key = $col->name;
			}
		}
		$this->_dbprefix = $this->db->dbprefix;
	}

	public function _fix_before_change($data)
	{
		foreach ($data as $key => $item) {
			if (!in_array($key, $this->columns)) unset($data[$key]);
		}
		return $data;
	}
}

/* End of file base_Model.php */
/* Location: ./application/models/base_Model.php */
