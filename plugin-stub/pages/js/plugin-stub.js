(function() {
	tinymce.create('tinymce.plugins.PluginStubShortcode', {
		init: function(editor, url) {
			editor.addCommand('addPluginStubShortcode', function() {
				editor.insertContent('[pluginstub arg1="val1" arg2="val2"]');
			});
			editor.addButton('pluginstub', {
				title: 'Plugin Stub',
				image: url + '/plugin-stub.png',
				cmd: 'addPluginStubShortcode'
			});
		}
	});

	tinymce.PluginManager.add('pluginstub', tinymce.plugins.PluginStubShortcode);
})();
