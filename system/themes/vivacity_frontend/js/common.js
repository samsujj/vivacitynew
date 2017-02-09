$( document ).ready(function() {
   // stLight.options({publisher: "09fb0294-7e9d-41f4-8808-8e0180c2eb62", doNotHash: false, doNotCopy: false, hashAddressBar: false});
    $('.prodetimgblock').find('.proimgthumb').click(function(){
        var imgsrc = $(this).find('img').attr('src');
        $('.prodetimgblock').find('#prodetmainimg').attr('src',imgsrc);
    });

    $('.productquanbloc').find('.quandec').click(function(){
        var quan = $('.productquanbloc').find('#quanvalue').val();
        var newquan;

        if(isNaN(quan)){
            newquan = 1;
        }else{
            if(quan >= 2){
                newquan = parseInt(quan)-1;
            }else{
                newquan = 1;
            }
        }
        $('.productquanbloc').find('#quanvalue').val(newquan);
    });

    $('.productquanbloc').find('.quaninc').click(function(){
        var quan = $('.productquanbloc').find('#quanvalue').val();
        var newquan;

        if(isNaN(quan)){
            newquan = 1;
        }else{
            if(quan >= 1){
                newquan = parseInt(quan)+1;
            }else{
                newquan = 1;
            }
        }
        $('.productquanbloc').find('#quanvalue').val(newquan);

    });



    if($('#checkout_billing_shipping_same').is(':checked')){
        $('.chk_ship_fld').hide();
    }

    $('#checkout_billing_shipping_same').change(function () {
        if($(this).is(':checked')){
            $('.chk_ship_fld').hide();
        }else{
            $('.chk_ship_fld').show();
        }
    })
$('.life').click(function(){
   // alert(23321);
 $('.life').addClass('open');
  //  $('.life').child('a').attr('aria-expanded',true);
})
    $('.journey').click(function(){
        // alert(23321);
        $('.journey').addClass('open');
      //  $('.journey').child('a').attr('aria-expanded',true);
    })
    $('.shop').click(function(){
        // alert(23321);
        $('.shop').addClass('open');
     //   $('.shop').child('a').attr('aria-expanded',true);
    })


    $('#share_button').click(function(e){
       // alert(a);
        e.preventDefault();
        FB.ui(
            {
                method: 'feed',
                name: 'This is the content of the "name" field.',
                link: 'http://www.groupstudy.in/articlePost.php?id=A_111213073144',
                picture: 'http://www.groupstudy.in/img/logo3.jpeg',
                caption: 'Top 3 reasons why you should care about your finance',
                description: "What happens when you don't take care of your finances? Just look at our country -- you spend irresponsibly, get in debt up to your eyeballs, and stress about how you're going to make ends meet. The difference is that you don't have a glut of taxpayers…",
                message: ""
            });
    });


});

function facebookshare(e){
    title=$(e).attr('posttitle');
    description=$(e).attr('postdescription');
    image=$(e).attr('postimage');
    id=$(e).attr('postid');
    //alert(title);
   // alert(image);
   // alert(description);
   // e.preventDefault();
    FB.ui(
        {
            method: 'feed',
            name: title,
            link: 'http://www.vivacitygo.com/blogdetails/'+id+'/'+title,
            picture: 'http://www.vivacitygo.com/uploads/blogmanager/'+image,
           // caption: 'Top 3 reasons why you should care about your finance',
            description: description,
            message: ""
        });
}
function quanDec(stock_id) {
    var quan = $('#quanvalue'+stock_id).val();
    var newquan;

    if(isNaN(quan)){
        newquan = 1;
    }else{
        if(quan >= 2){
            newquan = parseInt(quan)-1;
        }else{
            newquan = 1;
        }
    }
    $('#quanvalue'+stock_id).val(newquan);
}

function quanInc(stock_id) {
    var quan = $('#quanvalue'+stock_id).val();
    var newquan;

    if(isNaN(quan)){
        newquan = 1;
    }else{
        if(quan >= 1){
            newquan = parseInt(quan)+1;
        }else{
            newquan = 1;
        }
    }
    $('#quanvalue'+stock_id).val(newquan);
}

function addtocart(pid,stock_id) {
    var quan = $('.productquanbloc').find('#quanvalue').val();
    if(isNaN(quan)){
        quan = 1;
    }else{
        if(quan < 1){
            quan = 1;
        }
    }

    $.post('cart?ai_skin=full_page&cmd=add_item',{'cart_cmd':'add','product_id':pid,'stock_item':stock_id,'quantity':quan},function(res){
        window.location.href = '/shopping-cart-1';
    })
}

function addtocart1(pid,stock_id) {
    var quan = 1;

    $.post('cart?ai_skin=full_page&cmd=add_item',{'cart_cmd':'add','product_id':pid,'stock_item':stock_id,'quantity':quan},function(res){
        window.location.href = '/shopping-cart-1';
    })
}

function delItem(stock_id){
    $.post('shopping-cart',{'cmd':'remove','id':stock_id},function(res){
        window.location.reload();
    })
}

function updateQuan(stock_id) {
    var amount = $('#quanvalue'+stock_id).val();
    $.post('shopping-cart',{'cmd':'update','id':stock_id,'amount':amount},function(res){
        window.location.reload();
    });
}
