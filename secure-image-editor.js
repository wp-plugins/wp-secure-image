(function() {
   tinymce.create('tinymce.plugins.secimage', {
      init : function(ed, url) {
         ed.addButton('secimage', {
            title : 'Secure Image',
            image : url+'/images/secure-image-button.png',
            onclick : function() {
                var name = prompt("Name of the class file", "");
                if (name != null && name != '')
                    ed.execCommand('mceInsertContent', false, '[secimage name="'+name+'"][/secimage]');
            }
         });
      },
      createControl : function(n, cm) {
         return null;
      },
      getInfo : function() {
         return {
            longname : "Secure Image",
            author : 'ArtistScope',
            authorurl : 'http://www.artistscope.com/',
            infourl : 'http://www.artistscope.com/secure_image_protection_wordpress_plugin.asp',
            version : "0.2"
         };
      }
   });
   tinymce.PluginManager.add('secimage', tinymce.plugins.secimage);
})();