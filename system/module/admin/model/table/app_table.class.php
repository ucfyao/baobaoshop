<?php
class app_table extends table{
	protected $_validate = array( 
		array('identifier', 'require', '{admin/role_name_require}', table::MUST_VALIDATE), 
	);
	public function fetch_by_identifier($identifier) {
		return $this->where(array('identifier' => $identifier))->find();
	}
}