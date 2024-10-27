(function() {
    tinymce.PluginManager.add('threed_mce_button2', function( editor, url ) {
        editor.addButton( 'threed_mce_button2', {
            title: '3dvieweronline plugin info',
            type: "listbox",
            fixedWidth: true,
            text: '3dvieweronline',
            values: [{
            	text:'Your account',
                onclick: function() {
                    window.open('https://www.3dvieweronline.com/wordpress/wp-login.php','_blank');
                } 
            },
            {
            	text:'How to use the plugin',
            	onclick: function() {
					window.open('https://www.3dvieweronline.com/documentation_cat/embed-the-viewer/','_blank');
					}
            },
            {
            	text:'Contact us',
                onclick: function() {
                   {
					window.open('https://www.3dvieweronline.com/','_blank');
					}
                }
            }]
        });
    });
})();