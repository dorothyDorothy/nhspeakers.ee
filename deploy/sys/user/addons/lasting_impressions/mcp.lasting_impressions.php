<?php //


if (!defined('BASEPATH'))
  exit('No direct script access allowed');

use EllisLab\ExpressionEngine\Library\CP\Table;
use EllisLab\ExpressionEngine\Library\CP\Pagination;
use ClimbingTurn\LastingImpressions\libraries\UrlHelper;
use ClimbingTurn\LastingImpressions\libraries\DataHelper;
use ClimbingTurn\LastingImpressions\libraries\Config as LiConfig;


/**
 * Lasting Impression Control Panel class
 *
 * @package     lasting_impressions
 * @author      Dorothy Molloy / Anthony Mellor
 * @link 		http://www.climbingturn.co.uk/software/ee-add-ons/lasting-impressions-pro
 * @copyright 	Copyright (c) 2015 / 2016, Climbing Turn Ltd
 * 
 *  This file is part of lasting_impressions.
 *	Requires ExpressionEngine 3.0.0 or above
 */
class Lasting_impressions_mcp {

  private $site_id;
  private $_mod_url;
  private $_base_url;
  private $_dbSettings;
  private $_dbData;
  private $_settings;
  private $_page_config;

  public function __construct() {
    $this->_mod_url = UrlHelper::getMcpUrl();
    $this->_base_url = UrlHelper::getMcpBaseUrl();
    $settings = ee('Model')->get('lasting_impressions:Settings')->first(); 
    if (isset($settings) ) {
        $this->_dbSettings = $settings;
    } else {
        $this->_dbSettings = ee('Model')->make('lasting_impressions:Settings');
    }
    $this->_settings = array('limit' => 15, 'enabled' => 0, 'expires' => 30);
    $this->_page_config['per_page'] = 25;
    $this->_page_config['enable_query_strings'] = TRUE;
    $this->_page_config['page_query_string'] = TRUE;
  }


  public function index() {
    $title = lang('lasting_impressions_module_name');
    $this->_populate_view_furniture();
    $this->_load_packages();

    if (isset($_POST['submit'])) {
      $this->saveToDB();
    } else {
      $this->getSettingsFromDB();
    }
    $vars = $this->displaySettings();
    $vars[ 'package'] = LiConfig::getConfig()['package'];
    return $this->_load_view("mcp_index", $vars, LiConfig::getConfig()['name'], lang('breadcrumb_settings'));
  }
  
  private function _load_view($view_name, $vars, $breadcrumb, $heading){
      return array(
      'body'       => ee('View')->make(LiConfig::getConfig()['package'] . ':' . $view_name)->render($vars),
      'breadcrumb' => array(
        ee('CP/URL')->make('addons/settings/' . LiConfig::getConfig()['package'])->compile() => $breadcrumb
      ),
      'heading' => $heading
    );    
  }


  private function _populate_view_furniture() {
      $sidebar = ee('CP/Sidebar')->make();
      $header = $sidebar->addHeader('Lasting Impressions');
      $basicList = $header->addBasicList();
      $basicList->addItem("Settings", UrlHelper::getMcpUrl());
      $basicList->addItem('Reports',UrlHelper::getMcpUrl('report_all'));
  }

  private function _load_packages() {
    ee()->cp->load_package_css(LiConfig::getConfig()['package']);
    ee()->cp->load_package_js( 'lasting_impressions_cp');
  }

 /* ---------------- SETTINGS --------------------- */

  private function displaySettings() {
    $view = 'index';
    $vars = array(
        'view' => $view,
        'instructions' => lang('enable_to_edit'),
        'docs' => LiConfig::getConfig()['docs'],
        'partial_url' => $this->_mod_url, 
        'settings' => $this->_settings
        );
    return $vars;
  }

/* ---------------- REPORTS --------------------- */

  public function report_all() {
    $this->_populate_view_furniture();
    $this->_load_packages();
    $vars = $this->_set_common_report_vars();
    $vars['title'] = lang('view_all_data');
    $vars['report_id'] = 0;
    $current_page = $this->_get_post_or_zero('page') ?: 1;
    $data_helper = new DataHelper();
    $table = $data_helper->create_simple_table($this->_page_config['per_page'], $current_page);
    $vars['table'] = $table->viewData(ee('CP/URL', lang('lasting_impressions_module_name')));  
    
    //Pagination
    $base_url = UrlHelper::getMcpUrl('report_all');
    $num_items = ee('Model')->get('lasting_impressions:Data')->count();
    $vars['pagination']  = ee('CP/Pagination', $num_items)
                    ->currentPage($current_page)
                   ->perPage($this->_page_config['per_page'])
                    ->render($base_url);
    
    return $this->_load_view('mcp_reports', $vars, LiConfig::getConfig()['name'],  lang('breadcrumb_reports'));
  }


