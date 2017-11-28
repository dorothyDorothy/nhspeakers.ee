/*
	Lasting Impressions CP Functions
	By Anthony mellor & Dorothy Molloy
	Climbing Turn Limited
	www.climbingturn.co.uk
	Dependencies: jquery 1.8.x
*/

var lasting_impressions_cp = {
	
	errors : '',
	report_all : 'report_all',
	per_page : 7,

	validate : function() 
	{
		this.errors = '';
		if($('#li-enabled').is(':checked'))
		{
			var num_val = $('#li-limit').val();

			if( isNaN(num_val) ) {
				this.errors += ' * Maximum entries must be a number\r\n';
			}
			else if(num_val % 1 !== 0) {
				this.errors += ' * Maximum entries must be a whole number\r\n';	
			}
			else if( num_val == 0) {
				this.errors += ' * Maximum entries must be greater than 0\r\n';
			}

			num_val = $('#li-expires').val();

			if( isNaN(num_val) ) {
				this.errors += ' * Days before cookie expires must be a number\r\n';
			}
			else if(num_val % 1 !== 0) {
				this.errors += ' * Days before cookie expires must be a whole number\r\n';
			}
			else if( num_val == 0) {
				this.errors += ' * Days before cookie expires must be greater than 0\r\n';
			}

			if(this.errors != '') {
				return this.display_error();
			}
			else {
				return true;
			}
		}
		else {
			return true;
		}
	},

	set_enabled_state : function()
	{
		if($('#li-enabled').is(':checked'))	{
			$('#li-limit').removeAttr('disabled');
			$('#li-expires').removeAttr('disabled');
		}
		else {
			$('#li-limit').attr('disabled', 'disabled');
			$('#li-expires').attr('disabled', 'disabled');	
		}
	},

	display_error : function() {
		window.alert('Cannot submit your settings:\r\n\r\n' + this.errors + '\r\n');
		return false;
	},

	select_report: function(){
		var choice_index = $('#report-select option:selected').val();
		var url = display_choice[choice_index]; //See mcp_report where this is defined
		window.location = url;
	},
        
	style_report : function() {
		var report_title = $('#report-title').text();
		var url = window.location;
		if (url.href.indexOf(this.report_all) > -1) {
			$('.group_by').hide();
			$('#report-select option:eq(0)').attr('selected', 'selected');
		} else if ($('#report-title').text().indexOf('Group By') > -1) {
                                                    $('#export-type').val(export_grouped);
			$('.group_by').show();
			$('#report-select option:eq(1)').attr('selected', 'selected');
		} 
	},
            
  export_data: function() {
      var url = $('#export-type').val();
      window.location = url;
  },

  purge_data: function() {

	var url = $('#purge').val();
	var msg = $('#purge-message').val();
	if (window.confirm(msg)) {
		window.location = url;
    }
  }  

};


$(document).ready( function() { 
    $('#li_submit').click(function() { return lasting_impressions_cp.validate(); })
    $('input[name="li-enabled"]').change( function() { lasting_impressions_cp.set_enabled_state(); });
    $('#report-select').change(function() {return lasting_impressions_cp.select_report();})
    $('#total-rows').change(function() {return lasting_impressions_cp.select_number_of_rows();})
    lasting_impressions_cp.set_enabled_state();
    lasting_impressions_cp.style_report();
    $('#export-button').click(function() {return lasting_impressions_cp.export_data();});
    $('#purge-button').click(function() {return lasting_impressions_cp.purge_data();});
});