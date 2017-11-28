<?php 
    if ($table): 
    print form_open($partial_url);
            echo "<script>" .PHP_EOL;
            echo "var display_choice = new Array();" .PHP_EOL;
            echo "display_choice[0] = '" . $partial_url."&method=report_all';".PHP_EOL;
            echo "display_choice[1] = '" . $partial_url."&method=groupby_report';" .PHP_EOL;
                               echo "export_data_all = '" . $partial_url."&method=export_data_all';".PHP_EOL;
                               echo "export_grouped= '" . $partial_url."&method=export_grouped';".PHP_EOL;
            echo "</script>".PHP_EOL;
?>
    <div class="report-controls">
            <label type="text"><?php print lang('choose_report') ?></label>

            <select name="report_type" id="report-select">
                    <option value="0"<?php if($report_id == 0) { echo ' selected="selected"';} ?>><?php print lang('view_all_data') ?></option> 
                    <option value="1"<?php if($report_id == 1) { echo ' selected="selected"';} ?>><?php print lang('view_totals') ?></option> 
            </select>

            <input type="button" value="<?php print lang('export_csv_label') ?>" id="export-button" class="submit" name="export-button" title="<?php print lang('export_csv_title') ?>">
            <input type="hidden" value="<?php echo $partial_url."&method=". $method ?>" id="export-type" title="<?php print lang('purge_info') ?>"/>
            <input type="button" value="<?php echo lang('purge_label') ?>" id="purge-button" class="submit purge-button" title="<?php echo lang('purge_title') ?>">
            <input type="hidden" value="<?php echo $partial_url."&method=purge_data_all" ?>" id="purge"/>
            <input type="hidden" value="<?php print lang('purge_message') ?>" id="purge-message"/>
    </div>
       <?php print form_close(); ?>



<div class="pageContents group">
    <?php $this->embed('ee:_shared/table', $table); ?>
	

<?php else: ?>
	<p><strong><?php echo lang('no_data_message') ?></strong></p>
	<p><?php echo lang('required_parameter') ?> <code><?php echo lang('enable_reporting_param') ?></code> to your <code>{exp:lasting_impressions:register}</code> tag</p>
	<p><?php echo lang('housekeeping_tip') ?></p>

<?php endif; ?>
</div>
        

<?php if (isset($pagination)):?>
        <div class="ss_clearfix"><?=$pagination?></div>
<?php endif;?>


<?php include PATH_THIRD . 'lasting_impressions' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'mcp_footer.php'; ?>