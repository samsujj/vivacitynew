<?php

global $AI;
require_once(ai_cascadepath('includes/plugins/landing_pages/class.landing_pages.php'));

$products = $AI->db->GetAll("SELECT `p`.`product_id`,`p`.`title`,`ps`.`stock_item_id`,`ps`.`price` FROM `products` `p`  INNER JOIN `product_stock_items` `ps` ON `p`.`product_id`=`ps`.`product_id` WHERE `p`.`product_id` = 1 GROUP BY `ps`.`product_id` ORDER BY `p`.`title`");

$product = $products[0];


$landing_page = new C_landing_pages('Membership');
$landing_page->pp_create_campaign = true;
$landing_page->css_error_class = 'lp_error';
$landing_page->add_validator('bill_first_name', 'is_length', 3,'Invalid Billing First Name');
$landing_page->add_validator('bill_last_name', 'is_length', 3,'Invalid Billing Last Name');
$landing_page->add_validator('bill_address_line_1', 'is_length', 3,'Invalid Billing Address');
$landing_page->add_validator('bill_city', 'is_length', 3,'Invalid Billing City');
$landing_page->add_validator('bill_region', 'is_length', 2,'Invalid Billing State');
$landing_page->add_validator('bill_country', 'is_length', 2,'Invalid Billing Country');
$landing_page->add_validator('bill_postal_code', 'is_length', 5,'Invalid Billing Postal Code');
$landing_page->add_validator('bill_email', 'util_is_email','','Invalid Billing Email Address');
$landing_page->add_validator('bill_phone', 'is_phone','','Invalid Billing Phone Number');

if(!isset($_POST['bill_same_as_ship'])){
    $landing_page->add_validator('ship_first_name', 'is_length', 3,'Invalid Shipping First Name');
    $landing_page->add_validator('ship_last_name', 'is_length', 3,'Invalid Shipping Last Name');
    $landing_page->add_validator('ship_address_line_1', 'is_length', 3,'Invalid Shipping Address');
    $landing_page->add_validator('ship_city', 'is_length',3,'Invalid Shipping City');
    $landing_page->add_validator('ship_region', 'is_length', 2,'Invalid Shipping State');
    $landing_page->add_validator('ship_country', 'is_length', 2,'Invalid Shipping Country');
    $landing_page->add_validator('ship_postal_code', 'is_length', 5,'Invalid Shipping Postal Code');
    $landing_page->add_validator('ship_email', 'util_is_email','','Invalid Shipping Email Address');
    $landing_page->add_validator('ship_phone', 'is_phone','','Invalid Shipping Phone Number');
}

$landing_page->add_validator('card_name', 'is_length', 3,'Invalid Name on Card');
$landing_page->add_validator('card_number', 'is_length', 14,'Invalid Card Number');
$landing_page->add_validator('card_type', 'is_length', 2,'Invalid Card Type');
$landing_page->add_validator('card_exp_mo', 'card_expire_check', '','Invalid Card Expiration');
$landing_page->add_validator('card_cvv', 'is_length', 3,'Invalid Card Security Code (CVV)');

$landing_page->add_validator('check_terms','is_checked','','You must accept the Terms & Conditions');

$is_chk = 0;

if(isset($landing_page->session['form_data']['bill_same_as_ship'])){
    $is_chk = 1;
}

if(util_is_POST()) {
    $landing_page->set_shipping_amt('0');
    $landing_page->validate();


    $userID = $AI->user->userID;
    $emailid = $AI->user->email;
    $landing_page->load_userID($userID);
    $landing_page->save_order();
    if ($landing_page->has_errors()) {
        $landing_page->display_errors();
    } else {
        $created_order = $landing_page->session['created_order'];

        $landing_page->clear_session();
        unset($landing_page->session['lead_id']);
        unset($landing_page->session['created_user']);
        unset($landing_page->session['created_order']);

       // after_saveorder($created_order);

        $email_name = 'Accept terms';
        $send_to = $emailid;
        $send_from = 'iftekarkta@gmail.com';

        $vars = array();
        //$vars['name'] = 'Samsuj Jaman';
        $vars['name'] = $AI->user->username;

        $defaults = array();
        $defaults['email_subject'] = ' $se->send email';
        $defaults['email_msg'] = getmailbody($AI->user->username);

        $se = new C_system_emails($email_name);
        $se->set_from($send_from);
        $se->set_defaults_array($defaults);
        $se->set_vars_array($vars);
        $se->send($send_to);

        util_redirect('dashboard');
    }

}

