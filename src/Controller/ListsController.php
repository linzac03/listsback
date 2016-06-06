<?php

/**
 * @file
 * Contains \Drupal\lists\Controller\ListsController
 */

namespace Drupal\lists\Controller;
use Drupal\lists\DB\ListsDB;

class ListsController {
	
  public function lists($action) {
		
		\Drupal::service('page_cache_kill_switch')->trigger();
		switch($action) {
      case 'moo':
        //$quote = "/usr/local/lib/scrapy/moo/quote";
        $escaped = escapeshellcmd("./moo.sh");
        system($escaped);
        $moosay = fopen("quote","r");
 				$out = fread($moosay,filesize("quote"));
        fclose($moosay);
        return array(
                 '#markup' => "<p>" . $out . "</p>"
               );
      
			case 'db':
				$db = ListsDB::getInstance();
				$sql = <<<SQL
					select * from bill_items where 
						( select id from testlist
							where id = 1 
						) = list_id
SQL;

				if(!$result = $db->getConnection()->query($sql)){
					die('There was an error running the query [' . $db->error . ']');
				}
				
				$arr = array();
				$i = 0;	
				while($row = $result->fetch_assoc()){
					$arr[$i++] = array(
						'name' => $row['item_name'],
						'price' => $row['item_price']
					);
				}	
				
				die(JSON_encode($arr));	
			default:
        return array('#markup' => t("Hello World"));
    }
  }

  public function extendedlists($action, $subaction) {
		
		\Drupal::service('page_cache_kill_switch')->trigger();
		switch($action) {
			case 'db':
				$db = ListsDB::getInstance();
				switch($subaction) {
					case 'post':
						$ph = PostHandler::getInstance();
						$resp = $ph->handle($_POST);
						$_POST = array();
						die(JSON_encode($resp)); //this is how I generally want to handle passing back to android
						break;
					default:
						die(array('error'=>'failted'));
				}
			default:
        return array('#markup' => t("Hello World"));
    }
  }
}
