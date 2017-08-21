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

}