  public function groupby_report() {
    $this->_populate_view_furniture();
    $this->_load_packages();
    $vars = $this->_set_common_report_vars();
    $vars['title'] = lang('view_totals');
    $vars['report_id'] = 1;
    $current_page = $this->_get_post_or_zero('page') ?: 1;

    $data_helper = new DataHelper();
   $table = $data_helper->create_totals_table($this->_page_config['per_page'], $current_page);
   $vars['table'] = $table->viewData(ee('CP/URL', lang('lasting_impressions_module_name')));  
   
   //Pagination
   $base_url = UrlHelper::getMcpUrl('groupby_report');
   $num_items = $table->config['total_rows'];
    $vars['pagination']  = ee('CP/Pagination', $num_items)
                 ->currentPage($current_page)
                ->perPage($this->_page_config['per_page'])
                 ->render($base_url);
       
    return $this->_load_view('mcp_reports', $vars, LiConfig::getConfig()['name'],  lang('breadcrumb_reports'));
  }
  
  private function _set_common_report_vars(){
    $vars['module_url'] = $this->_mod_url;
    $vars['partial_url'] = $this->_base_url;
    $vars[ 'docs'] = LiConfig::getConfig()['docs'];
    $vars[ 'package'] = LiConfig::getConfig()['package'];
    $grouped_or_simple = ee()->input->get_post('method');
    if (strpos($grouped_or_simple, 'group') > -1) {
        $vars['method'] = 'export_grouped'; 
    }  else {
        $vars['method'] = 'export_data_all';
    }
    return $vars;
  }
  

// ---------------- Exports and Purging ----------
  
  public function export_data_all() {
    ee()->load->library('lasting_impressions_export');
     $data_helper = new DataHelper();
    $stats = $data_helper->get_all_recorded_data(false); 
    ee()->lasting_impressions_export->export("lastingImpressions_Data", $stats, false);
  }

  public function export_grouped() {
    ee()->load->library('lasting_impressions_export');
    $data_helper = new DataHelper();
    $stats = $data_helper->get_all_recorded_data(true); 
    ee()->lasting_impressions_export->export("grouped_lastingImpressions_Data", $stats, true);
  }

  public function purge_data_all() {
   $data_helper = new DataHelper();
   $res = $data_helper->purge_all_recorded_data();
    $this->_populate_view_furniture();
    $this->_load_packages();
    $vars = $this->_set_common_report_vars();
    $vars['resume_link'] = $this->_mod_url;
    
    return $this->_load_view('mcp_data_purged', $vars,  LiConfig::getConfig()['name'], lang('breadcrumb_reports'));
  }


//-------------- DB calls -----------------

  private function getSettingsFromDB() {
    $this->_settings['limit'] = $this->_dbSettings->limit;
    $this->_settings['enabled'] = $this->_dbSettings->enabled;
    $this->_settings['expires'] = $this->_dbSettings->expires;
  }


   private function _get_pagination_start($current_page) {
     $start = ($current_page == 1)? 0 : ($current_page -1) * $this->_page_config['per_page'];
     return $start;
   }


  private function saveToDB() {
    $setting_valid = true;
    if (isset($_POST['li-limit'])) {
      $limit = (int) $_POST['li-limit'];
      $expires = (int) $_POST['li-expires'];
      $this->_settings['enabled'] = $_POST['li-enabled'];

      if (is_int($limit)) {
        $this->_settings['limit'] = $limit;
      }
      if (is_int($expires)){
        $this->_settings['expires'] = $expires;
      }
    }

    if (is_int($limit) && is_int($expires)) {
      //$this->li_settings_model->save($this->settings);
      $this->_dbSettings->set(array(
                'limit' => $this->_settings['limit'],
                'enabled' => $this->_settings['enabled'],
                'expires' =>  $this->_settings['expires']
      ));
      $this->_dbSettings->save();
      
      ee('CP/Alert')->makeStandard(lang('lasting_impressions_module_name'))
        ->asSuccess()
        ->withTitle(lang('lasting_impressions_settings_change_success'))
        ->addToBody(
            lang('lasting_impressions_settings_set') . ':<br>' .
            lang('enabled_equals') . $this->_settings['enabled'] .  '<br>' . 
            lang('limit_equals') . $this->_settings['limit'] .  '<br>' . 
            lang('expiry_equals') . $this->_settings['expires'] 
            )
        ->now();
      return true;
    }
//    ee('CP/Alert')->makeStandard(lang('lasting_impressions_module_name'))
//        ->asIssue()
//        ->withTitle(lang('invalid_limit'))
//        ->addToBody(lang('invalid_expires'))
//        ->now();
//    return false;
  }

    /**
     * get_post_or_zero
     *
     * @access	public
     * @param 	string 	name of GET/POST var to check
     * @return	int 	returns 0 if the get/post is not present or numeric or above 0
     */
    private function _get_post_or_zero($name)
    {
            $name = ee()->input->get_post($name);
            return ($this->_is_positive_intlike($name) ? $name : 0);
    }
    
    
    private function _is_positive_intlike($num, $threshold = 1)
    {
        //without is_numeric, bools return positive
        //because preg_match auto converts to string
        return (
                is_numeric($num) AND
                preg_match("/^[0-9]+$/", $num) AND
                $num >= $threshold
        );
    }

}

    /* End of file mcp.lasting_impressions.php */
/* Location: ./system/expressionengine/third_party/lasting_impressions/mcp.lasting_impressions.php */