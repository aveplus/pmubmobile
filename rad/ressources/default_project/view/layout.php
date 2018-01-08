<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="<?=$_APP['_LANG_']?>">
<head>
<title><?=$_APP['_TITLE_']?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<link rel="shortcut icon" href="<?=$_APP['_PUBLIC_PATH_']?>images/ico/favicon.PNG">
<link href="<?=$_APP['_PUBLIC_PATH_']?>css/style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="<?=$_APP['_PUBLIC_PATH_']?>css/coin-slider.css" />
<link href="<?=$_APP['_PUBLIC_PATH_']?>css/bootstrap.min.css" rel="stylesheet">

<script type="text/javascript" src="<?=$_APP['_PUBLIC_PATH_']?>lib/js/cufon-yui.js"></script>
<script type="text/javascript" src="<?=$_APP['_PUBLIC_PATH_']?>lib/js/cufon-yanone.js"></script>
<script type="text/javascript" src="<?=$_APP['_PUBLIC_PATH_']?>lib/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="<?=$_APP['_PUBLIC_PATH_']?>lib/js/script.js"></script>
<script type="text/javascript" src="<?=$_APP['_PUBLIC_PATH_']?>lib/js/coin-slider.min.js"></script>
<script type="text/javascript" src="<?=$_APP['_PUBLIC_PATH_']?>lib/js/bootstrap.min.js"></script>

</head>
<body>
<div class="main">
  <div class="header">
    <div class="header_resize">
      <div class="searchform">
        <form id="formsearch" name="formsearch" method="post" action="#">
          <span>
          <input name="editbox_search" class="editbox_search" id="editbox_search" maxlength="80" value="Search our ste:" type="text" />
          </span>
          <input name="button_search" src="<?=$_APP['_PUBLIC_PATH_']?>images/search.gif" class="button_search" type="image" />
        </form>
      </div>
      <div class="menu_nav">
        <ul>
          <li class="<?=$_APP['Home']?>"><a href="<?=$_APP['_PATH_']?>"><span>Home Page</span></a></li>
          <li class="<?=$_APP['Support']?>"><a href="<?=$_APP['_PATH_']?>Support"><span>Support</span></a></li>
          <li class="<?=$_APP['About']?>"><a href="<?=$_APP['_PATH_']?>About"><span>About Us</span></a></li>
          <li class="<?=$_APP['Blog']?>"><a href="<?=$_APP['_PATH_']?>Blog"><span>Blog</span></a></li>
          <li class="<?=$_APP['Contact']?>"><a href="<?=$_APP['_PATH_']?>Contact"><span>Contact Us</span></a></li>
        </ul>
      </div>
      <div class="clr"></div>
      <div class="logo">
        <h1><a href="<?=$_APP['_PATH_']?>">Easy<span>Dev</span> <small>Company</small></a></h1>
      </div>
	<?php
	if($_APP['_TITLE_'] != "Error | EasyDev")
	{
		$path = $_APP['_PUBLIC_PATH_'];
		echo '     
		  <div class="clr"></div>
		  <div class="slider">
			<div id="coin-slider"> <a href="#"><img src="'.$path.'images/slide1.jpg" width="960" height="320" alt="" /><span><big>Sed condimentum justo sit amet urna ornare euismod.</big><br />
			  Tusce nec iaculis risus hasellus nec sem sed tellus malesuada porttitor. Mauris scelerisque feugiat ante in vulputate. Nam sit amet ullamcorper tortor. Phasellus posuere facilisis cursus. Nunc est lorem, dictum at scelerisque sit amet, faucibus et est. Proin mattis ipsum quis arcu aliquam molestie.</span></a> <a href="#"><img src="'.$path.'images/slide2.jpg" width="960" height="320" alt="" /><span><big>Amet urna ornare euismodSed condimentum.</big><br />
			  Tusce nec iaculis risus hasellus nec sem sed tellus malesuada porttitor. Mauris scelerisque feugiat ante in vulputate. Nam sit amet ullamcorper tortor. Phasellus posuere facilisis cursus. Nunc est lorem, dictum at scelerisque sit amet, faucibus et est. Proin mattis ipsum quis arcu aliquam molestie.</span></a> <a href="#"><img src="'.$path.'images/slide3.jpg" width="960" height="320" alt="" /><span><big>Sed condimentum justo sit amet urna ornare euismod.</big><br />
			  Tusce nec iaculis risus hasellus nec sem sed tellus malesuada porttitor. Mauris scelerisque feugiat ante in vulputate. Nam sit amet ullamcorper tortor. Phasellus posuere facilisis cursus. Nunc est lorem, dictum at scelerisque sit amet, faucibus et est. Proin mattis ipsum quis arcu aliquam molestie.</span></a> </div>
			<div class="clr"></div>
		  </div>
		  <div class="clr"></div>
		'; 
	}
	?>
    </div>
  </div>
  
  <?=$_APP['_CONTENT_']?>
  
<?php
if($_APP['_TITLE_'] != "Error | EasyDev")
  echo '
  <div class="fbg">
    <div class="fbg_resize">
      <div class="col c1">
        <h2><span>Image</span> Gallery</h2>
        <a href="#"><img src="'.$path.'images/gal1.jpg" width="75" height="75" alt="" class="gal" /></a> <a href="#"><img src="'.$path.'images/gal2.jpg" width="75" height="75" alt="" class="gal" /></a> <a href="#"><img src="'.$path.'images/gal3.jpg" width="75" height="75" alt="" class="gal" /></a> <a href="#"><img src="'.$path.'images/gal4.jpg" width="75" height="75" alt="" class="gal" /></a> <a href="#"><img src="'.$path.'images/gal5.jpg" width="75" height="75" alt="" class="gal" /></a> <a href="#"><img src="'.$path.'images/gal6.jpg" width="75" height="75" alt="" class="gal" /></a> </div>
      <div class="col c2">
        <h2><span>Services</span> Overview</h2>
        <p>Curabitur sed urna id nunc pulvinar semper. Nunc sit amet tortor sit amet lacus sagittis posuere cursus vitae nunc.Etiam venenatis, turpis at eleifend porta, nisl nulla bibendum justo.</p>
        <ul class="fbg_ul">
          <li><a href="#">Lorem ipsum dolor labore et dolore.</a></li>
          <li><a href="#">Excepteur officia deserunt.</a></li>
          <li><a href="#">Integer tellus ipsum tempor sed.</a></li>
        </ul>
      </div>
      <div class="col c3">
        <h2><span>Contact</span> Us</h2>
        <p>Nullam quam lorem, tristique non vestibulum nec, consectetur in risus. Aliquam a quam vel leo gravida gravida eu porttitor dui.</p>
        <p class="contact_info"> <span>Address:</span> 1458 TemplateAccess, USA<br />
          <span>Telephone:</span> +226 70 62 66 83<br />
          <span>FAX:</span>+226 70 62 66 84 <br />
          <span>Others:</span> +226 76 91 30 29<br />
          <span>E-mail:</span> <a href="#">info@aveplus.net</a> </p>
      </div>
      <div class="clr"></div>
    </div>
  </div>';
  ?>
  
  <div class="footer">
    <div class="footer_resize">
      <p class="lf">&copy; Copyright <a href="http://www.aveplus.net/" target="_blank">AvePLUS</a>.</p>
      <p class="rf">Design by AvePLUS <a href="http://www.aveplus.net/" target="_blank">Web Templates</a></p>
      <div style="clear:both;"></div>
    </div>
  </div>
</div>
</body>
</html>