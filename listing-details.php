<?php require_once('Connections/cms.php'); ?>
<?php
$pageID = -1;
?>
<?php
if (!function_exists("GetSQLValueString")) {
    function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "")
    {
        if (PHP_VERSION < 6) {
            $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
        }

        $theValue = function_exists("mysqli_real_escape_string") ? mysqli_real_escape_string($theValue) : mysqli_escape_string($theValue);

        switch ($theType) {
            case "text":
                $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
                break;
            case "long":
            case "int":
                $theValue = ($theValue != "") ? intval($theValue) : "NULL";
                break;
            case "double":
                $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
                break;
            case "date":
                $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
                break;
            case "defined":
                $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
                break;
        }
        return $theValue;
    }
}

$colname_listing = "-1";
if (isset($_GET['listingID'])) {
    $colname_listing = $_GET['listingID'];
}
mysqli_select_db($cms, $database_cms);
$query_listing = sprintf("SELECT * FROM listings  LEFT JOIN (SELECT photoAlbums.albumID,photoAlbums.coverPhotoID,photoAlbums.albumName,photos.id,photos.file_name FROM photoAlbums,photos WHERE photoAlbums.coverPhotoID=photos.id)  AS a ON listings.albumID=a.albumID  WHERE listingID = %s", GetSQLValueString($colname_listing, "int"));
$listing = mysqli_query($cms, $query_listing) or die(mysqli_error($cms));
$row_listing = mysqli_fetch_assoc($listing);
$totalRows_listing = mysqli_num_rows($listing);
$totalRows_photos = 0;

if ($row_listing['albumID'] != NULL){
    $query_photos = "SELECT * FROM photos WHERE albumID = ".$row_listing['albumID']." ORDER BY photoSequence ASC";
    $photos = mysqli_query($cms, $query_photos) or die(mysqli_error($cms));
    $row_photos = mysqli_fetch_assoc($photos);
    $totalRows_photos = mysqli_num_rows($photos);
}

$query_websiteInfo = "SELECT * FROM cmsWebsites WHERE websiteID = ".$websiteID;
$websiteInfo = mysqli_query($cms, $query_websiteInfo) or die(mysqli_error($cms));
$row_websiteInfo = mysqli_fetch_assoc($websiteInfo);
$totalRows_websiteInfo = mysqli_num_rows($websiteInfo);
?>
<?php
$pageTitle = 'Listing Details';
if ($row_listing['propertyLocation'] != '') $pageTitle = $row_listing['propertyLocation'];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html><!-- InstanceBegin template="/Templates/template.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
    <!-- InstanceBeginEditable name="doctitle" -->
    <title>Listing Details |<?php echo $row_listing['propertyLocation']; ?></title>
    <!-- InstanceEndEditable -->
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <link rel="stylesheet" type="text/css" href="styles/styles-old.css">
    <link href="styles/styles.css" rel="stylesheet" type="text/css">
    <!-- InstanceBeginEditable name="head" -->
    <link rel="stylesheet" type="text/css" href="styles/flickity.css"/>
    <!-- InstanceEndEditable -->
    <script src="foo.js" type="text/javascript"></script>
