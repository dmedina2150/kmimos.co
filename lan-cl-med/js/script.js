//FULL WINDOW
function contain_window(element){
    var height=jQuery(window).height();
    jQuery(element).css({'height':height+'px'});
}

function all_contain_window(element){
    jQuery('.contain_window').each(function(index, element) {
        contain_window(this);
    });
}

jQuery(document).ready(function(e){
    all_contain_window();
    jQuery(window).resize(function(){
        all_contain_window();
    });
});


jQuery(document).on('click','section .down',function(e){
    var section = jQuery(this).closest('section').next();
    if(section.length>0){
        jQuery('html, body').animate({scrollTop : section.offset().top+'px'});
    }
});



//TESTIMONY
var testimony_element='#testimony .testimonials .testimony';
var testimony_select=testimony_element+':nth-child(1)';
jQuery(document).ready(function(){
    testimonials_select(testimony_select);
    jQuery('#testimony').on('click','.navigate .direction',function(e){
        testimonials_navigate(this);
    });
});

function testimonials_select(element){
    //jQuery(testimony_element).removeClass('select');
    //jQuery(element).addClass('select');

    /*//FADE
     jQuery(testimony_element).stop().fadeOut(0,function(e){
     jQuery(element).stop().fadeIn(500);
     });
     */

    //ANIMATE
    jQuery(testimony_element).animate({opacity:0},500,function(e){
        jQuery(testimony_element).css({display:''});
        jQuery(element).animate({opacity:1},200).css({display:'block'});

        var img = jQuery(element).data('img');
        var height = jQuery(element).closest('.content').height()+130;
        jQuery(element).closest('#testimony').find('.image').css({'background-image':'url('+img+')', 'height':'calc(100% - '+height+'px)'});
    });

}

function testimonials_navigate(element){
    var direction=jQuery(element).data('direction');
    if(direction=='sig' && jQuery(testimony_select).next().length>0){
        testimony_select=jQuery(testimony_select).next();

    }else if(direction=='ant' && jQuery(testimony_select).prev().length>0){
        testimony_select=jQuery(testimony_select).prev();

    }else if(direction=='sig' && jQuery(testimony_select).next().length==0){
        testimony_select=jQuery(testimony_element).eq(0);

    }else if(direction=='ant' && jQuery(testimony_select).prev().length==0){
        testimony_select=jQuery(testimony_element).eq((jQuery(testimony_element).length-1));

    }
    testimonials_select(testimony_select);
}
