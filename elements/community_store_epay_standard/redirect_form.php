<?php defined('C5_EXECUTE') or die(_("Access Denied."));
extract($vars);
// Here we're setting up the form we're going to submit to epay.
// This form will automatically submit itself 
?>
<input type="hidden" name="merchantnumber" value="<?php echo $merchantNumber?>">
<input type="hidden" name="amount" value="<?= $total + 0?>"> <input type="hidden" name="currency" value="<?php echo $currencyCode?>">
<input type="hidden" name="orderid" value="<?= $orderID?>">
<input type="hidden" name="callbackurl" value="<?= $notifyURL?>">
<input type="hidden" name="accepturl" value="<?= $returnURL?>">
<input type="hidden" name="cancelurl" value="<?= $cancelReturn?>">
<input type="hidden" name="windowstate" value="3"> <input type="submit" value=
"Go to payment">


