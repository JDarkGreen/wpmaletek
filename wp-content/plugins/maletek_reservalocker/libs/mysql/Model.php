<?php

require_once('Database.php');

/**
 * PHP 4 Database Model Class
 * Sets up the Model interface
 */
 
class Model extends Database {

	var $table;
	var $fields;
	var $id;
        var $primaryKey='id';
	
	/**
	 * Constructor function, gets table name, sets the ID of a current row
	 * and gets all field names from the table
	 *
	 * @param   integer   ID of row to update or delete
	 */
	function __construct($id = '') {
			
		if (empty($this->table))
			$this->table = strtolower(substr(get_class($this), 0, -6));
		
		if (empty($this->fields)) {
			$this->query("SHOW COLUMNS FROM $this->table");
			foreach ($this->get() as $col)
				$this->fields[] = $col->Field;
		}
			
		if (!empty($id)) {
			$this->id = $id;
		}
	
	}
	
	/**
	 * Very simple select
	 *
	 * @param    string   What to order by
	 * @param    string   Where statement
	 * @param    string   Columns to select
	 * @return   result   Result of query
	 */
	function select($where = '', $orderby='',  $cols = '*', $limit = '') {
	
		if (!empty($this->id) && empty($where)) $where .= $this->primaryKey." = $this->id";
		
		return parent::select($this->table, $orderby, $where, $cols, $limit);
	
	}
	
	/**
	 * Insert a new record, or update existing
	 *
	 * @param   array   Data to insert (usually $_POST)
	 */
	function save($data) {
                
		if (!is_array($data))
			return false;
                
		for ($i=0; $i<count($this->fields); $i++) {
			$set[$this->fields[$i]] = !empty($data[$this->fields[$i]]) ? $data[$this->fields[$i]] : '';
		}
                
		if (empty($this->id)){ 
                    return $this->insert($this->table, $set);
                }			
		else {
			foreach ($set as $key => $val) {
				if (empty($set[$key]) || $set[$key] == '')
					unset($set[$key]);
			}
			return $this->update($this->table, $set, $this->primaryKey." = '$this->id'");
		}
	
	}
	
	/**
	 * Select from table
	 *
	 * @param   string   Order by
	 * @param   string   Where clause
	 * @param   string   Columns to select
	 */
	function find_all($orderby = 'id DESC', $where = '', $cols = '*', $limit = '') {
	
		$orderby = (!empty($orderby)) ? $orderby : 'id DESC';
		$where = (!empty($where)) ? $where : '';
		$cols = (!empty($cols)) ? $cols : '*';
		$limit = (!empty($limit)) ? $limit : '';
	
		$this->select($orderby, $where, $cols, $limit);
		return $this->get();
	
	}
	
	/**
	 * Select single row
	 *
	 * @param   string   Order by
	 * @param   string   Where clause
	 * @param   string   Columns to select
	 */
	function find($orderby = 'id DESC', $where = '', $cols = '*', $limit = '') {
	
		$orderby = (!empty($orderby)) ? $orderby : 'id DESC';
		$where = (!empty($where)) ? $where : '';
		if (!empty($this->id) && empty($where)) $where .= "id = $this->id";
		$cols = (!empty($cols)) ? $cols : '*';
		$limit = (!empty($limit)) ? $limit : '';
	
		$this->select($orderby, $where, $cols, $limit);
		return $this->get_first();
	
	}
	
	/**
	 * Delete row or rows
	 *
	 * @param   string   Where clause
	 */
	function delete($where) {
	
		if (!empty($this->id) && empty($where)) $where .= "id = $this->id";
	
		return parent::delete($this->table, $where);
	
	}
	

}