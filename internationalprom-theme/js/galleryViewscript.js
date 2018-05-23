jQuery(document).ready(function() {
    jQuery('.view_checkbox').on('click',function(){
        var PostId = jQuery(this).attr("data-PostId");
        var view = jQuery('input[name="view"].View_'+PostId).val();
        jQuery(this).hide();
        jQuery('.ajax_loader.ajax_loader_'+PostId).show();
        if(view === undefined) {
            view = 0;
        }
        jQuery.ajax({
            type : "post",
            dataType : "json",
            url : myAjax.ajaxurl,
            data : {action: "galleryView", PostId : PostId, view: view},
            success: function(response) {
                //alert(response);
                location.reload();
            }
        });
    });
});
