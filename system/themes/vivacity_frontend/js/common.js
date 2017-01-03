$( document ).ready(function() {
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



});

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
