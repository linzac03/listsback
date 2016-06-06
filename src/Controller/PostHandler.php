<?php

namespace Drupal\lists\Controller;
use Drupal\lists\DB\BaseQueries;

class PostHandler {
	private static $_instance;
  public static function getInstance() {
		if(!self::$_instance) self::$_instance = new self();
		return self::$_instance;
	}

	public function __construct() {}

	function handle($post) {	
		//assume this for now since no users are implemented
		$user = 'tester'; 
		
		switch($post['query']) {
			case 'newlist':
				/*
				* $data {name=newlistname, 
				*	items={billItem|textItem|elecItem|clothItem|grocItem}} 
				*/
				$data = JSON_decode($post['data']);
				BaseQueries::insertNewList($user, $data);
				return array('status' => 'success');
			case 'getMyLists':
				return BaseQueries::getMyLists($user);	
		}
	}
}
