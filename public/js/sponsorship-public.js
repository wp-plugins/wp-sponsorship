jQuery.noConflict();



jQuery(document).ready(function($)
{
	//checks if the "select all" box is ckecked
	$('#selectall').change(function () {
		if ($(this).prop('checked')) {
			$('input').prop('checked', true);
		}
		else {
			$('input').prop('checked', false);
		}
		$('#selectall').trigger('change');
	});

	if('sender'== parse_get())
	{
		console.log('sender');
		$(window).scrollTo('#target_scroll', 700);
	}

	if('contact'== parse_get())
	{
		console.log('contact');
		window.location.hash = '#target_scroll';
		return false;
	}

	//function that retrieves where to scroll based on GET variables
	function parse_get()
	{
		if(SESSION.sender == 'yes') return 'sender';
		if(SESSION.contact == 'yes') return 'contact';
	}

	// Change port automaticly if your change the protocol

	jQuery('#encryption_smtp').change(function() {
			switch(jQuery(this).val()) {
				case 'SSL':
					jQuery('#port_number_smtp').val('465');
					break;
				case 'TLS':
					jQuery('#port_number_smtp').val('587');
					break;
				default:
					jQuery('#port_number_smtp').val('25');
			}
		});



});
