<?php

class kilpailun_sarja_controller extends BaseController {

    public static function store($kilpailutunnus, $alemmat_painoluokat, $ylemmat_painoluokat) {


        $kilpailun_sarjat[] = self::luoSarjat($alemmat_painoluokat, $kilpailutunnus, 'Keltainen/oranssi');
        $kilpailun_sarjat[] = self::luoSarjat($ylemmat_painoluokat, $kilpailutunnus, 'Vihrea/Sininen/Ruskea/Musta');


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

        $errors = $sarjan_osallistuja->validate_ilmoittautuminen();

        if (count($errors) == 0) {
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
                'vyoarvo' => 'Keltainen/Oranssi',
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
//        Kint::dump($poistettava_sarja);
//        View::make('/');
        $poistettava_sarja->destroy();


        Redirect::to('/kilpailun_sivu/' . $kilpailutunnus . '/muokkaa', array('message' => 'Painoluokka poistettu kilpailusta!'));
    }

    public static function add($kilpailutunnus) {

        $params = $_POST;
        
        Kint::dump($params);
        View::make('/');
//        $lisattava_sarja = new kilpailun_sarja(array(
//            'kilpailutunnus' => $kilpailutunnus,
//            'painoluokka' => $params['painoluokka'],
//            'vyoarvo' => $params['vyoarvo']
//        ));
    }

}
