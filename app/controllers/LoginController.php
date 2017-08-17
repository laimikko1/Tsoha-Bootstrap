<?php

class LoginController extends BaseController {

    public static function login() {
        View::make('Suunnitelma/kirjautumissivu.html');
    }

    public static function handle_login() {
        $params = $_POST;


        $kilpailija = Kilpailija::authenticate($params['kayttajanimi'], $params['salasana']);

        if (!$kilpailija) {
            View::make('/Suunnitelma/kirjautumissivu.html', array('error' => 'Väärä käyttäjätunnus tai salasana!', 'kayttajanimi' => $params['kayttajanimi']));
        } else {
            $_SESSION['kilpailija'] = $kilpailija->ktunnus;

            Redirect::to('/', array('message' => 'Tervetuloa takaisin ' . $kilpailija->nimi . '!'));
        }
        Kint::dump($kilpailija);
    }

    public static function logOut() {
        unset($_SESSION['kayttajanimi']);
        unset($_SESSION['salasana']);

        session_destroy();
        session_Start();
        Redirect::to('/', array('message' => 'Olet kirjautunut ulos!'));
    }

}
