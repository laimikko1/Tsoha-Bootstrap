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

            $kilpailun_sarjat[]  = $kilpailun_sarja;
        }
//        foreach ($kilpailun_sarja as $kilpailun_sarja) {
//            $kilpailun_sarja->save();
//        }

        Kint::dump($kilpailun_sarjat);
    }

}
