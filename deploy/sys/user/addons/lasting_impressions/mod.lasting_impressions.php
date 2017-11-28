<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(PATH_MOD.'/channel/mod.channel.php');
use ClimbingTurn\LastingImpressions\libraries\Config as LiConfig;

/**
 * Lasting Impression Module class
 *
 * @package     lasting_impressions
 * @author      Dorothy Molloy & Anthony Mellor
 * @link 		http://www.climbingturn.co.uk/software/ee-add-ons/lasting-impressions-pro
 * @copyright 	Copyright (c) 2016, Climbing Turn Ltd
 * 
 *  This file is part of lasting_impressions.
 *	Requires ExpressionEngine 3.0.0 or above
 */

class Lasting_impressions extends Channel {
  private $config_class;
  private $action_id;
  private $action_id_tag;
  private $jquery_file_name = "jquery-1.10.2.min.js";
  private $_dbSettings;


  public function __construct() 
  {
    parent::__construct();
    ee()->load->library('logger');
    ee()->lang->loadfile('lasting_impressions');
    $settings = ee('Model')->get('lasting_impressions:Settings')->first(); 
    if (isset($settings) ) {
      $this->_dbSettings = $settings;
    } else {
      $this->_dbSettings = ee('Model')->make('lasting_impressions:Settings');
    }
    ee()->load->library('lasting_impressions_entries');
    $this->action_id_tag  = ee()->functions->fetch_action_id(LiConfig::getConfig()['package'], 'remove_item_from_cookie');
    $this->action_id = ee()->db->where(array('class' => __CLASS__, 'method' => 'remove_item_from_cookie'))->get('actions')->row('action_id'); 
    $this->enabled = $this->_dbSettings->enabled;  
  }


	/**
	*	The registration tag - records each entry visited
	*/
	public function register() 
	{
    if ($this->enabled)
    {
      $entry_id = ee()->TMPL->fetch_param('entry_id');
      $make_revisits_most_recent = ee()->TMPL->fetch_param('make_revisits_most_recent', "yes");
      $record_analytics = ee()->TMPL->fetch_param('enable_reporting', "no");

      if (! $this->is_integer($entry_id)) 
      {
        return;
      }
      $entry_added = ee()->lasting_impressions_entries->add($entry_id, $make_revisits_most_recent);
      if (!$entry_added) {
        ee()->logger->developer(LiConfig::getConfig()['package'] . " Failed to set Cookie");
      }
      if ($record_analytics == "yes") {
        $res = ee()->lasting_impressions_entries->record($entry_id);
        if ($res > 0) {
          $err = ee()->db->_error_message();
          ee()->logger->developer(LiConfig::getConfig()['package'] . " Failed to save data.  Error= " . $err);
        }
      }
    }		
  }


	/*
	 * Returns channel entries filtered down to those referenced by the cookie
	 */
	public function entries()
	{
    if ($this->enabled)   {

                            // values = recent (most recent first) or regsiter (order in which they were visited)
      $display_order = ee()->TMPL->fetch_param('display_order', 'recent');
                            // standard EE channel parameters
      $status = ee()->TMPL->fetch_param('status', 'open');
      $channel = ee()->TMPL->fetch_param('channel', '');

      $entries = ee()->lasting_impressions_entries->get();
      if(count($entries) > 0)
      {
        $this->parse_tag_data($entries, $status, $channel, $display_order);     
        return parent::entries();
      }
      return $this->return_no_results();
    }
  }




	// ------------------------------------------------------------------------------



	/**
	* Creates the list of entries wrapped in a form
	*/
	public function form()
	{
    if ($this->enabled)
    {
      $entries = ee()->lasting_impressions_entries->get();
      $status = ee()->TMPL->fetch_param('status', 'open');
      $channel = ee()->TMPL->fetch_param('channel', '');			

      $display_order = ee()->TMPL->fetch_param('display_order', 'recent');
      $use_ajax = ee()->TMPL->fetch_param('use_ajax', FALSE);
      $submit_value = ee()->TMPL->fetch_param('submit_value', 'Remove ...');
      $listing_template = ee()->TMPL->fetch_param('listing_template', FALSE);
      $parent_tag_id = ee()->TMPL->fetch_param('parent_tag_id', FALSE);
      $use_html5 = ee()->TMPL->fetch_param('use_html5') == 'yes' ? TRUE : FALSE;
      
      if(count($entries) > 0)
      {

        $this->parse_tag_data($entries, $status, $channel, $display_order);

        $hidden_fields = array(
          "ACT" 				=> $this->action_id,
          "li_entry_id"		=> '{entry_id}',
          );

        if($use_ajax)
        {
          if(($listing_template == FALSE) || ($parent_tag_id == FALSE))
          {
            die(lang('lasting_impressions_parameter_missing_error'));
          }
          $hidden_fields['li_listings_template'] 	= $listing_template;
          $hidden_fields['li_parent_tag_id']		= $parent_tag_id;
        }

        $form_data = array(
          "action" => ee()->functions->form_backtrack('0'),
          "id" => 'lasting-impressions-form-{entry_id}',
          "hidden_fields" => $hidden_fields
          );

        $this->check_for_submit_buttons($submit_value, $use_html5);
                                        // add the entry_id to the single vars array so that our entry_id hidden field gets parsed
        ee()->TMPL->var_single['entry_id'] = 'entry_id';

        ee()->TMPL->tagdata = ee()->functions->form_declaration($form_data) . PHP_EOL . ee()->TMPL->tagdata . PHP_EOL . "</form>";

        return parent::entries();
      }
      return $this->return_no_results();
    }
  }




