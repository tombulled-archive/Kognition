(function($)
{
  window.AWWApp_Dialog = {

    // init HTML and events
    init: function()
    {
      this.dialog = $('#awwapp_shortcode_dialog');

      var t = this,
        cancel_button = $('input[name=awwapp_cancel]', t.dialog),
        insert_button = $('input[name=awwapp_insert]', t.dialog);


      QTags.addButton('awwapp_button', AWWApp.text.title, function()
      {
        t.open();
      });

      insert_button.bind('click', function()
      {
        t.insert();
      });

      cancel_button.bind('click', function()
      {
        t.close();
      });
    },

    // check if MCE is active
    isMCE: function()
    {
      return tinyMCEPopup && ( ed = tinyMCEPopup.editor ) && !ed.isHidden();
    },

    // open dialog
    open: function()
    {
      if (!wpActiveEditor) return;

      this.textarea = $('#'+wpActiveEditor).get(0);

      if (!this.dialog.data('wpdialog'))
      {
        this.dialog.wpdialog(
        {
          title: AWWApp.text.title,
          width: 860,
          height: 560,
          modal: true,
          dialogClass: 'wp-dialog',
          zIndex: 300000
        });
      }

      this.dialog.wpdialog('open');
    },

    // close dialog
    close: function()
    {
      this.dialog.wpdialog('close');
    },

    // generate shortcode
    genShortcode: function()
    {
      var code = '[' + AWWApp.shortcode;

      $('input[type=text],select', this.dialog).each(function()
      {
        var $t = $(this),
          v = $t.val();

        if (parseInt(v) == -1 || v == '')
          return;

        code += ' ' + $t.attr('name') + '="' + v + '"';
      });

      code += ']';

      return code;
    },

    // insert shortcode
    insert: function()
    {
      if (this.isMCE())
        this.mceInsert();
      else
        this.htmlInsert();
    },

    // insert shortcode into textarea
    htmlInsert : function()
    {
      var html, start, end, cursor, textarea = this.textarea;
      if (!textarea) return;

      html = this.genShortcode();

      var range = null;
      if (!this.isMCE() && document.selection)
      {
        textarea.focus();
        range = document.selection.createRange();
      }

      // Insert HTML
      // W3C
      if (typeof textarea.selectionStart !== 'undefined')
      {
        start = textarea.selectionStart;
        end = textarea.selectionEnd;
        selection = textarea.value.substring(start, end);
        cursor = start + html.length;

        textarea.value = textarea.value.substring(0, start)
                       + html
                       + textarea.value.substring(end, textarea.value.length);

        // Update cursor position
        textarea.selectionStart = textarea.selectionEnd = cursor;
      }
      else
      if (document.selection && range) // IE
      {
        textarea.focus();
        range.text = html; //+ range.text;
        range.moveToBookmark(range.getBookmark());
        range.select();
        range = null;
      }

      this.close();
      textarea.focus();
    },

    // insert shortcode to MCE
    mceInsert: function()
    {
      var ed = tinyMCEPopup.editor,
        html = this.genShortcode();

      tinyMCEPopup.execCommand("mceBeginUndoLevel");
      ed.selection.setContent(html);
      tinyMCEPopup.execCommand("mceEndUndoLevel");
      this.close();
      ed.focus();
    }
  }

})(jQuery);

jQuery(function($)
{
  AWWApp_Dialog.init();
});