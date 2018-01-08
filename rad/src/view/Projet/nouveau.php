<script>
	function validerFormulaire(frm)
	{
		var regex = new RegExp(/[^A-Za-z0-9_]/);

		if(regex.test(frm.elements['PROJECT_PATH'].value) || frm.elements['PROJECT_PATH'].value=="")
		{	alert("Saisissez un bon nom de dossier pour le projet SVP!"); 
			frm.elements['PROJECT_PATH'].focus();
			frm.elements['PROJECT_PATH'].select();
			return false;
		} 
		
		if(regex.test(frm.elements['PROJECT_ID'].value) || frm.elements['PROJECT_ID'].value=="")
		{	alert("Saisissez un bon identifiant pour le projet SVP!");
			frm.elements['PROJECT_ID'].focus();
			frm.elements['PROJECT_ID'].select(); 
			return false;
		}

		if(frm.elements['PROJECT_NAME'].value=="")
		{	alert("Saisissez un nom valide pour le projet SVP!");
			frm.elements['PROJECT_NAME'].focus();
			frm.elements['PROJECT_NAME'].select(); 
			return false;
		}				
				
		return confirm('Voulez-vous vraiement enregistrer créer le projet maintenant?');
	}
</script>

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
    <table width="100%">
        <tr>
            <td><h1>Création d'un nouveau projet</h1></td>
            <td align="right"><div class="more_button"><a style="font-weight:normal" href="<?=$_APP['_PATH_']?>Projet">Retour au menu</a></div></td>
        </tr>
    </table>    
    
    
    <hr />
    <div>
   		<div id="templatemo_login">
        	<form method="post" action="<?=$_APP['_PATH_'].'Projet/creer'?>" onsubmit="return validerFormulaire(this);">
                <span style="color:#F00">NB: Le dossier du projet et le nom respecter les règles de nomination des dossiers<br />
                L'ID du projet doit être un mot simple sans caractères accentués et spéciaux...</span><br /><br />
                <label>Nom du dossier projet en un seul mot avec (A-Z,a-z,0-9,_):</label>
                <br />
                <input class="inputfield" name="PROJECT_PATH" type="text" id="PROJECT_PATH" placeholder="Nom du repertoire de votre projet"/>
                <br /><br />
                <label>Id du projet en un mot avec avec (A-Z,a-z,0-9,_):</label>
                <br />
                <input class="inputfield" name="PROJECT_ID" type="text" id="PROJECT_ID" placeholder="L'id de votre projet"/>
                <br /><br />
                <label>Nom du projet(En 1 ou 2 ou 3 mots tout au plus):</label>
                <br />
                <input class="inputfield" name="PROJECT_NAME" type="text" id="PROJECT_NAME" placeholder="Le nom de votre projet"/>
                
                <input class="button" type="submit" name="Submit" value="Créer le projet"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	 
            </form>
        </div>
    </div>
</div>	