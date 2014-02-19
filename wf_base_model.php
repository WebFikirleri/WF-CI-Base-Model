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

	public function get($limit = null, $offset = null)
	{
		return $this->_db->get($this->table_name, $limit, $offset);
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

	public function get_by_many($data)
	{
		$this->_db->where($data);
		return $this->get_result();
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
		return $this->_db->insert_id();
	}

	public function insert_many($data)
	{
		$this->_db->insert_batch($this->table_name, $data);
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

	public function limit($limit = null, $offset = null)
	{
		$this->_db->limit($limit, $offset);
		return $this;
	}

	public function like($column, $match)
	{
		$this->_db->like($column, $match);
		return $this;
	}

	public function or_like($column, $match)
	{
		$this->_db->or_like($column, $match);
		return $this;
	}

	public function not_like($column, $match)
	{
		$this->_db->not_like($column, $match);
		return $this;
	}

	public function or_not_like($column, $match)
	{
		$this->_db->or_not_like($column, $match);
		return $this;
	}

	public function get_table_name($prefix = false)
	{
		if ($prefix !== false)
			return $this->db->dbprefix($this->table_name);
		else
			return $this->table_name;
	}

	public function count_all()
	{
		return $this->_db->count_all($this->table_name);
	}

	public function _fetch_table()
	{
		$class_name = strtolower(get_class($this));
		$this->table_name =  preg_replace('/((^(mdl_))|((_mdl)$)|((_model)$))/', '', $class_name);
		if (!$this->db->table_exists($this->table_name))
			show_error('Table not exists! Please read <a href="https://github.com/WebFikirleri/WF-CI-Base-Model">documentation.</a>');
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

/* End of file wf_base_model.php */
/* Location: ./application/models/wf_base_model.php */
