<?php

class kilpailu_controller extends BaseController {

    public static function kilpailut() {
        $kilpailut = Kilpailu::all(0);
        View::make('Kilpailu/tulossa_olevat_kilpailut.html', array('kilpailut' => $kilpailut));
    }

    public static function menneet_Kilpailut() {
        $kilpailut = Kilpailu::all(1);
        View::make('Kilpailu/menneet_kilpailut.html', array('kilpailut' => $kilpailut));
    }

    public static function showKilpailunSivu($kilpailutunnus) {
        $kilpailu = Kilpailu::find($kilpailutunnus);
        $kilpailun_painoluokat = Kilpailun_sarja::findAll($kilpailutunnus);

        View::make('Kilpailu/kilpailun_sivu.html', array('kilpailu' => $kilpailu, 'kilpailunp' => $kilpailun_painoluokat));
    }

    public static function showIlmoittautuminen($kilpailutunnus) {
        self::check_logged_in();
        $errors = array();

        $kilpailu = Kilpailu::find($kilpailutunnus);
        if ($kilpailu->ajankohta < date('Y-m-d h:i:s')) {
            $errors[] = 'Kilpailun ilmoittautuminen on päättynyt!';
            Redirect::to('/kilpailut', array('errors' => $errors));
        }
        $kilpailun_sarjat = Kilpailun_sarja::findAll($kilpailutunnus);
        if (empty($kilpailun_sarjat)) {
            $errors[] = 'Kilpailuun ei ole lisätty vielä sarjoja!';
            Redirect::to('/kilpailut', array('errors' => $errors));
        }

        View::make('Kilpailu/kilpailun_ilmoittautumislomake.html', array('kilpailu' => $kilpailu, 'kilpailun_sarjat' => $kilpailun_sarjat));
    }

    public static function showTulokset($kilpailutunnus) {
        $kilpailu = Kilpailu::find($kilpailutunnus);
        $kilpailun_sarjat = Kilpailun_sarja::findAll($kilpailutunnus);
        foreach ($kilpailun_sarjat as $sarja) {
            self::removeNullResults($sarja);
        }

        View::make('Kilpailu/kilpailun_tulokset.html', array('kilpailu' => $kilpailu, 'kilpailun_sarjat' => $kilpailun_sarjat));
    }

    public static function removeNullResults($sarja) {
        for ($index = 0; $index < count($sarja->sarjan_osallistujat); $index++) {
            $k = new Kilpailija($sarja->sarjan_osallistujat[$index]);
            if ($k->sijoitus == NULL) {
                $poistettavat[] = $index;
            }
        }
        if (!empty($poistettavat)) {
            foreach ($poistettavat as $value) {
                unset($sarja->sarjan_osallistujat[$value]);
            }
        }
    }

}
