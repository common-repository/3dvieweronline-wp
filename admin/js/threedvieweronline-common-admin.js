(function( $ ) {
	'use strict';
    $( document ).ready(function() {
        if($(".threed_model_iframe").length){
            $('.threed_model_iframe').on('load', function() {
                $(".threed_model_iframe").css("background-image", "none");
            });
        }
        $(document).on('click','#previewBtn',function(){
            var url=$("#threed_model_url").val();
            
            var iwidth=$("#threed_piframe_width").val();
            var iheight=$("#threed_piframe_height").val();
            if(iwidth==''){iwidth="100%";}
            if(iheight==''){iheight="500px";}
            if(url!=''){
                if(iwidth.search('%') == '-1'){
                    if(iwidth.search('px') == '-1'){
                        iwidth +='px';
                    }
                }
                if(iheight.search('%') == '-1'){
                    if(iheight.search('px') == '-1'){
                        iheight +='px';
                    }
                }
               /*  
                $("#threed-iframe-preview").html('<iframe class="threed_model_iframe" width="'+iwidth+'" height="'+iheight+'" src="'+url+'" frameborder="0" allowfullscreen=""></iframe>'); 
                 setTimeout( function(){ 
                   $(".threed_model_iframe").css("background-image", "none");
                }  , 10000 ); */
                
                var ifr=$('<iframe/>', {
                    class:'threed_model_iframe',
                    src:url,
                    load:function(){
                        $(this).show();
                        $(".threed_model_iframe").css("background-image", "none");
                        $(".iframe-loader").remove();
                    }
                }).attr({'width':"100%",'height':iheight}).css('max-width',iwidth);
                $("#threed-iframe-preview").html(ifr);
                setTimeout( function(){ 
                   $(".threed_model_iframe").css("background-image", "none");
                }  , 10000 );
                //$("#threed-iframe-preview").append('<iframe class="threed_model_iframe iframe-loader"></iframe>');
            }else{
                alert('Please enter 3D model URL');
            }
        });
    });
	

})( jQuery );
