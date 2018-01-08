<link href="<?=$_APP['_PUBLIC_PATH_']?>Datable/style.css" charset="utf-8" rel="stylesheet" type="text/css" />

<style type="text/css" title="currentStyle"  charset="utf-8">
    @import "<?=$_APP['_PUBLIC_PATH_']?>libs/Datable/css/demo_page.css";
    @import "<?=$_APP['_PUBLIC_PATH_']?>libs/Datable/css/demo_table_jui.css";
    @import "<?=$_APP['_PUBLIC_PATH_']?>libs/Datable/css/jquery-ui-1.8.4.custom.css";
    @import "<?=$_APP['_PUBLIC_PATH_']?>libs/Datable/css/highlight.css";
    @import "<?=$_APP['_PUBLIC_PATH_']?>libs/Datable/css/TableTools_JUI.css";
    table a{ text-decoration:none; }
</style>


<script type="text/javascript" charset="utf-8">
    //<![CDATA[
    (function($){
        $(document).ready(function(){
            $(".addRow").btnAddRow();
            $(".delRow").btnDelRow();
    });
    })(jQuery);  
    //]]>
</script>

<script type="text/javascript" charset="utf-8" src="<?=$_APP['_PUBLIC_PATH_']?>libs/Datable/js/jquery.js"></script>
<script type="text/javascript" charset="utf-8" src="<?=$_APP['_PUBLIC_PATH_']?>libs/Datable/js/jquery.dataTables.min.js"></script>	
<script type="text/javascript" charset="utf-8">
 $(document).ready(function() {
            oTable = $('#example').dataTable({
                "sPaginationType": "full_numbers",
                "sScrollY":320,
                "aLengthMenu": [[5, 10, 25, 50, 100, -1], ["5", "10", "25", "50", "100", "All"]],
                "oLanguage": { "sProcessing":   "Traitement en cours...",
                    "sLengthMenu":   "Afficher _MENU_ &eacute;l&eacute;ments",
                    "sZeroRecords":  "Aucun &eacute;l&eacute;ment &agrave; afficher",
                    "sInfo": "Affichage de l'&eacute;l&eacute;ment _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
                    "sInfoEmpty": "La liste est vide ...",
                    "sInfoFiltered": "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
                    "sInfoPostFix":  "",
                    "sSearch":       "Rechercher:",
                    "sUrl":          "",
                    "sLoadingRecords": "Chargement de la liste en cours...",
                    "sZeroRecords":    "Aucun &eacute;l&eacute;ment &agrave; afficher",
                    "sEmptyTable":     "Aucune donn&eacute;e disponible pour affichage...",
                    "oPaginate": {
                        "sFirst":    "|<=",
                        "sPrevious": "<=",
                        "sNext":     "=>",
                        "sLast":     "=>|",
                   }
            },
        "bJQueryUI": true
            });
        } );
</script>  

<style>
.label
{
    font-weight:bold;	
}

