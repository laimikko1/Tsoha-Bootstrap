<?php

class LoginController extends BaseController {

    public static function login() {
        View::make('Suunnitelma/kirjautumissivu.html');
    }

    public static function handle_login() {
        $params = $_POST;

        $kilpailija = Kilpailija::authenticate($params['kayttajanimi'], $params['salasana']);

        if (!$kilpailija) {
            View::make('Suunnitelma/kirjautumissivu.html', array('error' => 'Väärä käyttäjätunnus tai salasana!', 'kayttajanimi' => $params['kayttajanimḯ']));
        } else {
            $_SESSION['kilpailija'] = $kilpailija->ktunnus;

            Redirect::to('/', array('message' => 'Tervetuloa takaisin! ' . $kilpailija->nimi . '!'));
        }
    }

}
