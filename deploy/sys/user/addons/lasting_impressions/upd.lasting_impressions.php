<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @package	lasting_impressions Installer
 * 
 * @package     lasting_impressions
 * @author      Dorothy Molloy / Anthony Mellor
 * @link 		http://www.climbingturn.co.uk/software/ee-add-ons/lasting-impressions-pro
 * @copyright 	Copyright (c) 2015 / 2016, Climbing Turn Ltd
 * 
 *
 *  This file is part of lasting_impressions.
 *	Requires ExpressionEngine 3.0.0 or above
 */

use ClimbingTurn\LastingImpressions\libraries\Config as LiConfig;


class Lasting_impressions_upd  {
	
	public $version;
	private $class_name;
	private $settings = array();
        private $config;
	
	
public function __construct() {
    $this->version =  LiConfig::getConfig()['version'];
    ee()->load->library('logger');
    $this->class_name = ucfirst(LiConfig::getConfig()['package']);
}
	
	//------------ install --------------------------
	
    public function install() {
        $table_installed = $this->_create_lastingimpressions_table();
        $data_table_installed = $this->_create_lastingimpressions_data_table();
        $module_inserted = $this->_update_modules_table();
        $action_inserted = $this->_add_to_actions_table();
        $success = $table_installed && $data_table_installed && $module_inserted && $action_inserted;
        $return_val = ($success == 1)? TRUE: FALSE;
        ee()->logger->developer( LiConfig::getConfig()['name'] . ' installed = ' . $success);
        ee('CP/Alert')->makeStandard(lang('lasting_impressions_module_name'))
                                ->asSuccess()
                                ->withTitle(lang('lasting_impressions_install_sucess'))
                                ->addToBody(lang('lasting_impressions_install_sucess'))
                                ->now();
        return ($return_val);
    }
	
    private function _create_lastingimpressions_table()
    {
            ee()->load->dbforge();
            ee()->dbforge->add_field(array(
                'li_id'         =>     array('type' => 'int', 'constraint' => 6, 'unsigned' => TRUE, 'auto_increment' => TRUE),
                'channel_id' => 	array('type' => 'int', 'constraint' => '10', 'unsigned' => TRUE),
                'site_id' => 	array('type' => 'int', 'constraint' => '10', 'unsigned' => TRUE),
                'limit' => 	array('type' => 'int', 'constraint' => '10', 'unsigned' => TRUE),
                'enabled' => array('type' => 'TINYINT', 'constraint' => '1', 'unsigned' => TRUE),
                'expires' => array('type' => 'int', 'constraint' => '10', 'signed' => TRUE)
            ));
             ee()->dbforge->add_key('li_id', TRUE);
             
            $res = ee()->dbforge->create_table(LiConfig::getConfig()['package'] );
            if (! $res) 
            {
                    ee()->logger->developer(LiConfig::getConfig()['name'] . ' Failed to create exp_' . LiConfig::getConfig()['package'] . ' table');
                    return false;
            }
            return true;        
    }

    private function _create_lastingimpressions_data_table() {
            ee()->load->dbforge();
            ee()->dbforge->add_field(array(
                'lid_id' =>    array('type' => 'int', 'constraint' => 6, 'unsigned' => TRUE, 'auto_increment' => TRUE),
                'entry_id' => 	array('type' => 'int', 'constraint' => '10', 'unsigned' => TRUE),
                'member_id' => array('type' => 'int', 'constraint' => '10', 'signed' => TRUE),
                'session_id' => array('type' => 'varchar', 'constraint' => '40', 'null' => FALSE),
                'ip_address' => array('type' => 'varchar', 'constraint' => '45', 'null' => TRUE),
                'user_agent' => array('type' => 'varchar', 'constraint' => '512', 'null' => TRUE),
                'entry_date' => array('type' => 'int', 'constraint' => '10', 'unsigned' => TRUE)
            ));	
            ee()->dbforge->add_key('lid_id', TRUE);

            $res = ee()->dbforge->create_table(LiConfig::getConfig()['data_table']);
            if (! $res) 
            {
                    ee()->logger->developer(LiConfig::getConfig()['name'] . ' Failed to create exp_' 
                            . LiConfig::getConfig()['data_table'] . ' with result ' . $res);
                    return false;
            }
            return true;			
    }
	
	
    private function _update_modules_table() {
            $module_info = array(
                            'module_name' => $this->class_name,
                            'module_version' => $this->version,
                            'has_cp_backend' => 'y',
                            'has_publish_fields' => 'n'
            );
            $res = ee()->db->insert('modules', $module_info);
            if (! $res) {
                    ee()->logger->developer(LiConfig::getConfig()['name'] . ' Failed to insert ' 
                                    . LiConfig::getConfig()['package'] . ' module into exp_modules table');
                    return false;
            }
            return true;
    }
	
	
    private function _add_to_actions_table(){
            $data = array(
                            'class' => ucfirst(LiConfig::getConfig()['package']),
                            'method' => 'remove_item_from_cookie'
            );
            $res = ee()->db->insert('actions', $data);
            if(!$res)
            {
                    ee()->logger->developer(LiConfig::getConfig()['name'] . ' Failed to insert '
                                    . LiConfig::getConfig()['package'] . ' method remove_item_from_cookie into exp_actions table');
                                    return false;
            }
            return true;
    }
	
	
	//------------ uninstall --------------------------
	
