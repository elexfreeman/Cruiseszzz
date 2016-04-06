

<div class="w-clearfix ships-container">
<?php
//выводим плитку кораблиов
$ships=$this->GetShipsList();
foreach ($ships as $ship)
{
    ?>
    <div class="ship-item click" onclick="ZAdmin.ShipEdit(<?php echo $ship->id;?>)">
        <div class="s-title"><?php echo $ship->title; ?></div>
        <div class="s-photo"><img src="<?php echo $ship->TV['t_title_img'];  ?>"  class="s-img"></div>
    </div>
    <?php
}
?>


</div>
<!--
<div class="w-form z_form_edit">
    <div class="w-clearfix ship-description">
    <div class="btn-close click" onclick="ZAdmin.HideModal();">X</div>
    <div class="w-form ship-modal">


    </div>
</div>
</div>
-->
<!-- Modal -->
<div class="modal fade" id="ZAdminEditModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content ship-modal">

        </div>
    </div>
</div>