<?php

class LoginController extends BaseController {



    public static function handle_login() {
        $params = $_POST;

        $kilpailija = Kilpailija::authenticate($params['kayttajanimi'], $params['salasana']);

        if (!$kilpailija) {
            View::make('/Suunnitelma/kirjautumissivu.html', array('error' => 'Väärä käyttäjätunnus tai salasana!', 'kayttajanimi' => $params['kayttajanimi']));
        } else {
            $_SESSION['kilpailija'] = $kilpailija->ktunnus;


            Redirect::to('/', array('message' => 'Tervetuloa takaisin ' . $kilpailija->nimi . '!'));
        }
    }

    public static function logout() {
        $_SESSION['kilpailija'] = null;
        Redirect::to('/', array('message' => 'Olet kirjautunut ulos!'));
    }

}
