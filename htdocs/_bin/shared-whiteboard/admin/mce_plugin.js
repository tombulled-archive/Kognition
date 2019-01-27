(function()
{
  tinymce.create('tinymce.plugins.awwapp_mce_plugin',
  {
    init: function(ed, url)
    {
      ed.addCommand('awwapp_show_dialog', function()
      {
        AWWApp_Dialog.open();
      });

      ed.addButton('awwapp_button',
      {
        title: AWWApp.text.title,
        image: url + '/images/icon-20.png',
        cmd: 'awwapp_show_dialog'
      });
    },
    createControl: function(n, cm)
    {
      return null;
    },
    getInfo: function()
    {
      return {
        longname: AWWApp.text.title,
        author: '',
        authorurl: '',
        infourl: '',
        version: AWWApp.version
      };
    }
  });

  tinymce.PluginManager.add('awwapp_mce_plugin', tinymce.plugins.awwapp_mce_plugin);
})();