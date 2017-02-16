$( document ).ready(function() {
   // stLight.options({publisher: "09fb0294-7e9d-41f4-8808-8e0180c2eb62", doNotHash: false, doNotCopy: false, hashAddressBar: false});
    $('.prodetimgblock').find('.proimgthumb').click(function(){
        var imgsrc = $(this).find('img').attr('src');
        $('.prodetimgblock').find('#prodetmainimg').attr('src',imgsrc);
    });


    $("body").css("padding-right", "0");
    $(".modal ").css("padding", "0");

    $('ul.list-inline li.carticon').hover(
        function() {
        $(this).find('.dropdown-menu').stop(true, true).delay(200).fadeIn(500);
    }, function() {
        $(this).find('.dropdown-menu').stop(true, true).delay(200).fadeOut(500);
    });


    $('.dropdown-toggle-shopcart').dropdown();

    $('.singlemenu1').click(function(){

        $('html, body').animate({scrollTop:$('.singleblock1 ').offset().top}, 'slow');

    })

    $('.singlemenu2').click(function(){

        $('html, body').animate({scrollTop:$('.singleblock2 ').offset().top}, 'slow');

    })

    $('.singlemenu3').click(function(){

        $('html, body').animate({scrollTop:$('.singleblock3 ').offset().top}, 'slow');

    })

    $('.singlemenu4').click(function(){

        $('html, body').animate({scrollTop:$('.singleblock4 ').offset().top}, 'slow');

    })


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
        var val=parseFloat($('#subtotalp').attr('unitp'))*newquan;
        $('#subtotalp').text(val.toFixed(2));
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
        //alert($('#subtotalp').attr('unitp'));
        var val=parseFloat($('#subtotalp').attr('unitp'))*newquan;
        $('#subtotalp').text(val.toFixed(2));
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
function facebookshareproduct(e){
    title=$(e).attr('posttitle');
    description=$(e).attr('postdescription');
    image=$(e).attr('postimage');
    id=$(e).attr('postid');
    //alert($('.userid').text());
   // alert(image);
   // alert(description);
   // e.preventDefault();
    FB.ui(
        {
            method: 'feed',
            name: title,
            link: 'http://'+$('.userid').text()+'.vivacitygo.com/product-details/'+id+'/'+title+'ai_bypass=true',
            picture: 'http://www.vivacitygo.com/'+image,
           // caption: 'Top 3 reasons why you should care about your finance',
            description: description,
            message: ""
        });
}
function facebookshareproductinfo(e){
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
            link: 'http://'+$('.userid').text()+'.vivacitygo.com/product-info/'+id+'/'+title+'ai_bypass=true',
            picture: 'http://www.vivacitygo.com/'+image,
           // caption: 'Top 3 reasons why you should care about your finance',
            description: description,
            message: ""
        });
}
function facebookshareproductstatic(e){
    title=$(e).attr('posttitle');
    description=$(e).attr('postdescription');
    image=$(e).attr('postimage');
    //alert(title);
   // alert(image);
   // alert(description);
   // e.preventDefault();
    FB.ui(
        {
            method: 'feed',
            name: title,
            link: 'http://'+$('.userid').text()+'.vivacitygo.com/'+title+'ai_bypass=true',
            picture: 'http://www.vivacitygo.com/'+image,
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
    updateQuan(stock_id);
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
    updateQuan(stock_id);
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
        //window.location.href = '/shopping-cart-1';
        $.post('custom_cart',{},function(res2){
            var cres = JSON.parse(res2);
            $('#topquan').text(cres.totalquan);
            $('#toptotamnt').text(cres.totalamount);
            $('#topcartarea').html(cres.htmlcontent);
            $('html,body').animate({ scrollTop: 0 }, 1000);
            $('#topcartarea').show();
            if($('#cartquan').length){
                $('#cartquan').text(cres.totalquan);
            }
            if($('#cartsubtotal').length){
                $('#cartsubtotal').text('$ '+cres.producttotal);
            }
            if($('#carttotaltotal').length){
                $('#carttotaltotal').text('$ '+cres.totalamnt);
            }
        });
    });
}

