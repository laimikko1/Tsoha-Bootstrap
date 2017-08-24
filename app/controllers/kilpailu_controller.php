<?php

class kilpailu_controller extends BaseController {

    public static function showKilpailunSivu($kilpailutunnus) {
        $kilpailu = kilpailu::find($kilpailutunnus);
        $kilpailun_painoluokat = kilpailun_sarja::findAll($kilpailutunnus);

        Kint::dump($kilpailun_painoluokat);
        Kint::dump($kilpailu);

        View::make('Kilpailu/kilpailun_sivu.html', array('kilpailu' => $kilpailu, 'kilpailunp' => $kilpailun_painoluokat));
    }

    public static function showIlmoittautuminen($kilpailutunnus) {
        self::check_logged_in();

        $kilpailu = kilpailu::find($kilpailutunnus);
        $kilpailun_sarjat = kilpailun_sarja::findAll($kilpailutunnus);

        Kint::dump($kilpailu);
        Kint::dump($kilpailun_sarjat);

        View::make('Kilpailu/kilpailun_ilmoittautumislomake.html', array('kilpailu' => $kilpailu, 'kilpailun_sarjat' => $kilpailun_sarjat));
    }

  


}
