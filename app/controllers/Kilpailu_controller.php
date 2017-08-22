<?php

class Kilpailu_controller extends BaseController {

    public static function index() {
        $kilpailut = Kilpailu::all();

        Kint::dump($kilpailut);

        View::make('Kilpailu/tulossa_olevat_kilpailut.html', array('kilpailut' => $kilpailut));
    }

    public static function show($kilpailutunnus) {
        $kilpailu = Kilpailu::find($kilpailutunnus);
        $kilpailun_painoluokat = Kilpailun_sarja::findAll($kilpailutunnus);

        Kint::dump($kilpailun_painoluokat);
        Kint::dump($kilpailu);

        View::make('Kilpailu/kilpailun_sivu.html', array('kilpailu' => $kilpailu, 'kilpailunp' => $kilpailun_painoluokat));
    }

    public static function ilmoittaudu($kilpailutunnus) {
        $kilpailu = Kilpailu::find($kilpailutunnus);
        $kilpailun_sarjat = Kilpailun_sarja::findAll($kilpailutunnus);

        Kint::dump($kilpailu);
        Kint::dump($kilpailun_sarjat);

        View::make('Kilpailu/kilpailun_ilmoittautumislomake.html', array('kilpailu' => $kilpailu, 'kilpailun_sarjat' => $kilpailun_sarjat));
    }

    public static function store() {
        $params = $_POST;

        $kilpailu = new Kilpailu(array(
            'kilpailun_nimi' => $params['kilpailun_nimi'],
            'kilpailupaikka' => $params['kilpailupaikka'],
            'ajankohta' => $params['kilpailun_paiva'] . ' ' . $params['kilpailun_kellonaika'] . ':00',
            'kilpailun_kuvaus' => $params['kilpailun_kuvaus']
        ));

        Kint::dump($kilpailu);

        $errors = $kilpailu->errors();

        if (empty($params['painoluokat_alemmat']) && empty($params['painoluokat_ylemmat'])) {
            $errors[] = 'Valitse ainakin yksi painoluokka kilpailuun!';
        } else {
            $alemmat_painoluokat = $params['painoluokat_alemmat'];
            $ylemmat_painoluokat = $params['painoluokat_ylemmat'];
        }

        if (count($errors) == 0) {
            $id = $kilpailu->save();
            Kilpailun_sarja_controller::store($id, $alemmat_painoluokat, $ylemmat_painoluokat);

            Redirect::to('Kilpailu/kilpailun_sivu.html' . $kilpailu->ktunnus, array('message' => 'Kilpailu ja sen painoluokat luotu!'));
        } else {
            View::make('Kilpailu/uusi_kilpailu.html', array('errors' => $errors, 'attributes' => $kilpailu));
        }
    }

    public static function uusi() {
        View::make('Kilpailu/uusi_kilpailu.html');
    }

}
