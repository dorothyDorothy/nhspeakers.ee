<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

use ClimbingTurn\LastingImpressions\libraries\Config as LiConfig;

/**
 * Lasting Impression Extension class
 *
 * @package     lasting_impressions
 * @author      Dorothy Molloy & Anthony Mellor
 * @link 		http://www.climbingturn.co.uk/software/ee-add-ons/lasting-impressions-pro
 * @copyright 	Copyright (c) 2015 / 2016, Climbing Turn Ltd
 * 
 *  This file is part of lasting_impressions.
 *	Requires ExpressionEngine 3.0.0 or above
 */
class Lasting_impressions_ext {
    var $name;
    var $version;
    var $description    = 'Records channel entries visited';
    var $settings_exist = 'y';
    var $docs_url       = 'http://www.climbingturn.co.uk/software/ee-add-ons/lasting-impressions-pro';
    var $settings       = array();

    /**
     * Constructor
     * @param   mixed   Settings array or empty string if none exist.
     */
    function __construct($settings = '')
    {
        $this->settings = $settings;
        $this->name = LiConfig::getConfig()['name'];
        $this->version = LiConfig::getConfig()['version'];
    }

    /**
     * Activate Extension
     * @return void
     */
    function activate_extension()	{
        $this->settings = array('localize_datetime_format' => FALSE);

        $data = array(
            'class'     => __CLASS__,
            'method'    => 'modify_channel_entries_query_result',
            'hook'      => 'channel_entries_tagdata',
            'settings'  => serialize($this->settings),
            'priority'  => 10,
            'version'   => $this->version,
            'enabled'   => 'y'
        );
        ee()->db->insert('extensions', $data);
    }

    /**
     * Update Extension
     * @return  mixed   void on update / false if none
     */
    function update_extension($current = '')    {
        if ($current == '' OR $current == $this->version)
        {
            return FALSE;
        }

        ee()->db->where('class', __CLASS__);
        ee()->db->update(
                    'extensions',
                    array('version' => $this->version)
        );
    }

    /**
     * Disable Extension
     * @return void
     */
    function disable_extension()
    {
        ee()->db->where('class', __CLASS__);
        ee()->db->delete('extensions');
    }


        /*
         * view_count comes from the cookie and is set as a tag param that you can use within the entries loop
         */
        public function modify_channel_entries_query_result($tagdata, $row, $channel_obj)
        {
                ee()->load->library('lasting_impressions_entries');

                $matches = array();
                $view_count_var = LD.LiConfig::getConfig()['package'].':view_count'.RD;

                if ((preg_match_all("/".LD.LiConfig::getConfig()['package'].":last_view"."\s+format=([\"'])([^\\1]*?)\\1".RD."/s", $tagdata, $matches)) || (strpos($tagdata, $view_count_var) !== FALSE))
                {

                        $entries = ee()->lasting_impressions_entries->get();
                        $view_count = 0;
                        $last_view = '';

                        foreach ($entries as $entry) 
                        {
                                if($row['entry_id'] == $entry['entry_id'])
                                {
                                        $view_count = $entry['view_count'];
                                        $last_view = $entry['last_view'];
                                        break;
                                }
                        }

                        $tagdata = str_replace($view_count_var, $view_count, $tagdata);

                        $localize_datetime =  $this->settings['localize_datetime_format'] === 'y' ? TRUE : FALSE;

                        if(isset($matches[2][0]))
                        {
                                // later versions of EE use localize->format_date
                                if(method_exists(ee()->localize, 'format_date'))    {
                                        $formatted_date = ee()->localize->format_date($matches[2][0], $last_view, $localize_datetime);
                                }   else  {
                                        $formatted_date = ee()->localize->decode_date($matches[2][0], $last_view, $localize_datetime);
                                }
                                $tagdata = str_replace($matches[0][0], $formatted_date, $tagdata);
                        }  else   {
                                $tagdata = str_replace(LD.LiConfig::getConfig()['package'].':last_view'.RD, $last_view, $tagdata);
                        }					
                }

                return $tagdata;

        }


        function settings()
        {
                $settings = array();
                $settings['localize_datetime_format'] = array('r', array('y' => 'Yes', 'n' => 'No'), 'y');
                return $settings;
        }

}