<?php
/**
 * Kilpailun_sarja controller vastaa kilpailun_sarjan tietokantailmentymän hallinasta, yhdessä kilpailun_sarja-
 * mallin kanssa.
 */
class kilpailun_sarja_controller extends BaseController {
/**
 * Tallentaa kilpailun sarjat järjestelmään. Saa parametrina sekä ylemmät että alemmat vyöluokkien painoluokat
 * ja kilpailutunnuksen, johon sarjat liittyvät.
 * @param type $kilpailutunnus
 * @param type $alemmat_painoluokat
 * @param type $ylemmat_painoluokat
 * @return type
 */
    public static function store($kilpailutunnus, $alemmat_painoluokat, $ylemmat_painoluokat) {

        $alemmat = self::luoSarjat($alemmat_painoluokat, $kilpailutunnus, 'Keltainen/oranssi');
        $ylemmat = self::luoSarjat($ylemmat_painoluokat, $kilpailutunnus, 'Vihrea/Sininen/Ruskea/Musta');
        $kilpailun_sarjat = array_merge($alemmat, $ylemmat);

        foreach ($kilpailun_sarjat as $ksarja) {
            $ksarja->save();
        }


        return $kilpailun_sarjat;
    }
/**
 * Luo ilmoittautumisen kilpailun_sarjaan. Tarkistetaan onko käyttäjä kirjatunut sisään.
 * @param type $kilpailutunnus
 */
    public static function ilmoittaudu($kilpailutunnus) {
        self::check_logged_in();
        $id = $_SESSION['kilpailija'];
        $errors = array();

        $params = $_POST;

        $attributes = (array(
            'ktunnus' => $id,
            'sarjatunnus' => $params['sarjatunnus']
        ));

        $sarjan_osallistuja = new Sarjan_osallistuja($attributes);
        $errors[] = $sarjan_osallistuja->validate_ilmoittautuminen();

        if ($errors[0] == NULL) {
            $sarjan_osallistuja->save();
            Redirect::to('/', array('message' => 'Ilmoittautuminen kilpailuun lisätty!'));
        } else {
            Redirect::to('/kilpailun_sivu/' . $kilpailutunnus . '/ilmoittautuminen', array('errors' => $errors));
        }
    }
/**
 * Luo kilpailun sarjat annetuilla parametreilla.
 * @param type $painoluokka
 * @param type $kilpailutunnus
 * @param type $vyoarvo
 * @return \Kilpailun_sarja
 */
    private static function luoSarjat($painoluokka, $kilpailutunnus, $vyoarvo) {
        foreach ($painoluokka as $painoluokka) {
            $kilpailun_sarja = new Kilpailun_sarja(array(
                'kilpailutunnus' => $kilpailutunnus,
                'vyoarvo' => $vyoarvo,
                'painoluokka' => $painoluokka
            ));
            $kilpailun_sarjat[] = $kilpailun_sarja;
        }
        return $kilpailun_sarjat;
    }
/**
 * Poistaa kilpailun sarjan.
 */
    public static function destroy() {
        $params = $_POST;
        $sarjatunnus = $params['sarjatunnus'];
        $kilpailutunnus = $params['kilpailutunnus'];
        $poistettava_sarja = new Kilpailun_sarja(array('sarjatunnus' => $sarjatunnus));
        $poistettava_sarja->destroy();


        Redirect::to('/kilpailun_sivu/' . $kilpailutunnus . '/muokkaa', array('message' => 'Painoluokka poistettu kilpailusta!'));
    }
/**
 * Lisää uuden, yksittäisen kilpailun sarjan.
 * @param type $kilpailutunnus
 */
    public static function add($kilpailutunnus) {
        $params = $_POST;
        $lisattava_sarja = new Kilpailun_sarja(array(
            'kilpailutunnus' => $kilpailutunnus,
            'painoluokka' => $params['painoluokka'],
            'vyoarvo' => $params['vyoarvo']
        ));
        $errors = $lisattava_sarja->errors();
        if (count($errors) > 0) {
            Redirect::to('/kilpailun_sivu/' . $kilpailutunnus . '/muokkaa', array('errors' => $errors));
        }
        $lisattava_sarja->save();
        Redirect::to('/kilpailun_sivu/' . $kilpailutunnus . '/muokkaa', array('message' => 'Sarja lisätty kilpailuun!'));
    }
/**
 * Poistaa kilpailijan ilmoittautumisen kilpailun_sarjaan
 * @param type $sarjatunnus
 */
    public static function destroyIlmoittautuminen($sarjatunnus) {
        $id = $_SESSION['kilpailija'];
        $params = $_POST;
        $osallistuja = new Sarjan_osallistuja(array(
            'ktunnus' => $id,
            'sarjatunnus' => $params['sarjatunnus']
        ));

        $osallistuja->destroyIlmoittautuminen();
        Redirect::to('/kayttajan_sivu/' . $osallistuja->ktunnus, array('message' => 'Ilmoittautuminen peruttu!'));
    }

}
