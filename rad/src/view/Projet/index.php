<style>
	#projectListe {
		line-height:30px;
	}
	
	#projectListe tr th{
		font-weight:bold;
		text-align:left;
	}
	
	#projectListe td{
		border-top: 1px solid #686f76;	
	}
	#projectListe img{
		border:hidden;
		width:30px;
		height:30px;	
	}
</style> 
     
  <div class="templatemo_fullgraybox">
        <table width="100%">
        	<tr>
            	<td><h1>Liste des projets </h1></td>
        		<td align="right"><div class="more_button"><a style="font-weight:normal" href="<?=$_APP['_PATH_']?>Projet/nouveau">Créer un nouveau projet</a></div></td>
            </tr>
        </table>
        <hr />
        <!--p><img src="<?=$_APP['_PUBLIC_PATH_']?>images/image_200x200.gif" alt="project" /-->
        <table id="projectListe" width="100%">
        	
			<?php
				$cpt = 1;
				if(count($_APP['_LISTE_PROJET_'])==0)
					echo '<tr><td colspan="3" style="color:#FF0000">
								<strong>nanaPHP</strong> ne contient aucun projet actuellement... 
						  </td></tr>';
            	else
				{
					echo '<tr>
							<th width="20%">Ordre</th>
							<th width="40%">Nom du projet</th>
							<th width="40%">Actions</th>
						</tr>';
					foreach($_APP['_LISTE_PROJET_'] as $projet)
					{
						echo '
							<tr>
								<td>'.($cpt++).'</td>
								<td>'.$projet['name'].'</td>
								<td>
									<a href="'.$_APP['_PATH_'].'Projet/contenu/'.str_replace("/","SLACH",str_replace(" ","+",$projet['dir'])).'" title="Afficher le contenu du projet...">
										<img src="'.$_APP['_PUBLIC_PATH_'].'images/bouton/zoom.png" alt="Zoom"/>
									</a>
									
									<a target="_blank" href="'.$_APP['_PATH_'].'Projet/execute/'.$projet['name'].'" title="Exécuter ce projet...">
										<img src="'.$_APP['_PUBLIC_PATH_'].'images/bouton/exe.png" alt="Run"/>
									</a>
									
									<a href="'.$_APP['_PATH_'].'Projet/deploiment/'.$projet['name'].'" title="Déployer ce projet...">
										<img src="'.$_APP['_PUBLIC_PATH_'].'images/bouton/deployer.png" alt="Déployer"/>
									</a>
								</td>
							</tr>';
					}
				}
			?>
        </table>
        <?php if(array_key_exists('_FLASH_',$_APP)) echo $_APP['_FLASH_']; ?>
  </div>	