
<?php

$uri_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri_segments = explode('/', $uri_path);
@$video_category = $uri_segments[2];
$str='';
if($video_category!=''){
    $str .= ' and video_category='.$video_category;
}
if($_GET['search']!=''){
    //echo 14332424;
   // echo $_GET['search'];
    $search=$_GET['search'];
   // $str .= ' and tilte ="temp"' ;
    $str .= " and (title like '%".$search."%' or description like '%".$search."%' or priority like '%".$search."%')" ;
   //  echo $str;
}
//echo "select * from video_manager where status=1".$str." order by time desc";
$resvideoorderbytime=db_query("select * from video_manager where status=1".$str." order by time desc");
$resvideoorderbypriority=db_query("select * from video_manager where status=1 order by priority asc");
//$rescategoryrderbyid=db_query("select * from blog_video_category where status=1 order by id desc");
$rescategoryrderbyid=db_query("SELECT bc.*, COUNT(v.video_category) AS videocount
FROM blog_video_category AS bc
LEFT JOIN video_manager AS v ON bc.id = v.video_category
GROUP BY bc.id having bc.status=1 ORDER BY bc.id DESC");
//print_r($rescategoryrderbyid);
//$res=db_fetch_assoc($resvideoorderbytime);
//print_r($res);
//while($rowcat=db_fetch_assoc($rescategory)){
function getpropername($title=''){
    $pro_url_title = strtolower(trim($title));
    $pro_url_title = preg_replace("/[^a-z0-9_\s-]/", "", $pro_url_title);
    $pro_url_title = preg_replace("/[\s-]+/", " ", $pro_url_title);
    $pro_url_title = preg_replace("/[\s_]/", "-", $pro_url_title);
    return $pro_url_title;
}

?>

<div class="container-fluid innerpagetitleblock text-center">
    <div class="innerpagetitleblockwrapper">
        <h1>Videos</h1>
    </div>
</div>
<div class="container-fluid videoblock1">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="videoblockwrapper">
                <div class="row">
                    <div class="col-lg-9 col-md-8 col-sm-12 col-xs-12 videoblock1left">
                        <div class="row">
                            <?php if(count($resvideoorderbytime)>0){
                                while($rowvideobytime=db_fetch_assoc($resvideoorderbytime)){

                                    ?>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 videosingleblock">
                                        <div class="video-container">
                                            <!-- <img src="system/themes/vivacity_frontend/images/videoimg1.jpg">-->
                                            <a href="/videodetails/<?php echo $rowvideobytime['id']?>/<?php echo getpropername($rowvideobytime['title'])?>"><img src="https://img.youtube.com/vi/<?php echo $rowvideobytime['file']?>/0.jpg"></a>
                                            <div class="videoicons">
                                                <a href="/videodetails/<?php echo $rowvideobytime['id']?>/<?php echo getpropername($rowvideobytime['title'])?>"><img src="system/themes/vivacity_frontend/images/iconaddvideo.jpg"></a>
                                                <a href="javascript:void(0)" data-toggle="tooltip" data-html="true" title='<div class="myToolTip tooltip-top" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner">
                                                     <ul class="list-inline">
                                                        <li>
                                                            <a href="javascript:void(0)">
                                                                <img src="system/themes/vivacity_frontend/images/icon-smfb.png">
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="javascript:void(0)">
                                                                <img src="system/themes/vivacity_frontend/images/icon-smpint.png">
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="javascript:void(0)">
                                                                <img src="system/themes/vivacity_frontend/images/icon-smtweet.png">
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="javascript:void(0)">
                                                                <img src="system/themes/vivacity_frontend/images/icon-smgplus.png">
                                                            </a>
                                                        </li>
                                                    </ul>
                                                 </div>
                                            </div>'>
                                            <img src="system/themes/vivacity_frontend/images/iconsharevideo.jpg"></a>
                                                <!-- Generated markup by the plugin -->
                                            </div>
                                            <!--<div class="tooltip tooltip-top" role="tooltip">
                                                <div class="tooltip-arrow"></div>
                                                <div class="tooltip-inner">
                                                    <ul class="list-inline">
                                                        <li>
                                                            <a href="javascript:void(0)">
                                                                <img src="system/themes/vivacity_frontend/images/iconfbvideo.png">
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="javascript:void(0)">
                                                                <img src="system/themes/vivacity_frontend/images/iconpintvideo.png">
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="javascript:void(0)">
                                                                <img src="system/themes/vivacity_frontend/images/icontwvideo.png">
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="javascript:void(0)">
                                                                <img src="system/themes/vivacity_frontend/images/icongplusvideo.png">
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="javascript:void(0)">
                                                                <img src="system/themes/vivacity_frontend/images/iconblogvideo.png">
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>-->
                                        </div>
                                        <h2><a href="/videodetails/<?php echo $rowvideobytime['id']?>/<?php echo getpropername($rowvideobytime['title'])?>"><?php echo $rowvideobytime['title']; ?></a></h2>
                                       <!-- <p><?php /*echo $rowvideobytime['description']; */?></p>-->
                                        <p><?php echo strlen($rowvideobytime['description']) > 150 ? substr($rowvideobytime['description'], 0, 150).'...' : $rowvideobytime['description']; ?></p>
                                    </div>

                                <?php }
                            } ?>

                           <!--<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 videosingleblock">
                                <div class="video-container">
                                    <img src="system/themes/vivacity_frontend/images/videoimg1.jpg">
                                    <div class="videoicons">
                                       <a href="javascript:void(0)"><img src="system/themes/vivacity_frontend/images/iconaddvideo.jpg"></a>
                                       <a href="javascript:void(0)"><img src="system/themes/vivacity_frontend/images/iconsharevideo.jpg"></a>
                                    </div>
                                </div>
                                <h2><a href="/videodetails">WE BELIEVE</a></h2>
                                <p>Fusce suscipit sit amet nisi in euismod. Aenean dignissim finibus eros, sit amet venenatis ante vestibulum id.elit, luctus eu porta a, pellentesque a mi.</p>
                            </div>


                          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 videosingleblock">
                                <div class="video-container">
                                    <img src="system/themes/vivacity_frontend/images/videoimg2.jpg">
                                    <div class="videoicons">
                                        <a href="javascript:void(0)"><img src="system/themes/vivacity_frontend/images/iconaddvideo.jpg"></a>
                                        <a href="javascript:void(0)"><img src="system/themes/vivacity_frontend/images/iconsharevideo.jpg"></a>
                                    </div>
                                </div>
                                <h2><a href="/videodetails">WE BELIEVE</a></h2>
                                <p>Fusce suscipit sit amet nisi in euismod. Aenean dignissim finibus eros, sit amet venenatis ante vestibulum id.elit, luctus eu porta a, pellentesque a mi.</p>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 videosingleblock">
                                <div class="video-container">
                                    <img src="system/themes/vivacity_frontend/images/videoimg3.jpg">
                                    <div class="videoicons">
                                        <a href="javascript:void(0)"><img src="system/themes/vivacity_frontend/images/iconaddvideo.jpg"></a>
                                        <a href="javascript:void(0)"><img src="system/themes/vivacity_frontend/images/iconsharevideo.jpg"></a>
                                    </div>
                                </div>
                                <h2><a href="/videodetails">WE BELIEVE</a></h2>
                                <p>Fusce suscipit sit amet nisi in euismod. Aenean dignissim finibus eros, sit amet venenatis ante vestibulum id.elit, luctus eu porta a, pellentesque a mi.</p>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 videosingleblock">
                                <div class="video-container">
                                    <img src="system/themes/vivacity_frontend/images/videoimg4.jpg">
                                    <div class="videoicons">
                                        <a href="javascript:void(0)"><img src="system/themes/vivacity_frontend/images/iconaddvideo.jpg"></a>
                                        <a href="javascript:void(0)"><img src="system/themes/vivacity_frontend/images/iconsharevideo.jpg"></a>
                                    </div>
                                </div>
                                <h2><a href="/videodetails">WE BELIEVE</a></h2>
                                <p>Fusce suscipit sit amet nisi in euismod. Aenean dignissim finibus eros, sit amet venenatis ante vestibulum id.elit, luctus eu porta a, pellentesque a mi.</p>
                            </div>-->
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12 videoblock1right">
                        <div class="searchboxblock">
                            <form method="get" action="">
                            <input type="search" name="search" class="form-control" placeholder="search">
                              <!--  <input type="submit" value="Search">-->
                            </form>
                        </div>
                        <div class="videoblock1rightmgr videoblock1rightblock1">
                            <h2>Popular Videos</h2>
                            <div class="hrlinegray"></div>
                            <div class="videoblock1rightlists">
                                <ul>
                                    <?php if(count($resvideoorderbypriority)>0){
                                    while($rowvideobypriority=db_fetch_assoc($resvideoorderbypriority)){

                                    ?>
                                    <li>
                                        <div clas="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                <div class="videoblock1left">
                                                    <div class="video-container">
                                                       <!-- <img src="system/themes/vivacity_frontend/images/popularvideo1.jpg">-->
                                                        <a href="/videodetails/<?php echo $rowvideobypriority['id']?>/<?php echo getpropername($rowvideobypriority['title'])?>"><img src="https://img.youtube.com/vi/<?php echo $rowvideobypriority['file']?>/hqdefault.jpg""></a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                <h2><a href="/videodetails/<?php echo $rowvideobypriority['id']?>/<?php echo getpropername($rowvideobypriority['title'])?>"><?php echo $rowvideobypriority['title']; ?></a></h2>
                                                <p><?php echo date("jS M, Y",$rowvideobypriority['time']); ?></p>
                                            </div>
                                        </div>
                                    </li>
                                    <?php }
                                    }?>
                                   <!-- <li>
                                        <div clas="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                <div class="videoblock1left">
                                                    <div class="video-container">
                                                        <img src="system/themes/vivacity_frontend/images/popularvideo2.jpg">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                <h2>Lorem Ipsms</h2>
                                                <p>Sep, 26 2015.</p>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div clas="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                <div class="videoblock1left">
                                                    <div class="video-container">
                                                        <iframe width="200" height="170" src="https://www.youtube.com/embed/GtDkOsbsxfI" frameborder="0" allowfullscreen></iframe>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                <h2>Lorem Ipsms</h2>
                                                <p>Sep, 26 2015.</p>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div clas="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                <div class="videoblock1left">
                                                    <div class="video-container">
                                                        <img src="system/themes/vivacity_frontend/images/popularvideo4.jpg">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                <h2>Lorem Ipsms</h2>
                                                <p>Sep, 26 2015.</p>
                                            </div>
                                        </div>
                                    </li>-->
                                </ul>
                            </div>
                            <div class="videoblock1rightlistsbottomimg">
                                <div class="video-container">
                                    <img src="system/themes/vivacity_frontend/images/popularvideobottomimg.jpg">
                               </div>
                            </div>
                        </div>
                        <div class="videoblock1rightmgr videoblock1rightblock2">
                            <h2>catagories</h2>
                            <div class="hrlinegray"></div>
                            <div class="videoblock1rightblock2lists">
                                <ul class="list-group">
                                    <?php if(count($rescategoryrderbyid)>0){
                                    while($rowcategorybyid=db_fetch_assoc($rescategoryrderbyid)){

                                    ?>

                                    <li class="list-group-item">
                                        <a href="/videos/<?php echo $rowcategorybyid['id']?>/<?php echo getpropername($rowcategorybyid['title'])?>">
                                        <label><?php echo $rowcategorybyid['title'];?></label>
                                        <span class="badge">(<?php echo $rowcategorybyid['videocount'];?>)</span></a>
                                    </li>
                                    <?php }
                                    }?>
                                   <!-- <li class="list-group-item">
                                        <label>Lorem</label>
                                        <span class="badge">(95)</span>
                                    </li>
                                    <li class="list-group-item">
                                        <label>Lorem</label>
                                        <span class="badge">(65)</span>
                                    </li>
                                    <li class="list-group-item">
                                        <label>Lorem</label>
                                        <span class="badge">(49)</span>
                                    </li>
                                    <li class="list-group-item">
                                        <label>Lorem</label>
                                        <span class="badge">(98)</span>
                                    </li>
                                    <li class="list-group-item">
                                        <label>Lorem</label>
                                        <span class="badge">(25)</span>
                                    </li>-->
                                </ul>
                            </div>
                        </div>
                        <div class="videoblock1rightmgr videoblock1rightblock3">
                            <h2>Social Media</h2>
                            <div class="hrlinegray"></div>
                            <ul class="list-inline">
                                <li>
                                    <a href="javascript:void(0)">
                                        <img src="system/themes/vivacity_frontend/images/iconfbvideo.png">
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)">
                                        <img src="system/themes/vivacity_frontend/images/iconpintvideo.png">
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)">
                                        <img src="system/themes/vivacity_frontend/images/icontwvideo.png">
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)">
                                        <img src="system/themes/vivacity_frontend/images/icongplusvideo.png">
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)">
                                        <img src="system/themes/vivacity_frontend/images/iconblogvideo.png">
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
         </div>
    </div>
</div>


<script type="text/javascript">
    $(function () {
        $('a[data-toggle=tooltip]').tooltip();
    });
</script>