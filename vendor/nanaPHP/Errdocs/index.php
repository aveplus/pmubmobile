<!--HEADER-->
<!DOCTYPE html>
<html lang="<?=$this->lang?>">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="">
		<meta name="author" content="">
		<title><?=$this->title?></title>

		<style>
			#erreur
			{
			margin-top:2px;
			margin-bottom:15px;
			border-radius: 5px;
			box-shadow: 0px 0px 10px #FF6666;
			border: 1px solid  #FF6666;
				padding:10px;
				padding-top:5px;
				background-color:#FCC;
				color: #000;
				width:99%;
				float:left;
				font-size:18px;
				line-height:30px;
				text-align:left;
			}
			
			#succes
			{
			margin-top:5px;
			border-radius: 5px;
			box-shadow: 0px 0px 10px #009900;
			border: 1px solid  #009900;
				padding-left:5px;
				background-color: #009900;
				color: #FFFFFF;
				font-size:14px;
				width:99%;
				float:left
			}
        </style>
	</head><!--/head-->
	<body class="homepage">
        <div style="margin-left:40px; margin-right:40px; text-align:justify; margin-top:10px; margin-bottom:10px">
        <h1 style="color:#0099FF">Bienvenue dans nanaPHP <span style="color:#F00">Error</span> documentations...</h1>
        <hr>
        <h3>Origine du problème</h3>
        <p style="font-size:18px; line-height:30px">
        D'abord il faut noter que cela survient quand une erreur se produit dans vos codes, généralement une exception.
        Si le framework tente de l'attrapper alors que vous n'avez pas de controller <span style="color:#0066FF">ControllerApp</span> muni de la 
        méthode <span style="color:#0066FF">Err</span> avec paramètre, il se déclenche alors cette erreur que vous voyez.
        <?php
            if(isset($_APP['EXCEPTION_CODE']))
            {
                echo '
                <div id="erreur">
					<h3>Ci-dessous les détails de l\'erreur qui n\'a pas pu être attrapée par votre code...</h3><hr>
                    <strong>Code:</strong> '.$_APP['EXCEPTION_CODE'].'<br>
                    <strong>Message:</strong> '.$_APP['EXCEPTION_MESSAGE'].'<br><br>
					<strong>Description détallée de l\'erreur:<br></strong> '.$_APP['EXCEPTION_TRACE'].'
                </div>';
            }
        ?>
        <br><br>
        Dans votre dossiers <span style="color:#0066FF">src/controller</span> (Prod), ou bien, si vous êtes en mode Dev, 
        dans <span style="color:#0066FF">projects/nom_de_votre_projet/controller</span>, il doit y avoir un contrôleur nommé <span style="color:#0066FF">ControllerApp</span>.
        Ce contrôleur dispose d'un méthode <span style="color:#0066FF">Err($param)</span> que vous devez implémenter pour resoudre ce problème.
        <br><br>
        <strong>NB:</strong> La méthode <span style="color:#0066FF">Err</span> dispose d'un paramètre qui est obligatoire, ce paramètre n'est rien 
        d'autre que le code de l'erreur.
        </p>
        <hr>
        <em style="color:#0099FF">By nanaPHP Team</em>
        </div>
	</body>
</html>