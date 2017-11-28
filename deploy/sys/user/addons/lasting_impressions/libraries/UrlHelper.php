<?php

namespace ClimbingTurn\LastingImpressions\Libraries;
use ClimbingTurn\LastingImpressions\libraries\Config as LiConfig;

class UrlHelper {

    public static function getMcpUrl($action = 'index', $params = array())
    {
        return ee('CP/URL')->make('addons/settings/' . LiConfig::getConfig()['package'] .'/'.$action, $params);
    }
    
    public static function getMcpBaseUrl($params = array()) {
        return ee('CP/URL')->make('addons/settings/' . LiConfig::getConfig()['package'], $params);
    }
}