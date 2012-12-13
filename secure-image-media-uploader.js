jQuery(document).ready(function() {
    var file_name = null;

    jQuery( "#tabs" ).tabs();

    jQuery("#wpsiw_searchfile").suggest("./secure-image-search.php",{
        onSelect: function() { file_name = this.value }
    });

    jQuery("#search").click(function(){
        file_name = jQuery("#wpsiw_searchfile").val();
        var postid = jQuery("#postid").val();
        if ( file_name == null) {
            alert ('Type a file name');
            jQuery("#wpsiw_searchfile").focus();
        }
        else {
            jQuery.get("secure-image-search.php", { search: file_name, post_id: postid },
                function(data){
                    jQuery('#file_details').html(data);
            });
        }

    });

    jQuery("#cancel").live("click", function(){ jQuery('#file_details').html(""); });


    jQuery('.sendtoeditor').live("click", function() {
        var file = '[secimage name="'+jQuery(this).attr('title')+'"][/secimage]'
        window.parent.send_to_editor(file);
    });

    jQuery('.setdetails').live("click", function() {
        jQuery( "#tabs" ).tabs( "select", "tabs-2" );
        jQuery("#wpsiw_searchfile").val(jQuery(this).attr('title'));
        jQuery("#search").trigger("click");
    });
});