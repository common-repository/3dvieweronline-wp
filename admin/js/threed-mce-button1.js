(function() {
    tinymce.PluginManager.add('threed_mce_button1', function( editor, url ) {
        editor.addButton( 'threed_mce_button1', {
            title: 'Embed 3dViewerOnline Shortcode',
            icon: 'icon threed-own-icon',
            onclick: function() {
                editor.insertContent('[3Dvo-model url=model_url id=3Dvo width=640 height=480 autosize=yes]');
            }
        });
    });
})();