</head>
<body>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
    <tr>
        <td height="90">&nbsp;</td>
        <td width="750" height="90"><table width="750" height="150" border="0" align="center" cellpadding="0" cellspacing="0">
                <tr>
                    <td width="600" height="100" bgcolor="#000000"><img src="images/design5C/top.gif" width="600" height="150"></td>
                    <td width="150" height="100"><a href="http://flipperauctions.hibid.com/email/subscribe"><img src="images/design5C/logo.gif" width="150" height="150" border="0"></a></td>
                </tr>
            </table></td>
        <td height="90">&nbsp;</td>
    </tr>
    <tr>
        <td height="30">&nbsp;</td>
        <td width="750" height="30"><img src="images/design5C/links.gif" width="750" height="30" border="0" usemap="#LinkMap">
            <map name="LinkMap" id="LinkMap">
                <!--
                  <area shape="rect" coords="4,2,58,28" href="index.php" alt="">
                  <area shape="rect" coords="61,1,206,35" href="listings.php" alt="">
                  <area shape="rect" coords="206,1,320,28" href="recent-results.php" alt="">
                  <area shape="rect" coords="320,1,466,44" href="auction-advantages.php" alt="">
                  <area shape="rect" coords="469,0,581,40" href="online-bidding.php" alt="">
                  <area shape="rect" coords="582,-2,661,38" href="about.php" alt="">
                  <area shape="rect" coords="662,1,748,34" href="contact.php" alt="">
                  -->
                <area shape="rect" coords="4,2,58,28" href="index.php" alt="">
                <area shape="rect" coords="61,1,206,35" href="http://flipperauctions.hibid.com/auctions/current" alt="">
                <area shape="rect" coords="206,1,320,28" href="http://flipperauctions.hibid.com/auctions/past" alt="">
                <area shape="rect" coords="320,1,466,44" href="auction-advantages.php" alt="">
                <area shape="rect" coords="469,0,581,40" href="online-bidding.php" alt="">
                <area shape="rect" coords="582,-2,661,38" href="about.php" alt="">
                <area shape="rect" coords="662,1,748,34" href="contact.php" alt="">
            </map></td>
        <td height="30">&nbsp;</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td width="750" align="left" valign="top" class="colorC"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="20">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td width="20">&nbsp;</td>
                </tr>
                <tr>
                    <td width="20">&nbsp;</td><td align="left" valign="top">
                        <!-- InstanceBeginEditable name="main" -->
                        <h1 style="text-align:center"><?php echo $row_listing['propertyLocation']; ?></h1>
                        <!-- listing photos -->
                        <?php
                        if ($totalRows_photos>0){
                            ?>
                            <div class="main-gallery">
                                <?php do {
                                    list($width, $height) = getimagesize('http://4siteusa.com/uploads/'.$row_photos['file_name']);
                                    ?>
                                    <div class="gallery-cell" style="width:<?php echo $width ?>px; height:auto;"><img width="<?php echo $width ?>" height="<?php echo $height ?>" src="http://4siteusa.com/uploads/<?php echo $row_photos['file_name']; ?>"/></div>
                                <?php } while ($row_photos = mysqli_fetch_assoc($photos)); ?>
                            </div>
                            <?php
                        }
                        ?>
                        <?php if ($row_listing['longDescription'] != ''){ ?>
                            <p style="padding-top:20px"><?php echo $row_listing['longDescription']; ?></p>
                        <?php } ?>
                        <div class="wf_centered"><a href="contact.php?listingID=<?php echo $row_listing['listingID']; ?>" class="button">ask a question?</a><?php if ($row_listing['virtualTourLink'] != ''){ ?>
                                <a href="<?php echo $row_listing['virtualTourLink']; ?>" class="button">auction details</a>
                            <?php } ?></div>
                        <?php if ($row_listing['interiorFeatures'] != ''){ ?>
                            <hr />
                            <h2 class="wf_centered">Interior Features</h2>
                            <p><?php echo $row_listing['interiorFeatures']; ?></p>
                        <?php } ?>
                        <?php if ($row_listing['exteriorFeatures'] != ''){ ?>
                            <hr />
                            <h2 class="wf_centered">Exterior Features</h2>
                            <p><?php echo $row_listing['exteriorFeatures']; ?></p>
                        <?php } ?>
                        <hr />
                        <h2 class="wf_centered">Property Details</h2>
                        <table class="wf_centered" style="margin-top:20px" border="0" align="center" cellpadding="5" cellspacing="0">
                            <tbody>
                            <?php if ($row_listing['propertyPrice'] != '' && $row_listing['propertyPrice'] != 0){ ?>
                                <tr align="left" valign="top">
                                    <td height="22">Price:</td>
                                    <td height="22"><strong><?php echo "$".number_format($row_listing['propertyPrice'],0); ?></strong></td>
                                </tr>
                            <?php } ?>
                            <?php if ($row_listing['propertyStatus'] != ''){ ?>
                                <tr align="left" valign="top">
                                    <td height="22">Status:</td>
                                    <td height="22"><?php echo $row_listing['propertyStatus']; ?></td>
                                </tr>
                            <?php } ?>
                            <?php if ($row_listing['propertyType'] != ''){ ?>
                                <tr align="left" valign="top">
                                    <td height="22">Property Type:</td>
                                    <td height="22"><?php echo $row_listing['propertyType']; ?></td>
                                </tr>
                            <?php } ?>
                            <?php if ($row_listing['propertyStyle'] != ''){ ?>
                                <tr align="left" valign="top">
                                    <td height="22">Property Style:</td>
                                    <td height="22"><?php echo $row_listing['propertyStyle']; ?></td>
                                </tr>
                            <?php } ?>
                            <?php if ($row_listing['mlsNumber'] != ''){ ?>
                                <tr align="left" valign="top">
                                    <td height="22">MLS Number:</td>
                                    <td height="22"><?php echo $row_listing['mlsNumber']; ?></td>
                                </tr>
                            <?php } ?>
                            <?php if ($row_listing['beds'] != ''){ ?>
                                <tr align="left" valign="top">
                                    <td height="22">Beds:</td>
                                    <td height="22"><?php echo $row_listing['beds']; ?></td>
                                </tr>
                            <?php } ?>
                            <?php if ($row_listing['fullBaths'] != ''){ ?>
                                <tr align="left" valign="top">
                                    <td height="22">Full Baths:</td>
                                    <td height="22"><?php echo $row_listing['fullBaths']; ?></td>
                                </tr>
                            <?php } ?>
                            <?php if ($row_listing['halfBaths'] != ''){ ?>
                                <tr align="left" valign="top">
                                    <td height="22">Half Baths:</td>
                                    <td height="22"><?php echo $row_listing['halfBaths']; ?></td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                        <div class="wf_centered"><a href="listings.php" class="button">back to the listings page</a></div>
                        <!-- InstanceEndEditable --></td>
                    <td width="20">&nbsp;</td>
                </tr>
            </table></td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td height="20">&nbsp;</td>
        <td height="30" align="left" valign="middle" class="colorC"><div align="center">
                <table width="100%" border="0" cellspacing="0" cellpadding="4">
                    <tr>
                        <td height="15" valign="top">&nbsp;</td>
                    </tr>
                    <tr>
                        <td valign="top"><div align="center" class="footerText">Flipper McDaniel and Associates&nbsp; ::&nbsp; 426 South Wall Street&nbsp; ::&nbsp;&nbsp;Calhoun, GA&nbsp;30701</div></td>
                    </tr>
                </table>
            </div></td>
        <td height="20">&nbsp;</td>
    </tr>
</table>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<!-- InstanceBeginEditable name="scripts" --><script src="scripts/flickity.pkgd.min.js"></script>
<script>
    $('.main-gallery').flickity({
        // options
        cellAlign: 'center',
        contain: true,
        autoPlay: true,
        autoPlay: 3000,
        imagesLoaded: true,
        pageDots: false
    });
    jQuery(document).ready(function() {
        $('.main-gallery').flickity('reloadCells');
        $('.banner').css('margin-top','59px');
    });
</script>  <!-- InstanceEndEditable -->

</body>
<!-- InstanceEnd --></html>
<?php
mysqli_free_result($listing);

//mysqli_free_result($photos);

mysqli_free_result($websiteInfo);
?>
