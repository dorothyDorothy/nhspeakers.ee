<?php
namespace ClimbingTurn\LastingImpressions\Libraries;

 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
/**
 * Lasting Impression Config class
 *
 * @package     lasting_impressions
 * @author      Dorothy Molloy / Anthony Mellor
 * @link 		http://www.climbingturn.co.uk/software/lasting-impressions-pro
 * @copyright 	Copyright (c) 2016, Climbing Turn Ltd
 *
 *  This file is part of lasting_impressions.
 *	Requires ExpressionEngine 3.0.0 or above
 */
  class Config {

     public static function getConfig() {
         return array (
                'name' => 'Lasting Impressions',
                'package' => 'lasting_impressions',
                'namespace' => 'LastingImpressions',
                'data_table' => 'lasting_impressions_data',
                'version' => '4.0.0',
                'docs' =>  'https://www.climbingturn.co.uk/software/ee-add-ons/lasting-impressions-for-eecms-v3',

                //Versions below LASTING_IMPRESSIONS_HAS_DATATABLE_VERSION need the data table installed
                'has_datatable_version' => '3.0.0'
         );
     }
 }