function addtocart1(pid,stock_id) {
    var quan = 1;

    $.post('cart?ai_skin=full_page&cmd=add_item',{'cart_cmd':'add','product_id':pid,'stock_item':stock_id,'quantity':quan},function(res){
//        window.location.href = '/shopping-cart-1';
        $.post('custom_cart',{},function(res2){
            var cres = JSON.parse(res2);
            $('#topquan').text(cres.totalquan);
            $('#toptotamnt').text(cres.producttotal);
            $('#topcartarea').html(cres.htmlcontent);
            $('html,body').animate({ scrollTop: 0 }, 1000);
            $('#topcartarea').show();
            if($('#cartquan').length){
                $('#cartquan').text(cres.totalquan);
            }
            if($('#cartsubtotal').length){
                $('#cartsubtotal').text('$ '+cres.producttotal);
            }
            if($('#carttotaltotal').length){
                $('#carttotaltotal').text('$ '+cres.totalamnt);
            }

            if($('#cartproductlistt').length){
                $('#cartproductlistt').html(cres.htmlcontent2);
            }

        });

    })
}

function delItem(stock_id){
    $.post('shopping-cart',{'cmd':'remove','id':stock_id},function(res){
        $.post('custom_cart',{},function(res2){
            var cres = JSON.parse(res2);
            if(cres.totalquan > 0){
                $('#topquan').text(cres.totalquan);
                $('#toptotamnt').text(cres.producttotal);
                $('#topcartarea').html(cres.htmlcontent);
                //$('html,body').animate({ scrollTop: 0 }, 1000);
                //$('#topcartarea').show();
                if($('#cartquan').length){
                    $('#cartquan').text(cres.totalquan);
                }
                if($('#cartsubtotal').length){
                    $('#cartsubtotal').text('$ '+cres.producttotal);
                }
                if($('#carttotaltotal').length){
                    $('#carttotaltotal').text('$ '+cres.totalamnt);
                }

                if($('#cartproductlistt').length){
                    $('#cartproductlistt').html(cres.htmlcontent2);
                }
            }else{
                window.location.href = '/shopping-cart-1';
            }
        });
    })
}

function updateQuan(stock_id) {
    var amount = $('#quanvalue'+stock_id).val();
    $.post('shopping-cart',{'cmd':'update','id':stock_id,'amount':amount},function(res){
        //window.location.reload();
        $.post('custom_cart',{},function(res2){
            var cres = JSON.parse(res2);
            $('#topquan').text(cres.totalquan);
            $('#toptotamnt').text(cres.producttotal);
            $('#topcartarea').html(cres.htmlcontent);
            if($('#cartquan').length){
                $('#cartquan').text(cres.totalquan);
            }
            if($('#cartsubtotal').length){
                $('#cartsubtotal').text('$ '+cres.producttotal);
            }
            if($('#carttotaltotal').length){
                $('#carttotaltotal').text('$ '+cres.totalamnt);
            }

            if($('#cartproductlistt').length){
                $('#cartproductlistt').html(cres.htmlcontent2);
            }
        });
    });
}

function savenews(){
news=$('#news').val();
    if (news==''   || !ValidateEmail($("#news").val())) {
        setTimeout(function(){
            $('#myModalnewsinvalid').modal('show');
        }, 100);
    }
    else {
        url1='addnewsletter/?email='+news;
        $.ajax({url:  url1, success: function(results412){
            //console.log('Facebook Data');
            $('#news').val('');
            if(results412==1){
                setTimeout(function(){
                    $('#myModalnews').modal('show');
                    $('#news').val();
                }, 100);


                setTimeout(function(){
                    $('#myModalnews').modal('hide');
                    //$('#news').val();
                }, 5000);
            }
        }});
    }

}
function ValidateEmail(email) {
    var expr = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
    return expr.test(email);
};