.requireLabel{ font-size:14px; color:#F00; font-weight:bold; }
.requireL{ font-size:14px; color:#009; font-weight:bold; }
</style>


        <?php if(array_key_exists('erreur', $_APP)): ?>
            <?= $_APP['erreur']; ?>
          <?php  endif; ?>
           <?php if(array_key_exists('succes', $_APP)): ?>
            <?= $_APP['succes']; ?>
          <?php  endif; ?>
            <?php if(array_key_exists('info_error', $_APP)): ?>
            <?= $_APP['info_error']; ?>
          <?php endif; ?> 



    <label style="font-style:italic; font-size:30px; height:50px;" > Liste des dates de tirage : </label> 
<br/><br/>
   
    <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
    <thead>
        <tr class="odd gradeX">
            <th>N∞</th>
            <th>Libelle</th>
            <th>Date Debut</th>
            <th>Date Fin</th>
            <th>Date Tirage</th>
            <th>Etat</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php if(isset($_APP['dateT'])): ?>
        <?php  $i = 0;  foreach($_APP['dateT'] as $value): ?>           
                <?php     
            $i = $i + 1;?>                
            <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo $value['libelle']; ?></td>
                <td><?php echo $value['dateDebut'];?></td>
                <td><?php echo $value['dateFin'];?></td>
                <td><?php echo $value['dateTirage'];?></td>
                <td><?php if($value['etat'] == 0)
                    echo 'A Venir';
                  elseif($value['etat'] == 1) {
                    echo 'Effectue';
                        }
                    ?></td>
                <td style="color:#000066; font-size:16px; font-weight:bold;">
                    
                    <?php if($value['etat'] == 1):?>
                        <a href="#">
                            <img src="<?=$_APP['_PUBLIC_PATH_']?>images/b_edit.PNG" alt="Modifier" title="Modifier date"/>
                        </a>
                    <?php elseif($value['etat'] == 0):?>
                        <a href="<?=$_APP['_PATH_']?>Tirage/updateDate/<?php echo $value['id'];?>">
                            <img src="<?=$_APP['_PUBLIC_PATH_']?>images/b_edit.PNG" alt="Modifier" title="Modifier date"/>
                        </a>
                    <?php endif; ?>                    
                    
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <?php if($value['etat'] == 1):?>
                        <a href="#">
                            <img src="<?=$_APP['_PUBLIC_PATH_']?>images/b_tirage.PNG" alt="Lancer" title="Lancer tirage"/>
                        </a>
                    <?php elseif($value['etat'] == 0):?>
                        <a href="<?=$_APP['_PATH_']?>Tirage/auth_responsable/<?php echo $value['id'];?>">
                            <img src="<?=$_APP['_PUBLIC_PATH_']?>images/b_tirage.PNG" alt="Lancer" title="Lancer tirage"/>
                        </a>                        
                    <?php endif; ?>
					
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="<?=$_APP['_PATH_']?>Stat/downloaderGagnants/<?php echo $value['id'];?>">
                        <img src="<?=$_APP['_PUBLIC_PATH_']?>images/b_download.PNG" alt="TÈlÈcharger" title="TÈlÈcharger gagnants"/>
                    </a>
                        
                </td>
            </tr>
        <?php endforeach;?>
    <?php  endif; ?>
            
    </tbody>
        <tfoot>
            <tr>
                <th colspan="4">&nbsp;</th>
            </tr>
        </tfoot>
    </table>        

<link type="text/css" href="<?=$_APP['_PUBLIC_PATH_']?>style/css/ui-lightness/jquery-ui-1.7.2.custom.css" rel="stylesheet" />

<script src="<?=$_APP['_PUBLIC_PATH_']?>libs/Datable/js/jquery-ui.js"></script>
<script src="<?=$_APP['_PUBLIC_PATH_']?>libs/Datable/js/timepicker.css"></script>
<script src="<?=$_APP['_PUBLIC_PATH_']?>libs/Datable/js/timepicker.js"></script>
<script type="text/javascript">
        $(function() 
         {
                $( "#dateD" ).datepicker({dateFormat:"yy-mm-dd"});
                $( "#dateF" ).datepicker({dateFormat:"yy-mm-dd"});	

                 $.datepicker.regional['fr'] = {
                                closeText: 'Fermer',
                                prevText: 'Pr√©c√©dent',
                                nextText: 'Suivant',
                                currentText: 'Aujourd\'hui',
                                monthNames: ['Janvier', 'F√©vrier', 'Mars', 'Avril', 'Mai', 'Juin',
                                                'Juillet', 'Ao√ªt', 'Septembre', 'Octobre', 'Nnovembre', 'D√©cembre'],
                                monthNamesShort: ['Janv.', 'F√©vr.', 'Mars', 'Avril', 'Mai', 'Juin',
                                                'Juil.', 'Ao√ªt', 'Sept.', 'Oct.', 'Nov.', 'D√©c.'],
                                dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
                                dayNamesShort: ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'],
                                dayNamesMin: ['D','L','M','M','J','V','S'],
                                weekHeader: 'Sem.',
                                dateFormat: 'yy/mm/dd',
                                firstDay: 1,
                                isRTL: false,
                                showMonthAfterYear: false,
                                yearSuffix: ''};
                        $.datepicker.setDefaults($.datepicker.regional['fr']);

         });

</script>
