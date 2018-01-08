<!DOCTYPE html>
<html lang="<?=$_APP['_LANG_']?>">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=$_APP['_TITLE_']?></title>
<meta name="keywords" content="nanphp framework, web apps ide, free framework, easy web application, model view controller, mvc, PHP, CSS, HTML" />
<meta name="description" content="Web apps php framework, Un IDE professionnel pour développement web" />

<style>
#templatemo_menu {
	float: left;
	width: 950px;
	height: 40px;
	margin: 0;
	padding: 0;
	background: url(<?=$_APP['_PUBLIC_PATH_']?>images/menuBG.gif) repeat-x;
}
#templatemo_banner {
	clear: both;
	float: left;
	width: 750px;
	padding: 30px 150px 15px 50px;
    margin-bottom: -25px;
	<?php if($_APP['Home']!="") echo 'height:140px;';?>
	background: url(<?=$_APP['_PUBLIC_PATH_']?>images/bannerBG.gif) no-repeat;
}

.service_box {
	float: left;
	width: 220px;
	height: 252px;
	padding: 10px 15px 10px 15px;
	margin-right: 50px;
	background: url(<?=$_APP['_PUBLIC_PATH_']?>images/boxBG.png) no-repeat;
}
</style>

<link rel="shortcut icon" href="<?=$_APP['_PUBLIC_PATH_']?>images/favicon.PNG">
<!--link href="<?=$_APP['_PUBLIC_PATH_']?>css/bootstrap.min.css" rel="stylesheet"-->
<link href="<?=$_APP['_PUBLIC_PATH_']?>css/ide_style.css" rel="stylesheet">

<style>
#templatemo_container {
	margin: auto;
	width: 950px;
	padding: 0px 10px;
	background: url(<?=$_APP['_PUBLIC_PATH_']?>images/mainBG.gif) repeat-y;
}

#templatemo_menu li a:hover, #templatemo_menu li .current{
	color: #FFFFFF;
	background: url(<?=$_APP['_PUBLIC_PATH_']?>images/menuhoverBG.gif) no-repeat;
}

.templatemo_fullgraybox li {
	list-style: inside;
	padding: 0 0 10px 0;
	background: url(<?=$_APP['_PUBLIC_PATH_']?>images/listicon.gif) center left no-repeat;
}
</style>


