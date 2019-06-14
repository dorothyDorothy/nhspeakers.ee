<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use ClimbingTurn\LastingImpressions\libraries\Config as LiConfig;
/**
 * Lasting Impressions Entries Library
 *
 * @package     lasting_impressions
 * @author      Dorothy Molloy / Anthony Mellor
 * @link 		http://www.climbingturn.co.uk/software/lasting-impressions-pro
 * @copyright 	Copyright (c) 2016, Climbing Turn Ltd
 *
 *  This file is part of lasting_impressions.
 *	Requires ExpressionEngine 3.0.0 or above
 */

class Lasting_impressions_entries {
  private static $default_limit = 15;
  private static $default_expires = 30;
  private $entries;
  private $_dbSettings;

  function __construct()
  {
    $this->_dbSettings = ee('Model')->get('lasting_impressions:Settings')->first(); 
    $this->entries = $this->_get_data_from_cookie();
  }

	// ------------------------------------------------------------------------------

  public function add($entry_id, $make_revisits_most_recent)
  {
    $entries = $this->_get_data_from_cookie();

    $limit = $this->_get_limit();
    if (count($entries) >= $limit) 
    {
      array_shift($entries);
    }

    foreach($entries as $key => & $entry)
    {
      if($entry['entry_id'] == $entry_id)
      {
        $entry['view_count'] ++;
        $entry['last_view'] = time();				
        if($make_revisits_most_recent == "yes")
        {
          $new_entry = $entry;
          $entries[] = $new_entry;
          unset($entries[$key]);
        }
        $cookieset = $this->_store_data_to_cookie($entries);
        return $cookieset;
      }
    }

    $new_entry = array('entry_id' 	=> $entry_id,
     'view_count'	=> 1,
     'last_view'	=> time());

    $entries[] = $new_entry;
    $this->entries = $entries;
    $cookieset = $this->_store_data_to_cookie($entries);
    return $cookieset;
  }

  public function record($entry_id) {
    $ip_address = ee()->input->ip_address();
    $user_agent = ee()->input->user_agent();
    $time_logged = time();

    if(session_id() == "") {
      session_start();
    }
    $session_id = session_id();
    $member_id = ee()->session->userdata['member_id'];
    $data = array(
      'entry_id' => $entry_id,
      'member_id' => $member_id,
      'session_id' => $session_id,
      'ip_address' => $ip_address,
      'user_agent' => $user_agent,
      'entry_date' => $time_logged);

    $res = $this->_save_to_data_table($data);
    return $res;
  }

  private function _save_to_data_table($viewed_data){
    $insert_query = ee()->db->insert(LiConfig::getConfig()['data_table'], $viewed_data);
    return ee()->db->_error_number();
  }


	// ------------------------------------------------------------------------------


  public function delete($entry_id)
  {

    $entries = $this->_get_data_from_cookie();

    foreach ($entries as $key => $entry) 
    {
     if($entry['entry_id'] == $entry_id)	
     {
      unset($entries[$key]);
      break;
    }
  }

  $cookieset = $this->_store_data_to_cookie($entries);
  return $cookieset;
}


  public function get()
  {
    return $this->_get_data_from_cookie();
  }



   /*
    * This counts the number of entries in the cookie.  Useful for detecting whether or not 
    * you wish to display any lasting impressions to the user
    */
   public function count()
   {
    $entries = $this->_get_data_from_cookie();
    return count($entries);
  }



  private function _get_data_from_cookie()
  {
    ee()->load->library('logger');
    if($this->entries === NULL || $this->entries == '')
    {
      $entry_data = ee()->input->cookie(LiConfig::getConfig()['package'], TRUE);  

      if($entry_data == '')
      {
       $entry_data = array();
      } else  {
        $entry_data = unserialize(base64_decode($entry_data));
      }

      $this->entries = $entry_data; // cache it
      return $entry_data;
		}
		else
		{
			return $this->entries;
		}
	}	




	private function _store_data_to_cookie($entry_data)
	{
		$expires = $this->_set_expires();
		$this->entries = $entry_data;
		$entry_data = base64_encode(serialize($entry_data));
		
		$prefix = "";
		$config_prefix = ee()->config->item('cookie_prefix');
		if (isset($config_prefix) && $config_prefix != "") {
			$prefix = $config_prefix . "_";
		} else {
			$prefix = "exp_";
		}

    $cookieset = setcookie($prefix . LiConfig::getConfig()['package'], $entry_data, time()+3600*24 * $expires, '/', '', FALSE);
    return $cookieset;
	}	




	private function _get_limit()
	{
		$tmp_limit = $this->_dbSettings->limit;
		if ($tmp_limit == -1)
		{
			$limit = self::$default_limit; 
		}
		else
		{
			$limit = $tmp_limit;
		}
		return $limit;
	}

	

	private function _set_expires()
	{
		$expires = $this->_dbSettings->expires;
		if ($expires == -1) {
			$expires = self::$default_expires;
		}
		return $expires;
	}	

}
