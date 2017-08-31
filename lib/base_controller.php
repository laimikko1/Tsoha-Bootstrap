<?php

class BaseController {

    public static function get_user_logged_in() {
        // Toteuta kirjautuneen käyttäjän haku tähän
        if (isset($_SESSION['kilpailija'])) {
            $kilpailija_ktunnus = $_SESSION['kilpailija'];

            $kilpailija = Kilpailija::find($kilpailija_ktunnus);

            return $kilpailija;
        }
        return null;
    }

    public static function check_logged_in() {
        // Toteuta kirjautumisen tarkistus tähän.
        // Jos käyttäjä ei ole kirjautunut sisään, ohjaa hänet toiselle sivulle (esim. kirjautumissivulle).
        if (!isset($_SESSION['kilpailija'])) {
            Redirect::to('/kirjautuminen', array('message' => 'Sivu sallittu vain kirjautuneille jäsenille!'));
        }
    }

    public static function check_if_users_page($tarkistettava) {
        // Tarkistaa ettei yksi käyttäjä pääse esimerkiksi kaikkien muidenkin käyttäjien
        // henk koht sivuille, kuten käyttäjätietojen muokkaukseen
        // Poikkeus admin, kenen id 6
        if ($_SESSION['kilpailija'] == 6) {
            return;
        }

        $id = ($_SESSION['kilpailija']);
        if ($id != $tarkistettava) {
            Redirect::to('/kayttajan_sivu/' . $id);
        }
    }

    public static function check_if_administrator() {
        self::check_logged_in();
        $tarkistettava = ($_SESSION['kilpailija']);
        $kilpailija = Kilpailija::find($tarkistettava);
        $nimi = $kilpailija->kayttajanimi;
        if ($nimi != 'admin') {
            Redirect::to('/', array('message' => 'Sivu sallittu vain ylläpidolle!'));
        }
    }

}
