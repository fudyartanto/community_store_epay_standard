<?php 
defined('C5_EXECUTE') or die(_("Access Denied."));
extract($vars);
?>
<div class="form-group">
    <label><?= t("Epay Merchant Number")?></label>
    <input type="text" name="epayMerchantNumber" value="<?= $epayMerchantNumber?>" class="form-control">
</div>
<div class="form-group">
    <?= $form->label('epayCurrency',t("Currency")); ?>
    <?= $form->select('epayCurrency',$currencies,$epayCurrency?$epayCurrency:'USD');?>
</div>