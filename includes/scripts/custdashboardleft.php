<?php

global $AI;
$userid=$AI->user->userID;

$cartquery=db_query("SELECT orders . * , users.first_name, users.last_name , order_status.name as statustxt
FROM  orders 
LEFT JOIN users ON users.userID = orders.userID
LEFT JOIN order_status ON order_status.status_id = orders.status
WHERE orders.userID =".$userid."
OR orders.userID
IN (SELECT userID FROM  users WHERE  parent =".$userid.")");


?>


<style>
    .custdashboardleft{
        background: #fff!important;
        border: solid 1px #d9d9d9;
        padding: 25px;
        margin-bottom: 15px;
    }


    .custdashboardleft h2 {
        margin: 0;
        padding: 11px 12px 9px 12px;
        font-family: 'RalewayRegular';
        font-weight: bold;
        background: #688816;
        font-size: 16px;
        color: #fff;
        text-transform: uppercase;
    }

    .custdashboardleft p{
        background-color: #dfdfdf;
        font-family: 'RalewayRegular';
        padding: 8px;
        font-size: 13px;
        color: #5a5a5a;
        line-height: 24px;
    }

    .custdashboardleft table {
        width: 100%;
    }

    .custdashboardleft table th {
        text-align: left;
        font-family: 'RalewayRegular';
        font-size: 14px;
        color: #353535;
        font-weight: normal;
        padding: 12px 40px!important;
    }

    .custdashboardleft table td {
        text-align: left;
        font-family: 'RalewayRegular';
        font-size: 14px;
        color: #353535;
        background: #dfdfdf;
        border-bottom: solid 1px #fff;
        padding: 12px 40px!important;
    }

</style>


<div class="container-fluid custdashboardleft">
    <h2>My Dashboard</h2>
    <!--<p>COMMISSION QUALIFIED for the upcoming 4 weeks. That 45 CV can be acquired from CUSTOMERS or by you making a purchase. Important Note: "Credits" from your Wallet can not be used to order this auto-ship. Credits do not generate CV, however "US Dollars" from your wallet may be used. Let me explain. If you purchased a BUSINESS PACK and have 499 credits in your wallet - these can ONLY be spent on PRODUCTS - they can NOT be spent to purchase AUTO SHIP PACKAGE which is either 45, 90 or 135 CREDITS. Does that make sense</p>-->
    <p>
        Thank you for placing an order with Vivacity. <br/>

        You can view your order status below.

    </p>
</div>

<div class="container-fluid custdashboardleft">
    <h2>Recent Orders</h2>

    <?php

    if(db_num_rows($cartquery)>0) {


        ?>

        <table border="0">
            <tbody>
            <tr>
                <th>Order#</th>
                <th>Date</th>
                <th>Order Total</th>
                <th>Status</th>
            </tr>

        <?php
        while($rowcart=db_fetch_assoc($cartquery)){

            $billing_addr = unserialize($rowcart['billing_addr']);


        ?>
            <tr>
                <td><?php echo $billing_addr['first_name']." ".$billing_addr['last_name']; ?></td>
                <td><?php echo date('m/d/Y',strtotime($rowcart['date_added']))?></td>
                <td>$<?php echo $rowcart['total']; ?></td>
                <td><?php echo $rowcart['statustxt']; ?></td>
            </tr>

    <?php } ?>
            </tbody>
        </table>

        <?php

    }


    ?>

    <a href="orders" class="db_viewalllink"> View All Orders</a>

    <div class="clearfix"></div>
</div>