$landing_page->refill_form();


?>

<script>
    $(function(){

        var is_chk = '<?php echo $is_chk; ?>';

        bill_ship_same(is_chk);


        $('#checkout_billing_shipping_same').change(function(){
            if($(this).is(':checked')){
                is_chk = 1;
            }else{
                is_chk = 0;
            }
            bill_ship_same(is_chk);
        });



    });


    function bill_ship_same(is_chk) {

        if(is_chk == 1){
            $('.shipbillcls').hide();
        }else {

            $('#ship_first_name').val($('#bill_first_name').val());
            $('#ship_last_name').val($('#bill_last_name').val());
            $('#ship_address_line_1').val($('#bill_address_line_1').val());
            $('#ship_address_line_2').val($('#bill_address_line_2').val());
            $('#ship_city').val($('#bill_city').val());
            $('#ship_region').val($('#bill_region').val());
            $('#ship_email').val($('#bill_email').val());
            $('#ship_phone').val($('#bill_phone').val());
            $('#ship_postal_code').val($('#bill_postal_code').val());


            $('.shipbillcls').show();
        }
    }

</script>


<div class="checkoutblockwrapper checkoutblockwrappernew">
    <img src="system/themes/dashboardnewtheme/images/logo-vivacity.png" alt="logo" class="membershiplogo">

    <div class="container-fluid checkoutblock1">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 checkoutfromblock">
            <form name="landing_page" id="landing_page_chk" action="<?=$_SERVER['REQUEST_URI']?>" method="post" class="form-inline">
                <input type="hidden" name="bill_country" id="bill_country" value="US">
                <input type="hidden" name="ship_country" id="ship_country" value="US">
                <input name="pid" type="hidden" value="<?php echo $product['stock_item_id']; ?>" id="pid">

                <div class="hrlinenew"></div>
                <div class="clearfix"></div>

                <h2>BILLING INFORMATION</h2>

                <div class="form-group">
                    <label for="bill_first_name">First Name<span>*</span></label>
                    <input type="text"  class="form-control"  name="bill_first_name" id="bill_first_name" />
                </div>

                <div class="form-group">
                    <label for="bill_last_name">Last Name<span>*</span></label>
                    <input type="text"  class="form-control"  name="bill_last_name" id="bill_last_name" />
                </div>

                <div class="form-group">
                    <label for="bill_address_line_1">Address<span>*</span></label>
                    <textarea class="form-control fieldcommon" name="bill_address_line_1" id="bill_address_line_1"></textarea>
                </div>

                <div class="form-group">
                    <label for="bill_address_line_2">Address 2</label>
                    <textarea class="form-control" name="bill_address_line_2" id="bill_address_line_2"></textarea>
                </div>

                <div class="clearfix"></div>

                <div class="form-group">
                    <label for="bill_city">City <span>*</span></label>
                    <input type="text"  class="form-control fieldcommon"  name="bill_city" id="bill_city" />
                </div>

                <div class="form-group">
                    <label for="bill_state">State<span>*</span></label>
                    <?php $landing_page->draw_region_select('bill_'); ?>
                </div>

                <div class="form-group">
                    <label for="bill_postal_code">Zip Code<span>*</span></label>
                    <input type="text"  class="form-control fieldcommon" name="bill_postal_code" id="bill_postal_code"  />
                </div>

                <div class="form-group">
                    <label for="bill_phone">Phone <span>*</span></label>
                    <input type="text"  class="form-control" name="bill_phone" id="bill_phone"  />
                </div>

                <div class="form-group">
                    <label for="bill_email">Billing Email <span>*</span></label>
                    <input type="email"  class="form-control" name="bill_email" id="bill_email"  />
                </div>

                <div class="clearfix"></div>

                <div class="hrlinenew"></div>

                <div class=" singlecolumn">
                    <h5 style="float: none; margin: 0; padding:0px; font-weight: normal;">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" id="checkout_billing_shipping_same" class="" name="bill_same_as_ship" value="1" />
                                My billing and shipping address are the same
                            </label>
                        </div>
                    </h5>
                </div>

                <div class="hrlinenew shipbillcls"></div>

                <h2 class="shipbillcls">SHIPPING INFORMATION</h2>

                <div class="form-group shipbillcls">
                    <label for="ship_first_name">First Name<span>*</span></label>
                    <input type="text"  class="form-control"  name="ship_first_name" id="ship_first_name" />
                </div>

                <div class="form-group shipbillcls">
                    <label for="ship_last_name">Last Name<span>*</span></label>
                    <input type="text"  class="form-control"  name="ship_last_name" id="ship_last_name" />
                </div>

                <div class="form-group shipbillcls">
                    <label for="ship_address_line_1">Address<span>*</span></label>
                    <textarea class="form-control fieldcommon" name="ship_address_line_1" id="ship_address_line_1"></textarea>
                </div>

                <div class="form-group shipbillcls">
                    <label for="ship_address_line_2">Address 2</label>
                    <textarea class="form-control" name="ship_address_line_2" id="ship_address_line_2"></textarea>
                </div>

                <div class="clearfix"></div>

                <div class="form-group shipbillcls">
                    <label for="ship_city">City <span>*</span></label>
                    <input type="text"  class="form-control fieldcommon"  name="ship_city" id="ship_city" />
                </div>

                <div class="form-group shipbillcls">
                    <label for="ship_state">State<span>*</span></label>
                    <?php $landing_page->draw_region_select('ship_'); ?>
                </div>

                <div class="form-group shipbillcls">
                    <label for="ship_postal_code">Zip Code<span>*</span></label>
                    <input type="text"  class="form-control fieldcommon" name="ship_postal_code" id="ship_postal_code"  />
                </div>

                <div class="form-group shipbillcls">
                    <label for="ship_phone">Phone <span>*</span></label>
                    <input type="text"  class="form-control" name="ship_phone" id="ship_phone"  />
                </div>

                <div class="form-group shipbillcls">
                    <label for="ship_email">Shipping Email <span>*</span></label>
                    <input type="email"  class="form-control" name="ship_email" id="ship_email"  />
                </div>

                <div class="clearfix shipbillcls"></div>

                <div class="clearfix"></div>

                <div class="hrlinenew"></div>

                <h2>REVIEW PURCHASE</h2>

                <div class=" singlecolumn">
                    <div class="table-responsive">
                        <table class="table">
                            <tr>
                                <td>ITEM</td>
                                <td>Price</td>
                                <td>QUANTITY</td>
                                <td>Total</td>
                            </tr>
                            <tr>
                                <td><span id="ptitle"><?php echo $product['title']; ?></span></td>
                                <td>$<span id="pprice"><?php echo $product['price']; ?></span></td>
                                <td><span id="pquan">1</span></td>
                                <td>$<span id="pamnt"><?php echo $product['price']; ?></span></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td>Sub-Total</td>
                                <td>$<span id="psubtotal"><?php echo $product['price']; ?></span></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td>Shipping</td>
                                <td>$<span id="pship">0.00</span></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td>Tax</td>
                                <td>$<span id="ptax">0.00</span></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td>TOTAL</td>
                                <td>$<span id="ptotal"><?php echo $product['price']; ?></span></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="clearfix"></div>

                <div class="hrlinenew"></div>


                <h2>PAYMENT INFORMATION</h2>

                <div class="form-group singlecolumn">
                    <div class="paymentmode">
                        <?php echo get_cc_radio('card_type');?>
                    </div>

                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 tripplecolumn">
                        <label for="city">Name On Card</label>
                        <input type="text" id="card_name" class="span8" name="card_name" />
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 tripplecolumnnew1">
                        <label for="city">Card Number</label>
                        <input type="text" id="card_number" class="span8" name="card_number" />
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 cardexpiredblock">
                        <label for="city">Expiry Date</label>
                        <?php echo get_cc_expire_input('card_exp_mo', 'card_exp_yr'); ?>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12 tripplecolumn">
                        <label for="city">CVV</label>
                        <input type="text" id="card_cvv" name="card_cvv"  class="span4" maxlength="8" />
                    </div>


                </div>
                <div class="clearfix"></div>

                <div class="hrlinenew"></div>



                <h2>TERMS & CONDITIONS</h2>

                <div class="form-group singlecolumn tctext hide">
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam vestibulum lobortis metus vel vulputate. Maecenas imperdiet purus a velit egestas egestas. Nullam in velit vitae massa dapibus dignissim lacinia id urna. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis id quam aliquet, porttitor magna at, mattis augue. Vestibulum pellentesque aliquet fermentum. Nulla facilisi. Pellentesque at fermentum metus. Integer pulvinar massa in ligula pulvinar, molestie mattis ligula gravida. Vivamus ut sem a nulla fringilla dictum ut eu tellus. Mauris non arcu facilisis, dignissim enim vitae, pellentesque sem.Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam vestibulum lobortis metus vel vulputate. Maecenas imperdiet purus a velit egestas egestas. Nullam in velit vitae massa dapibus dignissim lacinia id urna. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis id quam aliquet, porttitor magna at, mattis augue. Vestibulum pellentesque aliquet fermentum. Nulla facilisi. Pellentesque at fermentum metus. Integer pulvinar massa in ligula pulvinar, molestie mattis ligula gravida. Vivamus ut sem a nulla fringilla dictum ut eu tellus. Mauris non arcu facilisis, dignissim enim vitae, pellentesque sem.</p>
                </div>
                <div class="clearfix"></div>
                <div class=" singlecolumn">
                    <h5 style="float: none; margin: 25px 0 0 0; padding:0px; font-weight: normal;">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" id="iagreetoit" name="check_terms" value="1" />
                                <span style="color:#000000;">I agree to the</span> <br/>
                                <ul class="list-inline text-center tnc" style="color:#000000;margin-top: 20px">

                                    <li><a href="javascript:void(0)" data-toggle="modal" data-target="#myModaltermsf">Terms of Use</a></li>
                                    <li><a href="javascript:void(0)" data-toggle="modal" data-target="#myModalrefundandreturnsf">Refunds and Returns</a></li>
                                    <li><a href="javascript:void(0)" data-toggle="modal" data-target="#myModalPrivacyPolicyf">Privacy Policy</a></li>

                                </ul>
                            </label>
                        </div>
                    </h5>
                </div>

                <div class="clearfix"></div>

                <div class=" opportunity_btnwrapper">
