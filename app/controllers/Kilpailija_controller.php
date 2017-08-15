<?php

class Kilpailija_controller extends BaseController {

    public static function index() {
        $kilpailijat = Kilpailija::all();

        Kint::dump($kilpailijat);

        View::make('Kilpailija/yllapitajan_sivu.html', array('kilpailijat' => $kilpailijat));
    }

    public static function show($ktunnus) {
        
        $kilpailija = Kilpailija::find($ktunnus);
        Kint::dump($kilpailija);
        
        Kint::trace();

        View::make('Kilpailija/kayttajan_sivu.html', array('kilpailija' => $kilpailija));
    }
    

    public static function store() {
        $params = $_POST;

        $kilpailija = new Kilpailija(array(
            'nimi' => $params['nimi'],
            'kayttajanimi' => $params['kayttajanimi'],
            'salasana' => $params['salasana'],
            'paaaine' => $params['paaaine']
        ));

        Kint::dump($params);


        $kilpailija->save();

        Redirect::to('/kayttajan_sivu/' . $kilpailija->ktunnus, array('message' => 'Tunnus luotu!'));
    }

}
