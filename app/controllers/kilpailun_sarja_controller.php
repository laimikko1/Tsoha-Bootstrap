<?php

class kilpailun_sarja_controller extends BaseController {

    public static function store($kilpailutunnus, $alemmat_painoluokat, $ylemmat_painoluokat) {

        $alemmat = self::luoSarjat($alemmat_painoluokat, $kilpailutunnus, 'Keltainen/oranssi');
        $ylemmat = self::luoSarjat($ylemmat_painoluokat, $kilpailutunnus, 'Vihrea/Sininen/Ruskea/Musta');
        $kilpailun_sarjat = array_merge($alemmat, $ylemmat);

        foreach ($kilpailun_sarjat as $ksarja) {
            $ksarja->save();
        }


        return $kilpailun_sarjat;
    }

    public static function ilmoittaudu($kilpailutunnus) {
        self::check_logged_in();
        $id = $_SESSION['kilpailija'];
        $errors = array();

        $params = $_POST;

        $attributes = (array(
            'ktunnus' => $id,
            'sarjatunnus' => $params['sarjatunnus']
        ));

        $sarjan_osallistuja = new Sarjan_osallistuja($attributes);
        $errors[] = $sarjan_osallistuja->validate_ilmoittautuminen();

        if ($errors[0] == NULL) {
            $sarjan_osallistuja->save();
            Redirect::to('/', array('message' => 'Ilmoittautuminen kilpailuun lisätty!'));
        } else {
            Redirect::to('/kilpailun_sivu/' . $kilpailutunnus . '/ilmoittautuminen', array('errors' => $errors));
        }
    }

    private static function luoSarjat($painoluokka, $kilpailutunnus, $vyoarvo) {
        foreach ($painoluokka as $painoluokka) {
            $kilpailun_sarja = new Kilpailun_sarja(array(
                'kilpailutunnus' => $kilpailutunnus,
                'vyoarvo' => $vyoarvo,
                'painoluokka' => $painoluokka
            ));
            $kilpailun_sarjat[] = $kilpailun_sarja;
        }
        return $kilpailun_sarjat;
    }

    public static function destroy() {
        $params = $_POST;
        $sarjatunnus = $params['sarjatunnus'];
        $kilpailutunnus = $params['kilpailutunnus'];
        $poistettava_sarja = new Kilpailun_sarja(array('sarjatunnus' => $sarjatunnus));
        $poistettava_sarja->destroy();


        Redirect::to('/kilpailun_sivu/' . $kilpailutunnus . '/muokkaa', array('message' => 'Painoluokka poistettu kilpailusta!'));
    }

    public static function add($kilpailutunnus) {
        $params = $_POST;
        $lisattava_sarja = new Kilpailun_sarja(array(
            'kilpailutunnus' => $kilpailutunnus,
            'painoluokka' => $params['painoluokka'],
            'vyoarvo' => $params['vyoarvo']
        ));
        $errors = $lisattava_sarja->errors();
        if (count($errors) > 0) {
            Redirect::to('/kilpailun_sivu/' . $kilpailutunnus . '/muokkaa', array('errors' => $errors));
        }
        $lisattava_sarja->save();
        Redirect::to('/kilpailun_sivu/' . $kilpailutunnus . '/muokkaa', array('message' => 'Sarja lisätty kilpailuun!'));
    }

    public static function destroyIlmoittautuminen($sarjatunnus) {
        $id = $_SESSION['kilpailija'];
        $params = $_POST;
        $osallistuja = new Sarjan_osallistuja(array(
            'ktunnus' => $id,
            'sarjatunnus' => $params['sarjatunnus']
        ));

        $osallistuja->destroyIlmoittautuminen();
        Redirect::to('/kayttajan_sivu/' . $osallistuja->ktunnus, array('message' => 'Ilmoittautuminen peruttu!'));
    }

}
