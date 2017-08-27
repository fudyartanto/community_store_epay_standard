<?php defined('C5_EXECUTE') or die(_("Access Denied."));
extract($vars);
// Here we're setting up the form we're going to submit to epay.
// This form will automatically submit itself 
?>
<input name="merchantnumber" value="<?php echo $merchantNumber?>">
<input name="amount" value="<?= $total + 0?>"> <input name="currency" value="<?php echo $currencyCode?>">
<input name="orderid" value="<?= $orderID?>">
<input name="callbackurl" value="<?= $notifyURL?>">
<input name="accepturl" value="<?= $returnURL?>">
<input name="cancelurl" value="<?= $cancelReturn?>">
<input name="windowstate" value="3"> <input type="submit" value=
"Go to payment">


