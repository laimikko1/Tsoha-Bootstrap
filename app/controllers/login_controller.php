<?php

class login_controller extends BaseController {

    public static function login() {
        View::make('Suunnitelma/kirjautumissivu.html');
    }

    public static function handle_login() {
        $params = $_POST;
        $k = new Kilpailija(array(
            'kayttajanimi' => $params['kayttajanimi'],
            'salasana' => $params['salasana']
        ));
        $errors = array();
        $errors[] = 'Väärä käyttäjätunnus tai salasana!';

        if (!is_null($k->validate_kayttajanimi()) || !is_null($k->validate_salasana())) {
            View::make('/Suunnitelma/kirjautumissivu.html', array('errors' => $errors, 'kayttajanimi' => $params['kayttajanimi']));
        }

        $kilpailija = Kilpailija::authenticate($params['kayttajanimi'], $params['salasana']);

        if (!$kilpailija) {
            View::make('/Suunnitelma/kirjautumissivu.html', array('errors' => $errors, 'kayttajanimi' => $params['kayttajanimi']));
        } else {
            $_SESSION['kilpailija'] = $kilpailija->ktunnus;


            Redirect::to('/', array('message' => 'Tervetuloa takaisin ' . $kilpailija->nimi . '!'));
        }
    }

    public static function logout() {
        $_SESSION['kilpailija'] = null;
        Redirect::to('/', array('message' => 'Olet kirjautunut ulos!'));
    }

    public static function rekisteroityminen() {
        View::make('Kilpailija/rekisteroityminen.html');
    }

}
