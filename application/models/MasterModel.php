<?php
/**
 * Emission objeto
 *
 *
 * @package  Emission
 * @author   Edson Costa
 * @version  v0.3
 * @access   public
 * @see      http://www.edsoncosta.com/
 */

defined('BASEPATH') or exit('<h1 style="text-transform: uppercase; font-weight: 300; color: red; text-align:center; margin-top: 20%;">Acesso não permitido</h1>');

class MasterModel extends CI_Model
{


	function __construct()
	{
		parent::__construct();
	}

	public function findAll($table, $terms, $columns = "*")
	{
		return $this->db->query("SELECT {$columns} FROM {$table} WHERE {$terms};");
	}


	public function find($table, $terms, $columns = "*")
	{
		try {
			$find = $this->db->query("SELECT {$columns} FROM {$table} WHERE {$terms};");
			if (!$find) {
				throw new Exception("erro");
				return false;
			}
			return $find;
		} catch (Exception $e) {
			return false;
		}
	}

	public function query($query)
	{
		return $this->db->query($query);
	}

	public function insert_query($query)
	{
		return $this->db->query($query);
	}

	public function insert($table, $data, $returnId = false)
	{
		$this->db->insert($table, $data);
		if ($this->db->affected_rows() == '1') {
			if ($returnId == true) {
				return $this->db->insert_id($table);
			}
			return true;
		}
		return false;
	}

	public function insert_data($table, $data, $campoID = null, $idColeta = null)
	{
		$this->db->insert($table, $data);
		if ($this->db->affected_rows() == '1') {
			$id = $this->db->insert_id($table);
			if ($idColeta != null) {
				return $this->db->select('*')->from($table)->where($campoID, $idColeta)->get();
			} else {
				return $this->db->select('*')->from($table)->where($campoID, $id)->get();
			}


		}
		return false;
	}

	public function update($table, $dados, $campoID, $id, $columns = "*")
	{
		$this->db->where($campoID, $id);
		$this->db->update($table, $dados);
		if ($this->db->affected_rows() >= 0) {
			return $this->db->select("$columns")->from($table)->where('id', $id)->get()->row();;
		}
		return false;
	}

	public function updateLite($table, $dados, $campoID, $ID)
	{
		$this->db->where($campoID, $ID);
		$this->db->update($table, $dados);
		if ($this->db->affected_rows() >= 0) {
			return true;
		}
		return false;
	}


	public function delete($table, $campoID, $ID)
	{
		$this->db->where($campoID, $ID);
		$this->db->delete($table);
		if ($this->db->affected_rows() == '1') {
			return true;
		}
		return false;
	}

	public function insert_table_tmp($etiqueta)
	{
		return $this->db->query(
			"INSERT INTO item_coleta 
    		(numero,cartao,remetente,objeto,peso,cep_destinatario,servico,altura,largura,comprimento,diametro,status_afericao) 
    		SELECT numero,cartao,remetente,objeto,peso,cep_destinatario,servico,altura,largura,comprimento,diametro,status_afericao
    		FROM item_coleta_tmp 
			WHERE objeto = '{$etiqueta}';");
	}


	public function coletas($type = '', $limit = 0, $offset = 0)
	{

		$where = " WHERE t.status = 'aberto' OR t.status = 'embarcado'";
		if (!empty($type)) {
			$where = " WHERE t.status != 'aberto' AND t.status != 'embarcado'";
		}

		$l = '';
		if($limit != 0 || $offset != 0){
			$l = "LIMIT $limit,$offset";
		}

		$result = $this->db->query("SELECT t.id as opID, 
							     t.id_motorista, 
							     t.data_abertura, 
							     t.data_fechamento, 
							     t.data_embarcacao, 
							     t.status, 
       							 m.name_motorist
						  FROM coletas t 
						      LEFT JOIN motorists m ON m.id = t.id_motorista
						      {$where} ORDER BY opID DESC {$l}");

		if($result->result()){
			return $result;
		}
		return null;
	}
}
