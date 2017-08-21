<?php

class Kilpailu_controller extends BaseController {

    public static function index() {
        $kilpailut = Kilpailu::all();

        Kint::dump($kilpailut);

        View::make('Kilpailu/tulossa_olevat_kilpailut.html', array('kilpailut' => $kilpailut));
    }

    public static function store() {
        $params = $_POST;

        $kilpailu = new Kilpailu(array(
            'kilpailun_nimi' => $params['kilpailun_nimi'],
            'kilpailupaikka' => $params['kilpailupaikka'],
            'ajankohta' => $params['kilpailun_paiva'] . ' ' . $params['kilpailun_kellonaika'] . ':00',
            'kilpailun_kuvaus' => $params['kilpailun_kuvaus']
        ));


        $alemmat_painoluokat = array();
        $ylemmat_painoluokat = array();

        $alemmat_painoluokat = $params['painoluokat_alemmat'];
        $ylemmat_painoluokat = $params['painoluokat_ylemmat'];

        $id = $kilpailu->save();
        $kilpailun_sarjat = Kilpailun_sarja_controller::store($id, $alemmat_painoluokat, $ylemmat_painoluokat);
        Kint::dump($id);
        Kint::dump($kilpailun_sarjat);
//
//        Redirect::to('/' . $kilpailu->ktunnus, array('message' => 'Kilpailu ja sen painoluokat luotu!'));
    }

    public static function uusi() {
        View::make('Kilpailu/uusi_kilpailu.html');
    }

}
