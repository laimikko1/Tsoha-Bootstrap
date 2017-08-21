<?php

class Kilpailija_controller extends BaseController {

    public static function index() {
        self::check_logged_in();

        $kilpailijat = Kilpailija::all();

        Kint::dump($kilpailijat);

        View::make('Suunnitelma/yllapitajan_sivu.html', array('kilpailijat' => $kilpailijat));
    }

    public static function store() {
        self::check_logged_in();

        $params = $_POST;

        $attributes = array(
            'nimi' => $params['nimi'],
            'kayttajanimi' => $params['kayttajanimi'],
            'salasana' => $params['salasana'],
            'paaaine' => $params['paaaine']
        );

        $kilpailija = new Kilpailija($attributes);
        $errors = $kilpailija->errors();

        if (count($errors) == 0) {
            $kilpailija->save();
            $_SESSION['kilpailija'] = $kilpailija->ktunnus;
            Redirect::to('/', array('message' => 'Tunnus luotu!'));
        } else {
            View::make('Kilpailija/rekisteroityminen.html', array('errors' => $errors, 'attributes' => $attributes));
        }
    }

    public static function edit($ktunnus) {
        self::check_logged_in();


        $kilpailija = Kilpailija::find($ktunnus);
        if (empty($_SESSION['kilpailija'])) {
            Redirect::to('/');
        }
        if ($_SESSION['kilpailija'] == $kilpailija->ktunnus) {
            View::make('Kilpailija/kayttajan_sivu.html', array('attributes' => $kilpailija));
        } else {
            Redirect::to('/');
        }
    }

    public static function update($ktunnus) {
        self::check_logged_in();
 
       $params = $_POST;

        $attributes = array(
            'ktunnus' => $ktunnus,
            'nimi' => $params['nimi'],
            'kayttajanimi' => $params['kayttajanimi'],
            'salasana' => $params['salasana'],
            'paaaine' => $params['paaaine']
        );

        $kilpailija = new Kilpailija($attributes);
        $errors = $kilpailija->errors();




        if (count($errors) > 0) {
            View::make('Kilpailija/kayttajan_sivu.html', array('errors' => $errors, 'attributes' => $attributes));
        } else {
            $kilpailija->update();

            Redirect::to('/kayttajan_sivu/' . $kilpailija->ktunnus, array('message' => 'Tietoja päivitetty onnistuneesti!'));
        }
    }

    public static function destroy($ktunnus) {
        $kilpailija = new Kilpailija(array('ktunnus' => $ktunnus));
        $kilpailija->destroy();

        Redirect::to('/rekisteroityminen', array('message' => 'Tunnus poistettu onnistuneesti!'));
    }

}
