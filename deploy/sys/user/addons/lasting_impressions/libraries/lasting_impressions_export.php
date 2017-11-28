<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/**
 * Lasting Impression Module class
 *
 * @package     lasting_impressions
 * @author      Dorothy Molloy / Anthony Mellor
 * @link 		http://www.climbingturn.co.uk/software/lasting-impressions-pro
 * @copyright 	Copyright (c) 2015 / 2016, Climbing Turn Ltd
 *
 *  This file is part of lasting_impressions.
 *	Requires ExpressionEngine 3.0.0 or above
 */

class Lasting_impressions_export {

    public function export($report_name, $data, $group_by){

        $filename = ee()->security->sanitize_filename('lasting_impressions_' . $report_name . '_export_' . date('Ymd_Hi_s', ee()->localize->now) . '.csv');
            
        ee()->load->helper("download");

        $content = '';
	         
        if ($group_by ) {
        	$content =  lang('number_of_views'). ',';
        }

	    $content = $content . $this->_create_common_headings();

        foreach ($data as $key => $value) {
            foreach ($value as $key2 => $value2) {
                if($key2 == 'entry_date') {
                    $value_formatted = date('Y-m-d H:i:s', $value2);
                } else {
                    $value_formatted = $value2;   
                }
                $content = $content . '"' . str_replace('"', '""', $value_formatted) . '",' ;
            }
            $content = $content .   PHP_EOL;
        }    
        
        force_download($filename, $content);

    }

    private function _create_common_headings(){
    	$content = lang('entry_id'). ', ' .
	    lang('title'). ', ' .
	    lang('site_id'). ', ' .
	    lang('channel_id'). ', ' .
	    lang('member_id'). ', ' .
	    lang('session_id'). ', ' .
	    lang('ip_address'). ', ' .
	    lang('user_agent'). ', ' .
	    lang('entry_date'). ', ' .PHP_EOL;	
	    return $content;
    }

}
