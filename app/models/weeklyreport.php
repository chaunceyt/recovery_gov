<?php
class Weeklyreport extends AppModel {

	var $name = 'Weeklyreport';

	//The Associations below have been created with all possible keys, those that are not needed can be removed
	var $belongsTo = array(
		'Reportfile' => array(
			'className' => 'Reportfile',
			'foreignKey' => 'reportfile_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

}
?>