<?php

class Kilpailun_sarja_controller extends BaseController {

    public static function store($kilpailutunnus, $alemmat_painoluokat, $ylemmat_painoluokat) {
        $alemmatVyot_sarja = array();
        $ylemmatVyot_sarja = array();
        $kilpailun_sarjat = array();


        foreach ($alemmat_painoluokat as $painoluokka) {
            $kilpailun_sarja = new Kilpailun_sarja(array(
                'kilpailutunnus' => $kilpailutunnus,
                'vyoarvo' => 'Keltainen/Oranssi',
                'painoluokka' => $painoluokka
            ));
            $kilpailun_sarjat[] = $kilpailun_sarja;
        }

        foreach ($ylemmat_painoluokat as $painoluokka) {
            $kilpailun_sarja = new Kilpailun_sarja(array(
                'kilpailutunnus' => $kilpailutunnus,
                'vyoarvo' => 'Vihrea/Sininen/Ruskea/Musta',
                'painoluokka' => $painoluokka
            ));

            $kilpailun_sarjat[] = $kilpailun_sarja;
        }



        foreach ($kilpailun_sarjat as $ksarja) {
            $ksarja->save();
        }

        return $kilpailun_sarjat;
    }

    public static function ilmoittaudu($kilpailutunnus) {
        self::check_logged_in();
        $id = $_SESSION['kilpailija'];

        $params = $_POST;

        $attributes = (array(
            'ktunnus' => $id,
            'sarjatunnus' => $params['sarjatunnus']
        ));

        $sarjan_osallistuja = new Sarjan_osallistuja($attributes);

        $errors = $sarjan_osallistuja->errors();

        if (count($errors) == 0) {
            $sarjan_osallistuja->save();
            Redirect::to('/', array('message' => 'Ilmoittautuminen kilpailuun lisätty!'));
        } else {
            Redirect::to('/kilpailun_sivu/' . $kilpailutunnus . '/ilmoittautuminen', array('errors' => $errors));
        }
    }

}
