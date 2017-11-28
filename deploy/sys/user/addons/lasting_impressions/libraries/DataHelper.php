<?php
namespace ClimbingTurn\LastingImpressions\Libraries;

use EllisLab\ExpressionEngine\Library\CP\Table;
use ClimbingTurn\LastingImpressions\libraries\Config as LiConfig;

/**
 * Lasting Impressions DataHelper class
 *
 * @package     lasting_impressions
 * @author      Dorothy Molloy / Anthony Mellor
 * @link    http://www.climbingturn.co.uk/software/lasting-impressions-pro
 * @copyright   Copyright (c) 2016, Climbing Turn Ltd
 *
 *  This file is part of lasting_impressions.
 *  Requires ExpressionEngine 3.0.0 or above
 */
class DataHelper {
    
    
public function get_all_recorded_data($group_by) {
          $query = $this->_get_lasting_impressions_data($group_by);
          if ($query->num_rows() > 0){
              $data= $query->result_array();
              return $data;
          }
          return array();
  }
  
  private function _get_lasting_impressions_data($group_by){
      if ($group_by) {
          ee()->db->select("count(*) as num_views, d.entry_id, t.title, t.site_id, t.channel_id")
              ->from(LiConfig::getConfig()['data_table']  . ' d')
              ->join('channel_titles t', 'd.entry_id = t.entry_id', 'inner') 
              ->group_by('d.entry_id')
              ->order_by('num_views', 'DESC');
      } else {
      ee()->db->select("d.entry_id, t.title,  t.site_id, t.channel_id, d.member_id, d.session_id, d.ip_address, d.user_agent, d.entry_date")
              ->from(LiConfig::getConfig()['data_table']  . ' d')
              ->join('channel_titles t', 'd.entry_id = t.entry_id', 'inner')
              ->order_by('d.entry_id');                   
      }
      $res = ee()->db->get();
      return $res;
  }
  
  
  
  public function purge_all_recorded_data() {
        ee()->db->empty_table(LiConfig::getConfig()['data_table'] );
  }
  
  //------------------------ TABLES ----------------------------- //
  
  public function create_simple_table($limit=25, $current_page = 1){
        $table = ee('CP/Table', array( 'sortable' => 'FALSE', 'autosort' => 'TRUE',  'limit' => $limit, 'page' => $current_page));
        $table->setColumns(
                array(
                   'entry_id' => array(
                           'sort' => 'false',
                           'type' => 'Table::COL_TEXT'
                   ),
                   'title' => array(
                           'sort' => 'false',
                           'type' => 'Table::COL_TEXT'
                   ),
                   'site_id' => array(
                           'sort' => 'false',
                           'type' => 'Table::COL_TEXT'
                   ),
                   'channel_id' => array(
                           'sort' => 'false',
                           'type' => 'Table::COL_TEXT'
                   ),
                   'member_id' => array(
                           'sort' => 'false',
                           'type' => 'Table::COL_TEXT'
                   ),
                   'session_id' => array(
                           'sort' => 'false',
                           'type' => 'Table::COL_TEXT'
                   ),
                   'ip_address' => array(
                           'sort' => 'false',
                           'type' => 'Table::COL_TEXT'
                   ),
                   'user_agent' => array(
                           'sort' => 'false',
                           'type' => 'Table::COL_TEXT'
                   ),
                   'entry_date' => array(
                           'sort' => 'false',
                           'type' => 'Table::COL_TEXT'
                   )
                 )
            );
        $table->setNoResultsText('no_data');
        $stats = $this->get_all_recorded_data(false);
        $stats = $this->_format_date_field($stats);
        $table->setData($stats);
        return $table;
        
  }
  
public function create_totals_table( $limit=25, $current_page) {
    $table = ee('CP/Table', array( 'sortable' => 'TRUE', 'autosort' => 'FALSE',  'limit' => $limit, 'page' => $current_page));
    $table->setColumns(
        array(
               'num_views' => array(
                           'label' => 'Num Views',
                           'sort' => 'false',
                           'type' => 'Table::COL_TEXT'
                   ),
                'entry_id' => array(
                           'sort' => 'false',
                           'type' => 'Table::COL_TEXT'
                   ),
                'title' => array(
                           'sort' => 'true',
                           'type' => 'Table::COL_TEXT'
                   ),
                'site_id' => array(
                           'sort' => 'false',
                           'type' => 'Table::COL_TEXT'
                   ),
                'channel_id' => array(
                           'sort' => 'false',
                           'type' => 'Table::COL_TEXT'
                   )
            )
        );
    $table->setNoResultsText('no_data');
    $stats = $this->get_all_recorded_data(true);
    $table->setData($stats);
    return $table;
    }
    
    private function _format_date_field($stats_array){
        foreach($stats_array as &$item) {
            $formatted_date =  date('d-m-Y', $item['entry_date']);
            $item['entry_date'] = $formatted_date;
        }
        return $stats_array;
    }
}
