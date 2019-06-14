<?php

namespace ClimbingTurn\LastingImpressions\Model;

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

 use EllisLab\ExpressionEngine\Service\Model\Model;

/**
 * Lasting Impressions Data class
 *
 * @package     lasting_impressions
 * @author      Dorothy Molloy / Anthony Mellor
 * @link 		http://www.climbingturn.co.uk/software/lasting-impressions-pro
 * @copyright 	Copyright (c) 2016, Climbing Turn Ltd
 *
 *  This file is part of lasting_impressions.
 *	Requires ExpressionEngine 3.0.0 or above
 */
class Data extends Model {
    protected static $_primary_key = 'lid_id';
    protected static $_table_name = 'lasting_impressions_data';  
    protected $lid_id;
    protected $entry_id;
    protected $member_id;
    protected $session_id;
    protected $ip_address;
    protected $user_agent;
    protected $entry_date;
    
    protected static $_typed_columns = array(
        'lid_id' => 'int',
        '$entry_id' => 'int',
        '$member_id' => 'int',
        '$session_id' => 'int',
        'ip_address' => 'string',
        'user_agent' => 'string',
        'entry_date' => 'timestamp'
    );
    
   protected static $_validation_rules = array(
            'lid_id' => 'required',
            '$entry_id' => 'required',
             'entry_date' => 'required',        
    );
}



