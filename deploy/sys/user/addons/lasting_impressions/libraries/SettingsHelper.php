<?php

namespace ClimbingTurn\LastingImpressions\Libraries;


/**
 * Lasting Impression Settings Helper class
 *
 * @package     lasting_impressions
 * @author      Dorothy Molloy / Anthony Mellor
 * @link 		http://www.climbingturn.co.uk/software/lasting-impressions-pro
 * @copyright 	Copyright (c) 2016, Climbing Turn Ltd
 *
 *  This file is part of lasting_impressions.
 *	Requires ExpressionEngine 3.0.0 or above
 * 
 */
class SettingsHelper {




  /* ======================== Settings =============================  
        
	private function _get_db_setting($setting_name)
	{
		if ( ! empty($this->db_settings[$setting_name]))
		{
			return $this->db_settings[$setting_name];
		}
		$res = $this->_get_settings_rows();
		if ($res)
		{
			return $this->db_settings[$setting_name];
		}
		return null;
	}


	
	private function _get_settings_rows()
	{
		$query = $this->_get_lasting_impressions_settings();
		if ($query->num_rows() > 0){
			$res = $query->result_array();
			$this->_cacheDBValues($res);
			return $res;
		} else {
			return false;
		}
	}
	


	private function _cacheDBValues($res)
	{
		foreach ($res as $row){
			$this->db_settings['limit']= $row['limit'];
			$this->db_settings['enabled'] = ($row['enabled'] == 1)? 'y' : 'n';
			$this->db_settings['expires'] = $row['expires'];
		}
	}

 */

	
	/**
	 * Save settings to DB

	public function save($settings) 
	{
		$query = $this->_get_lasting_impressions_settings();
		if ($query->num_rows() > 0) {
			$update_query = ee()->db->update(
					LiConfig::getConfig()['package'] ,
					$settings, ''
			);
		}else {
			$insert_query = ee()->db->insert(
					LiConfig::getConfig()['package'] ,
					$settings
			);
		}
		$this->db_settings['limit'] = $settings['limit'];
		$this->db_settings['enabled'] = $settings['enabled'];
		$this->db_settings['expires'] = $settings['expires'];
	}



	private function _get_lasting_impressions_settings()
	{
		$select = array('limit', 'enabled', 'expires');
		$query = ee()->db->select($select)
		->from(LiConfig::getConfig()['package'] )
		->get();
		return $query;
	}

	 */

                
//                private function _get_totals_by_entry_id(){
//                    $query = ee()->db->select("count(*), d.entry_id, t.title, d.member_id, d.session_id, d.ip_address, d.user_agent, d.entry_date")
//                            ->from(LiConfig::getConfig()['data_table']  . ' d')
//                            ->join('channel_titles t', 'd.entry_id', 't.entry_id', 'inner')
//                            ->group_by('d.entry_id');
//                    return $query;
//                }    
}