  private function check_for_submit_buttons($submit_value, $use_html5)
  {

    $tagdata = ee()->TMPL->tagdata;


    if($use_html5)
    {
     $end_tag = ">";
   }
   else
   {
     $end_tag = " />";
   }

   $variables = NULL;

   if(strpos($tagdata, (LD."submit_button".RD)) !== FALSE)
   {
     $variables[] = array('submit_button' => '<input type="submit" name="submit" class="remove-lasting-impressions" value="'. $submit_value . '"' . $end_tag);
   }
   elseif(strpos($tagdata, (LD."submit_link".RD)) !== FALSE)
   {
     $variables[] = array('submit_link' => '<a href="#" class="remove-lasting-impressions" title="'	. $submit_value . '">' . $submit_value . '</a>');
   }


   if($variables !== NULL)
   {
     ee()->TMPL->tagdata = ee()->TMPL->parse_variables(ee()->TMPL->tagdata, $variables);	
   }

 }



 public function load_js()
 {
  if ($this->enabled)
  {
   $load_jquery = strtolower(ee()->TMPL->fetch_param('load_jquery'));
   $path = URL_THIRD_THEMES . LiConfig::getConfig()['package'] . '/js/';
   $line = '';			
   if (($load_jquery == "true") || ($load_jquery == "yes")) {
    $line .= '<script type="text/javascript" src="' . $path . $this->jquery_file_name .'"></script>' . PHP_EOL;
  }
  $line .= '<script type="text/javascript" src="' . $path . LiConfig::getConfig()['package'] . '.min.js"></script>' . PHP_EOL;
  return $line;
}
}



public function count()    {
  if ($this->enabled)
  {
    return ee()->lasting_impressions_entries->count();
  }
}



	/**
	*	Called from the EE Action event to remove an item
	*
	*/
	public function remove_item_from_cookie()
	{
		if ($this->enabled)
		{
			$entry_id = ee()->input->get_post('li_entry_id');
			ee()->lasting_impressions_entries->delete($entry_id);
		}
		if ( ! AJAX_REQUEST)
		{
      $this->redirect_on_post();
    }		

  }




  private function parse_tag_data($entries, $status, $channel, $display_order)
  {
    $ordered_ids = implode("|", $this->get_entry_ids($entries, $display_order));
    ee()->TMPL->tagparams['entry_id'] = $ordered_ids;
    ee()->TMPL->tagparams['dynamic'] = 'no';
    ee()->TMPL->tagparams['status'] = $status;
    ee()->TMPL->tagparams['channel'] = $channel;
    ee()->TMPL->tagparams['fixed_order'] = $ordered_ids;   
  }


	/**
	*	Entry Ids are put into the array with the most recent last
	*	This function returns an array of entry ids with the most recent first
	*	so that they will be displayed in order of recency
	*/
	private function get_entry_ids($entries, $display_order)
	{
		$entry_ids = array();
		
		foreach($entries as $entry)
		{
			$entry_ids[] = $entry['entry_id'];
		}
		
		if($display_order === 'recent')
		{
			$entry_ids = array_reverse($entry_ids);	
		}
		
		return $entry_ids;

	}



	
	private function redirect_on_post()
	{
		$url = ee()->uri->uri_string();
		$base_url = ee()->functions->fetch_site_index(0, 0);
		ee()->functions->redirect($base_url.$url);
	}
	

	
	private function return_no_results()
	{
		return ee()->TMPL->no_results();
	}



	private function return_error($msg)
	{
		$err_message = ee()->lang->line($msg);
		ee()->TMPL->log_item($err_message);
	}




	private function is_integer($value) 
	{
		$bret = true;
		if (empty($value))
		{
			ee()->TMPL->log_item('Last Impressions: no entry_id given in register tag');
			$bret = false;
		}
		
		if (!is_numeric($value) && strrpos($value, '.') === FALSE)
		{
			ee()->TMPL->log_item('Last Impressions: entry_id is not an integer: register tag');
			$bret = false;
		}
		return $bret;
	}




	private function set_config_class($li_config)
	{
		$this->config_class = $li_config;
	}


}
/* End of file mod.lasting_impressions.php */
/* Location: ./system/expressionengine/third_party/lasting_impressions/mod.lasting_impressions.php */