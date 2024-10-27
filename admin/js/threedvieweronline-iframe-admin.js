(function( $ ) {
	'use strict';
    $( document ).ready(function() {
        $(document).on('click','.upload-btn', function(e){    
            e.preventDefault();
            var input=$(this).attr("data-input");
            var image = wp.media({ 
                title: 'Upload Image',
                // mutiple: true if you want to upload multiple files at once
                multiple: false
            }).open()
            .on('select', function(e){
                // This will return the selected image from the Media Uploader, the result is an object
                var uploaded_image = image.state().get('selection').first();
                // We convert uploaded_image to a JSON object to make accessing it easier
                // Output to the console uploaded_image
                console.log(uploaded_image);
                var image_url = uploaded_image.toJSON().url;
                // Let's assign the url value to the input field
                $('#'+input).val(image_url);
            });
        });
        $(document).on('submit', '#form-wscc-settings', function (event) { 
            
            var err=0;
            var form=$(this);
            form.find('.required').each(function() {
                
                if($(this).is("select"))
				{
					if( $(this).val() == "" ||  $(this).val()==null  ) {
						$(this).closest("tr").addClass('has-error');
						err=1;
					}
					else {
						$(this).closest("tr").removeClass('has-error');
					}
				}else{
					
					if( $(this).val() == "" ) {
						$(this).closest("tr").addClass('has-error');
						err=1;
					}
					else {
						$(this).closest("tr").removeClass('has-error');
					}
				}
            });
            
            if(err==1)
			{
               event.preventDefault();
            }
        });
    });
	

})( jQuery );
