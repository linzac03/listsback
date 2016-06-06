<?php

namespace Drupal\lists\DB;
use Drupal\lists\DB\ListsDB;

class BaseQueries {
	/**
	* For lack of a better place I'm going to put these DB queries here
	*/
	static function getMyLists($user) {
		$db = ListsDB::getInstance();
		$sql = <<<SQL
			select list_name from user_lists
			where user_id = (
				select user_id from app_users
				where username = '$user')
SQL;
		if(!$result = $db->getConnection()->query($sql)){
			\Drupal::logger('lists')->error('An Error occurred getting lists' . $db->getConnection()->error);
			return array('status' => 'error');
		}
			
		$arr = array();
		$i = 0;	
		while($row = $result->fetch_assoc()){
			$arr[$i++] = array(
				'name' => $row['list_name'],
			);
		}	
		return $arr;
	}

	static function insertListItem($user, $list_id, $item) {
		switch($item['type']) {
			case "bill":
				BillItem::insert($user, $list_id, $item);
				break;	
			default:
				break;
		}
	}

	static function insertNewList($user, $data) {
		$db = ListsDB::getInstance();
		$listname = preg_replace('/[^A-Za-z0-9\-]/', "", $data['name']);
		$userid = getUserId($user);	
		$sql = <<<SQL
			insert into user_lists (list_name, user_id)
			values ($listname, $userid) 
SQL;
		if(!$result = $db->getConnection()->query($sql)){
				\Drupal::logger('lists')->error('An error occurred inserting new list');
		}

		$newlistid = getListId($listname);
		foreach($data['items'] as $item) {
			switch($item['type']) {
				case 'bill':
					$db->insertBillItem($user, $listid, $item);
					break;
				case 'text':
					$db->insertTextItem($user, $listid, $item);
					break;
				default:
					break;
			}
		}	
	}

	static function getUserId($user) {
		$db = ListsDB::getInstance();
		$sql = <<<SQL
			select user_id from app_users
			where username = $user
SQL;
		if(!$result = $db->getConnection()->query($sql)){
				\Drupal::logger('lists')->error('An error occurred getting user ID');
		}
		$arr = array();
		$i = 0;	
		$row = $result->fetch_assoc();
		return $row['user_id'];	
	}	
	
	static function check_user_reminders($user) {}
}