<input type="submit" value="submit">
<input type="reset"value="cancel">

                    <div class="clearfix"></div>
                    </div>


                </form>


        </div>
    </div>
</div>

<?php

function getmailbody($username = '')
{
return '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Enrollment</title>
</head>

<body>

<table width="100%" border="0">
    <tr>
        <td align="center">
            <table width="600" border="0" style="font-family:Arial, Helvetica, sans-serif;">
                <tr>
                    <td align="left" valign="middle" style="padding:15px; padding-bottom:0px;"><img src="http://www.vivacitygo.com/system/themes/prelaunch_lp/images/logo-enrollment.png"  alt="#" style="width:230px;"/></td>

                    <td align="right" valign="middle" style="padding:15px; padding-bottom:0px; font-family:Arial, Helvetica, sans-serif; font-size:15px; color:#3c3c3b; line-height:20px; font-weight:bold;">Financial Freedom.<br />

                        Premium Quality Products.<br />
                        Generous Comp Plan.<br />
                        Be Your Own Boss Now!</td>
                </tr>

                <tr>
                    <td colspan="2" align="center" valign="middle"><img src="http://www.vivacitygo.com/system/themes/prelaunch_lp/images/mailpagebanner1.jpg"  alt="#"/>

                    </td>
                </tr>

                <tr>
                    <td colspan="2" align="center" valign="middle" style="padding:15px 40px; font-family:Arial, Helvetica, sans-serif; font-weight:bold; font-size:15px; color:#3c3c3b; line-height:20px;">


                        We understand our sucess is deeply rooted and intertwined with our most important businesspartner - YOU! <br />
                        <br />


                        Lets live a life full of vitality, inspiration, and health. By join Vivacity, you’ve taking a steptowards <span style="text-transform:uppercase; color:#13a716;">the shift</span> towards permanent
                        transformation in mind, body, and soul.  <br />
                        <br />


                        Congrats on taking the first step towards experiencing the vital essence of an inspired life.

                    </td>
                </tr>


                <tr>
                    <td colspan="2" align="center" valign="middle" style="padding:25px;">


                        <div style="background:#ec2e64; border-radius:5px; padding:15px;">
                            <h1 style="font-family:Arial, Helvetica, sans-serif; font-size:36px; color:#ffffff; text-transform:uppercase; margin:0; padding:0;">A Total Wellness</h1>

                            <h1 style="font-family:Arial, Helvetica, sans-serif; font-size:30px; color:#ffffff; text-transform:uppercase; margin:0; padding:0;">Philosophy with You in Mind</h1>

                            <h2 style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#000000; font-weight:normal; border-bottom:solid 1px #c2204e; margin:0; padding:15px 20px; line-height:24px;">Our programs are easy to follow, short in time, and simple to use. Whether
                                you are a brand-new participant to the health
                                industry or a seasoned athlete, Vivacity has a program level to
                                effectively suit your needs. </h2>

                            <h3 style="font-family:Arial, Helvetica, sans-serif; font-size:20px; color:#e6e6e6; margin:0; padding:12px 0 0 0;">Commit. Choose a package. Feel the results.<br />
                                Upgrade to your new, vital life now!</h3>

                        </div>

                    </td>
                </tr>

                <tr>
                    <td colspan="2" align="center" valign="middle">



                        <h1 style="font-family:Arial, Helvetica, sans-serif; font-weight:bold; font-size:45px; color:#51b517; text-transform:uppercase; margin:0; padding:8px 0 0 0;">Account Information</h1>

                        <h2 style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#5e5e5e; font-weight:normal; line-height:24px; margin:0; padding:10px 0 25px 0;">Full backoffice access will be available during our official launch date, <br />

                            JANUARY 23, 2016. <br />

                            You can still access the backend (limited view) now!  </h2>

                        <h3 style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#3c3c3b; margin:0; padding:0; font-weight:normal;">Username:   <span style="color:#2e7aec; padding-left:10px;">' . $username . '</span></h3>

                        <a href="http://www.vivacitygo.com/login?ai_bypass=true" target="_blank" style="display:block; margin:26px auto; width:120px; height:31px; background:#51b517; font-size:16px; color:#fff; text-align:center; text-transform:uppercase; font-weight:bold; line-height:33px; text-decoration:none;">Login Now</a>



                    </td>
                </tr>

                <tr>
                    <td colspan="2" align="center" valign="middle" style="background:#51b517; padding:10px 2px;">



                        <h1 style="font-family:Arial, Helvetica, sans-serif; font-size:30px; color:#fff; text-transform:uppercase; margin:0; padding:0; font-weight:bold;">we\'re here to help!</h1>

                        <h2 style="font-family:Arial, Helvetica, sans-serif; font-size:13px; color:#ffffff; font-weight:bold; margin:0; padding:5px 0;">If you run into any problems contact us and our team will be sure to take care of you. </h2>

                        <h3 style="font-family:Arial, Helvetica, sans-serif; font-size:12px; font-weight:normal; color:#193906; line-height:18px; margin:0; padding:10px 0 0 0;">210 E. Tennessee Street Florence, AL 35630<br />

                            info@makewaywellness.com<br />
                            Phone: 800.928.9401<br />
                            Fax: 615.861.8955<br /></h3>





                    </td>
                </tr>
                <tr>
                    <td colspan="2" align="center" valign="middle">



                        <img src="http://www.vivacitygo.com/system/themes/prelaunch_lp/images/logo-enrollment.png"  alt="#"  style="width:170px; display:block; margin:10px auto;"/>




                    </td>
                </tr>

            </table>



        </td>
    </tr>
</table>


</body>
</html>
';
}
?>