<?php

class Kilpailu_controller extends BaseController {

    public static function index() {
        $kilpailut = Kilpailu::all();

        Kint::dump($kilpailut);

        View::make('kilpailujen_tulokset.html', array('kilpailut' => $kilpailut));
    }

    public static function store() {
        $params = $_POST;

        $kilpailu = new Kilpailu(array(
            'kilpailun_nimi' => $params['kilpailun_nimi'],
            'kilpailupaikka' => $params['kilpailupaikka'],
            'ajankohta' => $params['ajankohta'],
            'kilpailun_kuvaus' => $params['kilpailun_kuvaus']
        ));

        Kint::dump($params);

        $kilpailu->save();

        Redirect::to('/kilpailun_sivu/' . $kilpailu->ktunnus, array('message' => 'Kilpailu luotu!'));
    }

}
