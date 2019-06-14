/**
*	Lasting Impressions ExpressionEngine Add-On
*	AJAX functionality
*
*	@package 		lasting_impressions
*	@author 		Climbing Turn Limited (www.climbingturn.co.uk)
*	@copyright 		Climbing Turn Limited 2014
*	@version  		2.0
* 	@link			http://www.climbingturn.co.uk/software/ee-add-ons/lasting-impressions-pro
*	@dependencies	jQuery 1.8+
*
*/

var lasting_impressions = {

	html_template : '',
	parent_tag : '',
	
	remove_item_from_lasting_impressions : function(obj) 
	{
		var form = $(obj).closest('form');
		var form_id = $(form).attr('id');
		lasting_impressions.html_template = $('#' + form_id + ' input[name="li_listings_template"]').val();
		lasting_impressions.parent_tag = $('#' + form_id + ' input[name="li_parent_tag_id"]').val();
		$.ajax( {
		      type: "POST",
		      url: form.attr( 'action' ),
		      data: form.serialize(),
		      success: function( response ) {
		        lasting_impressions.remove_item_complete(response);
		      }
		    } );

		return false;
	},
	    
    remove_item_complete : function(response) {
		$.get("/" + lasting_impressions.html_template, function(data)
		{
			$('#' + lasting_impressions.parent_tag).html(data);
			lasting_impressions.attach_events();
		});
    },

    attach_events : function() {
    	$('.remove-lasting-impressions').click( function() {return lasting_impressions.remove_item_from_lasting_impressions(this);} );
    }
};

$(document).ready(function () { 
	lasting_impressions.attach_events();
});