<?php

App::uses('HttpSocket', 'Network/Http');
class TwitterSource extends DataSource {

	protected $_schema = array(
			'id' => array('type' => 'integer', 'key' => 'primary'),
			'text' => array('type' => 'string'),
			'created_at' => array('type' => 'string')
		);

	public function __construct($config) {
		parent::__construct($config);
		$this->sourceUrl = $this->config['sourceUrl'];
		unset($this->config['sourceUrl']);
		unset($this->config['datasource']);
		$this->Http = new HttpSocket();
	}

	public function listSources() {
		return null;
	}

	public function describe($Model) {
		return $this->_schema;
	}

	public function read($model, $queryData = array()) {
		$HttpSocket = $this->Http;
		$conditions = array(
		);
		$results = $HttpSocket->get($this->sourceUrl, $this->config);
		$results = json_decode($results);
		$postsOnly = array();
		foreach ($results as $resultsValue) {
			$resultsValue = get_object_vars($resultsValue);
			array_push($postsOnly, array($resultsValue['text'], $resultsValue['created_at']));
		}
		return $postsOnly;
	}

	public function create($model, $fields = array(), $values = array()) {
		return false;
	}

}