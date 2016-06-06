<?php

use Drupal\lists\DB\ListsDB;

class BillItem {
	function insert($user, $listid, $item) {		
		$db = ListsDB::getInstance();
		$name = preg_replace('/[^A-Za-z0-9\-]/', 
			"", $item['name']);
		$value = preg_replace('/[^A-Za-z0-9\-]/',
			"", $item['value']);
		$nextdue = preg_replace('/[^A-Za-z0-9\-]/',
			"", $item['nextdue']);
		$frequency = preg_replace('/[^A-Za-z0-9\-]/',
			"",$item['frequency']);
		
		$sql = <<<SQL
			insert into bill_items (
				item_name, item_price, list_id, next_due, bill_freq)
			values ($name, $value, $listid, $nextdue, $freq)
SQL;
	
		if(!$result = $db->getConnection()->query($sql)){
			\Drupal::logger('lists')->error('An error occurred inserting Bill Item');
		}
	}
}