</head>
<body>
<div id="templatemo_container">
	
    <div id="templatemo_topbar">
		<div id="languagebox">
			<a href="#"><img src="<?=$_APP['_PUBLIC_PATH_']?>images/drapeau/engflag.gif" alt="English" width="25" height="15"/></a>
			<a href="#"><img src="<?=$_APP['_PUBLIC_PATH_']?>images/drapeau/frenchflag.gif" alt="French" width="25" height="15"/></a>
			<a href="#"><img src="<?=$_APP['_PUBLIC_PATH_']?>images/drapeau/germanyflag.gif" alt="Germany" width="25" height="15"/></a>
			<a href="#"><img src="<?=$_APP['_PUBLIC_PATH_']?>images/drapeau/japanflag.gif" alt="Japan" width="25" height="15"/></a>
        </div>
    </div>
    
	<div id="templatemo_header" style="padding-bottom:4px">
		<div id="templatemo_logo">
			<img src="<?=$_APP['_PUBLIC_PATH_']?>images/ico/logo.PNG" alt="Logo" />
		<div id="templatemo_sitetitle"><?=APP_ID.' '.APP_VERSION?></div>
	</div>
        
        <!--div id="templatemo_login">
        	<form method="post" action="#">
                <label>Email:</label><input class="inputfield" name="email_address" type="text" id="email_address"/>
                <label>Password:</label><input class="inputfield" name="password" type="password" id="password"/>
                <input class="button" type="submit" name="Submit" value="Login" />
            </form>
        </div-->
    </div>
    
	<div id="templatemo_menu">
     	<ul>
			<li><a href="<?=$_APP['_PATH_']?>" class="<?=$_APP['Home']?>">Accueil</a></li>
			<li><a href="<?=$_APP['_PATH_']?>Projet" class="<?=$_APP['Projet']?>">Projet</a></li>
            <li><a href="<?=$_APP['_PATH_']?>Contact" class="<?=$_APP['Contact']?>">Contact</a></li> 
            <li><a href="http://www.nanaphp.net/Solutions" target="_blank">Solutions</a></li>
            <li><a href="http://www.nanaphp.net/Forum">Forum</a></li>  
            <li><a href="http://www.nanaphp.net/Faq" target="_blank" class="lastmenu">Faq</a></li>                      
        </ul>  
    </div>
    
    <div id="templatemo_banner">
    	<h1> Your Future Web Apps IDE</h1>
      <p>nanaPHP est un framework combiné avec un IDE qui accélère le dévelopement web en vous permettant d'aller droit au développement métiers par la suppression des phases routinières.
        </p>
        <div class="more_button"><a href="http://www.nanaphp.net/Presentation" target="_blank">Lire plus...</a></div>
    </div>
       
	<?=$_APP['_CONTENT_']?>
  
  	<div id="templatemo_newssection">
        <h1>Dernières actualités nanaPHP!</h1>
        
        <div class="newsbox">
            <h4>Nouvelle version de nanaPHP</h4>
            <p>nanaPHP annonce la sortie de sa version 1.1 en mis septembre 2015. Cette version aura comme nouveauté principale l'intégration du gestionnaire de projet... <a href="http://www.nanaphp.net/news/version/v11" target="_blank">Lire plus...</a> </p>
    	</div>
    
        <div class="newsbox">
        	<h4>nanaPHP à l'honneur!</h4>
        	<p>nanaPHP est reconnu lors de la dernière rencontre gouvernement secteur privé comme le framework le plus productif au Burkina. <a href="http://www.nanaphp.net/news/partenaires/p00" target="_blank"><br>Lire plus...</a></p>
        </div>
    
    	<div class="newsbox">
    		<h4>Formation gratuite nanaPHP</h4>
    		<p>Afin de promouvoir le developpement de nanaPHP, le gouvernement burkinabè initie une série de formations sur sa mise en oeuvre à l'endroit des étudiants des écoles professionnelles. <a href="http://www.nanaphp.net/news/partenaires/p01" target="_blank">Lire plus...</a></p>
    	</div>
    
    	<div class="more_button"><a href="http://www.nanaphp.net/news" target="_blank">Lire toute l'actualité...</a></div>
  </div>
  <div id="templatemo_light_blue_row">
    	<div class="templatemo_gallery">
	      <h1>Projets futures</h1>
          	<div class="gp_row">
	            <img src="<?=$_APP['_PUBLIC_PATH_']?>images/ico/logo.PNG" alt="image" />

                <p>Développement d'un assistant de génération de modèle et de scripts sql, avec la possibilité de d'installer du même coup la base de données!<br> <a href="http://www.nanaphp.net/Projets/Ide/f03" target="_blank">Lire plus...</a>                </p>
		  </div>
            <div class="gp_row">
              <img src="<?=$_APP['_PUBLIC_PATH_']?>images/ico/logo.PNG" alt="image" />
                <p>Finalisation et mise en ligne du site web de nanaPHP! <a href="http://www.nanaphp.net/Projets/management/m01" target="_blank">Lire plus...<br><br><br></a>                </p>
		  </div>
            <div class="gp_row">
                <img src="<?=$_APP['_PUBLIC_PATH_']?>images/ico/logo.PNG" alt="image" />
                <p>
                	Développement d'un assistant de génération de formulaire web professionnel. <a href="http://www.nanaphp.net/Projets/Ide/f04" target="_blank">Lire plus...</a><br><br><br>
                </p>
			</div>
            <div class="more_button"><a href="href="http://www.nanaphp.net/Projets" target="_blank"">Voir tous les projets...</a></div>
        </div>
		<div class="templatemo_partners">
	      <h1>Nouveaux partenaires</h1>
          	<div class="gp_row">
                <img src="<?=$_APP['_PUBLIC_PATH_']?>images/partenaires/coris.jpg" alt="image" width="150" height="70"/>
                <p>
                	Coris bank international vient d'apparter un soutien financier et matériel a nanaPHP. Il devient ainsi un partenaire clé pour le développement future du framework Burkinabè... 
                	<a href="http://www.nanaphp.net/Partenaires/nouveau/p10" target="_blank">Lire plus...</a>
                </p>
			</div>
            <div class="gp_row">
                <img src="<?=$_APP['_PUBLIC_PATH_']?>images/partenaires/mena.png" alt="image" width="150" height="70"/>
                <p>
                	Le Minstère de la Jeunesse et de la Formation professionnelle a prit l'engagement de soutenir les formations à l'initiation de nanaPHP dans les écoles de formations techniques...
                	<a href="http://www.nanaphp.net/Partenaires/nouveau/p11" target="_blank">Lire plus...</a>
                </p>
			</div>
            <div class="gp_row">
                <img src="<?=$_APP['_PUBLIC_PATH_']?>images/partenaires/unesco.png" alt="image" width="150" height="70" />
                <p>
                	L'Organisation des Nations Unies pour l'éducation, la science et la culture vient en soutien à nanaPHP par l'octroi d'équipements informatiques...
                    <a href="http://www.nanaphp.net/Partenaires/nouveau/p12" target="_blank">Lire plus...</a>
                </p>
			</div>
            <div class="more_button"><a href="http://www.nanaphp.net/Partenaires/" target="_blank">Voir tous les partenaires...</a></div>
        </div>
    </div>

	<div id="templatemo_footer">
        <a href="<?=$_APP['_PATH_']?>">Accueil</a> | 
		<a href="<?=$_APP['_PATH_']?>Projet">Projet</a> | 
        <a href="<?=$_APP['_PATH_']?>Contact">Contact</a> |
		<a href="http://www.nanaphp.net/Solutions" target="_blank">Solutions</a> | 
		<a href="http://www.nanaphp.net/Forum" target="_blank">Forum</a> | 
		<a href="http://www.nanaphp.net/Faq" target="_blank">Faq</a><br />
        Copyright © nanaPHP 2015 <a href="http://www.nanaphp.net" target="_blank"><strong>nanaPHP - Your future Web Apps IDE</strong></a>       
	</div>
</div>
<!--script src="<?=$_APP['_PUBLIC_PATH_']?>lib/js/jquery.js"></script>
<script src="<?=$_APP['_PUBLIC_PATH_']?>lib/js/bootstrap.min.js"></script-->
</body>
</html>