    public function uninstall() {
            $query = ee()->db->select('module_id')
                    ->from('modules')
                    ->where('module_name', $this->class_name)
                    ->get();

            $removed_mod_member = $this->_remove_refs('module_id', $query->row('module_id'), 'module_member_groups'); //remove from Module member groups
            $removed_ref = $this->_remove_refs('module_name', $this->class_name, 'modules'); //remove from modules
            $removed_li_table = $this->_remove_lastingimpressions_table();
            $removed_li_data_table = $this->_remove_lastingimpressions_data_table();
            $removed_action = $this->_remove_refs('class', $this->class_name, 'actions');
            $uninstalled = $removed_mod_member && $removed_ref && $removed_li_table && 
                    $removed_li_data_table && $removed_action;
            ee()->logger->developer(LiConfig::getConfig()['name'] . ' uninstalled = ' . $uninstalled);
             ee('CP/Alert')->makeStandard(lang('lasting_impressions_module_name'))
                   ->asSuccess()
                   ->withTitle(lang('lasting_impressions_uninstall_sucess'))
                   ->addToBody(lang('lasting_impressions_uninstall_sucess'))
                   ->now();
            $return_val = ($uninstalled == 1)? TRUE: FALSE;
            return ($return_val);
    }
	
	
	private function _remove_refs($identifier, $item, $tableName) {
		ee()->db->where($identifier, $item);
		$res = ee()->db->delete($tableName);
		if (! $res) {
			ee()->logger->developer(LiConfig::getConfig()['name'] . ' Failed to remove ' . $identifier . ' ' . $item . ' from ' . $tableName);
			return false;
		}
		return true;
	}
	
	private function _remove_lastingimpressions_table() {
		ee()->load->dbforge();
		$res = ee()->dbforge->drop_table(LiConfig::getConfig()['package']);
		if (! $res) {
			ee()->logger->developer(LiConfig::getConfig()['name'] . ' Failed to drop exp_' . LiConfig::getConfig()['package']);
			return false;
		}
		return true;
	}
	

	private function _remove_lastingimpressions_data_table() {
		ee()->load->dbforge();
		$res = ee()->dbforge->drop_table(LiConfig::getConfig()['data_table']);
		if (! $res) {
			ee()->logger->developer(LiConfig::getConfig()['name'] . ' Failed to drop exp_' . LiConfig::getConfig()['data_table']);
			return false;
		}
		return true;		
	}
	
	//------------ update --------------------------
	
	public function update($current = '') 
	{	
		if (! version_compare($current, $this->version, '='))
		{
			return FALSE;
		} elseif ((defined(LiConfig::getConfig()['has_datatable_version']) == false) ||
		   version_compare($current, LiConfig::getConfig()['has_datatable_version']) >= 0 ) {
			//LiConfig::getConfig()['has_datatable_version'] is the version where we started using the data table, any versions beyond this will already have that table installed
			$data_created = true;

		} else {
			$data_created = $this->_create_lastingimpressions_data_table();
		}

		if ($data_created) {
			$this->_update_version_in_modules_table();
		} else {
			return false;
		}

		return TRUE;
	}
	
	private function _update_version_in_modules_table()
	{
		$data = array('module_version' => $this->version);
		ee()->db->where('module_name', LiConfig::getConfig()['package']);
		ee()->db->update('modules', $data);
	}
	
	// private function add_expires_column()
	// {
	// 	ee()->load->dbforge();
	// 	$field = array('expires'=>array('type'=>'int'));
	// 	$col_added = ee()->dbforge->add_column(LiConfig::getConfig()['package'], $field);
	// 	if ($col_added){
	// 		ee()->logger->developer('Lasting Impressions: Updated exp_' . LiConfig::getConfig()['package'] . ' added new column \'expires\'');
	// 		return true;
	// 	}else {
	// 		ee()->logger->developer('Lasting Impressions: Update failed to add new column \'expires\'');
	// 		return false;
	// 	}		
	// }
}
/* End of file upd.lasting_impressions.php */
/* Location: ./system/expressionengine/third_party/lasting_impressions/upd.lasting_impressions.php */