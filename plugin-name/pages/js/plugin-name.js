(function() {
	tinymce.create('tinymce.plugins.PLUGINNAMEShortcode', {
		init: function(editor, url) {
			editor.addCommand('addPLUGINNAMEShortcode', function() {
				editor.insertContent('[pluginname arg1="val1" arg2="val2"]');
			});
			editor.addButton('pluginname', {
				title: 'Plugin Name',
				image: url + '/plugin-name.png',
				cmd: 'addPLUGINNAMEShortcode'
			});
		}
	});

	tinymce.PluginManager.add('pluginname', tinymce.plugins.PLUGINNAMEShortcode);
})();
