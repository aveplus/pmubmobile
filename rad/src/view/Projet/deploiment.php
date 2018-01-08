<style>
	label { font-weight:bold; }
	.inputfield {
		width:450px;
		height: 20px;
		color:#333;
		border: 1px solid #686f76;	
	}
	em {
		font-weight:normal;
		color:#666666;
		font-size:11px;
	}
</style>
<div class="templatemo_fullgraybox" >
    <h1>Déploiement du projet "<?=$_APP['_PROJET_']?>": Etape 1/2</h1>
    <hr />
    <div>
   		<div id="templatemo_login">
        	<form method="post" action="<?=$_APP['_PATH_'].'Projet/deploiment/'.$_APP['_PROJET_']?>">
 				<label>
                    Nom du dossier/sous dossiers qui va/vont contenir le projet dans le documentRoot<br />
                    <em>Par exemple gestionScolaire, ou gestionScolaire/GestionEleve ou encore 
                    gestionScolaire/GestionEleve/GestionInscription.</em><br />
                    <span style="color:#F00">NB: Laisser ce champ vide si votre projet doit être directement dans le documentRoot de votre site web. Pour connaître votre documentRoot exécuter ce script sur le serveur: echo $_SERVER['DOCUMENT_ROOT'];<br />
                    En cas de problème éditez le fichier  "deploiments/<?=$_APP['_PROJET_'].'/src/config/prod.xxx'?>"</span>
                </label>
                <br />
                <input class="inputfield" name="PATH" type="text" id="path" placeholder="Saisissez le nom du dossier de votre projet dans documentRoot"/>
                <input class="button" type="submit" name="Submit" value="Terminer le déploiment"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	 
                <a href="<?=$_APP['_PATH_']?>Projet">&lt;&lt;Retour à la liste</a>
            </form>
        </div>
    </div>
</div>	