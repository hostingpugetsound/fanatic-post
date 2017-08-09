
jQuery(document).ready(function($) {

jQuery('#favThis').click(function() {

    var post_id = jQuery(this).data('id');
    console.log('Set Post ID as ' + post_id);
   
    var data = {
		'action': 'favorite',
		'post_id': post_id
	};

	jQuery.post(ajaxurl, data, function(response) {
        console.log('Fetched Favorite Data: ' + response);

        var data = JSON.parse(response);

        if (data.success == false) {
            toastr.error(data.message);    // Show error

        } else {
            if (data.status == false) {    
                jQuery('#favThis').removeClass('active');            
                jQuery('#favThis').empty().append('<i class="fa fa-plus"></i> Add to Favorites');
                toastr.info('Removed from favorites');    
                favStatus = false;
            } else {
                jQuery('#favThis').addClass('active');
                jQuery('#favThis').empty().append('<i class="fa fa-close"></i> Remove from Favorites');
                toastr.success('Added to favorites!');        
                favStatus = true;
            }
        }
    });  

    
});

jQuery(function() {
    console.log( "ready!" );
    var post_id = jQuery('#favThis').data('id');
    console.log('Set Post ID as ' + post_id);
    var data = {
		'action': 'fetch_favorite',
		'post_id': post_id
	};

	jQuery.post(ajaxurl, data, function(response) {
        console.log('Fetched Favorite Data: ' + response);

        var data = JSON.parse(response);

        if (data.success == false) {
            console.log('User not logged in, hiding favorite button.');
            jQuery('#favThis').hide();
        } else {
            if (data.status == false) {    
                jQuery('#favThis').removeClass('active');            
                jQuery('#favThis').empty().append('<i class="fa fa-plus"></i> Add to Favorites');
            } else {
                jQuery('#favThis').addClass('active');
                jQuery('#favThis').empty().append('<i class="fa fa-close"></i> Remove from Favorites');
            }
        }
    });      
    
});


    toastr.options = {
      "closeButton": false,
      "debug": false,
      "newestOnTop": false,
      "progressBar": false,
      "positionClass": "toast-top-center",
      "preventDuplicates": false,
      "onclick": null,
      "showDuration": "300",
      "hideDuration": "1000",
      "timeOut": "5000",
      "extendedTimeOut": "1000",
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut"
    };



});