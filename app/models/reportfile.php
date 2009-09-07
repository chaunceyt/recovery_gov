<?php
class Reportfile extends AppModel {

	var $name = 'Reportfile';

	//The Associations below have been created with all possible keys, those that are not needed can be removed
	var $hasMany = array(
		'Majoractivity' => array(
			'className' => 'Majoractivity',
			'foreignKey' => 'reportfile_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'Weeklyreport' => array(
			'className' => 'Weeklyreport',
			'foreignKey' => 'reportfile_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);

}
?>