<?php

namespace ClimbingTurn\LastingImpressions\Model;

 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 use EllisLab\ExpressionEngine\Service\Model\Model;

/**
 * Lasting Impressions Settings class
 *
 * @package     lasting_impressions
 * @author      Dorothy Molloy / Anthony Mellor
 * @link 		http://www.climbingturn.co.uk/software/lasting-impressions-pro
 * @copyright 	Copyright (c) 2016, Climbing Turn Ltd
 *
 *  This file is part of lasting_impressions.
 *	Requires ExpressionEngine 3.0.0 or above
 */
class Settings extends Model {
    protected static $_primary_key = 'li_id';
    protected static $_table_name = 'lasting_impressions';
    protected $li_id;
    protected $channel_id;
    protected $site_id;
    protected $limit;
    protected $enabled;
    protected $expires;
    
    
    protected static $_typed_columns = array(
        'li_id' => 'int',
        'channel_id' => 'int',
        'site_id' => 'int',
        'limit' => 'int',
        'enabled' => 'int',
        'expires' => 'int'
    );

    protected static $_validation_rules = array(
            'li_id' => 'required',
            'channel_id' => 'required',
             'site_id' => 'required',        
    );


  
}