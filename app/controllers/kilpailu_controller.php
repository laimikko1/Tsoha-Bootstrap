<?php

class kilpailu_controller extends BaseController {

    public static function showKilpailunSivu($kilpailutunnus) {
        $kilpailu = Kilpailu::find($kilpailutunnus);
        $kilpailun_painoluokat = Kilpailun_sarja::findAll($kilpailutunnus);

        View::make('Kilpailu/kilpailun_sivu.html', array('kilpailu' => $kilpailu, 'kilpailunp' => $kilpailun_painoluokat));
    }

    public static function showIlmoittautuminen($kilpailutunnus) {
        self::check_logged_in();

        $kilpailu = Kilpailu::find($kilpailutunnus);
        $kilpailun_sarjat = Kilpailun_sarja::findAll($kilpailutunnus);

        Kint::dump($kilpailu);
        Kint::dump($kilpailun_sarjat);

        View::make('Kilpailu/kilpailun_ilmoittautumislomake.html', array('kilpailu' => $kilpailu, 'kilpailun_sarjat' => $kilpailun_sarjat));
    }

  


}
