<?php

namespace Concrete\Package\CommunityStoreEpayStandard;

use Package;
use Route;
use Whoops\Exception\ErrorException;
use \Concrete\Package\CommunityStore\Src\CommunityStore\Payment\Method as PaymentMethod;

class Controller extends Package
{
    protected $pkgHandle = 'community_store_epay_standard';
    protected $appVersionRequired = '5.7.2';
    protected $pkgVersion = '1.0';

    public function getPackageDescription()
    {
        return t("Epay Standard Payment Method for Community Store");
    }

    public function getPackageName()
    {
        return t("Epay Payment Method");
    }

    public function install()
    {
        $installed = Package::getInstalledHandles();
        if(!(is_array($installed) && in_array('community_store',$installed)) ) {
            throw new ErrorException(t('This package requires that Community Store be installed'));
        } else {
            $pkg = parent::install();
            $pm = new PaymentMethod();
            $pm->add('community_store_epay_standard','Epay Standard',$pkg);
        }

    }
    public function uninstall()
    {
        $pm = PaymentMethod::getByHandle('community_store_epay_standard');
        if ($pm) {
            $pm->delete();
        }
        $pkg = parent::uninstall();
    }

    public function on_start() {
        Route::register('/checkout/epayresponse','\Concrete\Package\CommunityStoreEpayStandard\Src\CommunityStore\Payment\Methods\CommunityStoreEpayStandard\CommunityStoreEpayStandardPaymentMethod::validateCompletion');
    }
}
?>