<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

$lang = array(
    'lasting_impressions_module_name'            => 'Lasting Impressions',
    'lasting_impressions_module_description'    => 'Records and plays back a list of recently viewed entries for each site visitor',
    'no_settings' => 'No Settings set',
    'lasting_impressions_settings_change_success'  => 'Lasting Impressions settings successfully updated',
    'lasting_impressions_install_sucess'        => 'Lasting Impressions installed successfully',
    'lasting_impressions_uninstall_sucess'        => 'Lasting Impressions uninstalled successfully',
    'lasting_impressions_settings_set'       => 'Lasting Impressions settings have been set to ',
    'enabled_equals'    => 'Enabled = ',
    'limit_equals'  => 'Max number of entries = ',
    'expiry_equals' => 'Number of days before expiry = ',

    // --- Errors -----
    'invalid_limit' => 'Invalid limit',
    'invalid_expires' => 'Invalid cookie expiry value',
    
    'lasting_impressions_channel_missing_error'         => 'Lasting Impressions: requires a channel',
    'lasting_impressions_parameter_missing_error'     => 'Lasting Impressions: cannot use AJAX without providing parent_tag_id and listing_template parameters.',
    
    // MCP text
    
    'choose_report'                     => 'Report type:',
    'view_all_data'                     => 'All data',
    'view_totals'                       => 'Entry Totals',
    'table_header_limit'                => 'Maximum number of entries to record',
    'table_header_cookie_expiration'    => 'No of days before cookie expires',
    'table_header_li_enabled'           => 'Enabled',
    'breadcrumb_reports'                => 'Reports',
    'breadcrumb_settings'               => 'Settings',
    'breadcrumb_purged_data'        => 'Purged',
    'disable'                           => 'Disable',
    'enable'                            => 'Enable',
    'enable_to_edit'    => 'Select Enable to enter settings',
    'number_of_views'                   => 'Number viewed',
    'entry_id'                          => 'Entry ID',
    'channel_id'                        => 'Channel ID',
    'title'                             => 'Title',
    'member_id'                         => 'Member ID',
    'session_id'                        => 'Session ID',
    'ip_address'                        => 'IP Address',
    'user_agent'                        => 'User Agent',
    'entry_date'                        => 'Entry Date',

    'pagination_title'                  => 'Page',
    
    'please_select'                     => 'Please select',
    'no_data'               => 'No stats collected',

    'export_csv_label'                  => 'Export as CSV',
    'export_csv_title'                  => 'Export this report as CSV',

    'purge_label'                       => 'Purge all Data',
    'purge_title'                       => 'Purge Data: This will clear all recorded lasting impression data from the database',
    'purge_message'                     => 'Are you sure that you want to delete your Lasting impressions history?',

    'no_data_message'                   => "There isn't any report data yet.",
    'required_parameter'                => 'Lasting Impressions will only record data for reporting if you add the parameter',
    'enable_reporting_param'            => 'enable_reporting="yes"',
    
    'housekeeping_tip'                  => '<strong>Good Housekeeping Tip:</strong> If you enable the recording of data then remember to check here regularly to ensure your data set isn&apos;t getting too large. If it is you should download it and clear down the data.',

    'data_purged_title'                 => 'Lasting Impressions Data Purged',

    'data_purged'                       => 'Your Lasting Impressions data has been removed!',

    'continue_label'                    => 'Continue',

    'find_docs_at'                      => 'Lasting Impressions documentation can be found at',
    
    // Extension Settings
    'localize_datetime_format'          => 'Localize the date / time format',
);
