<?php
/**
 * Kilpailija-controller vastaa kilpailija-mallin kanssa kilpailijaan liittyvistä toiminnoista
 */
class kilpailija_controller extends BaseController {
/**
 * Talleta uusi kilpailija tietokantaan.
 */
    public static function store() {

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
/**
 * Luo kilpailijan oman sivun näkymä. Saa parametrinaan ktunnuksen, jonka avulla haetaan haluttu kilpailija.
 * @param type $ktunnus
 */
    public static function view($ktunnus) {
        self::check_logged_in();
        self::check_if_users_page($ktunnus);

        $ilmoittautumiset = Kilpailija::findAllIlmoittautumiset($ktunnus);
        $kilpailija = Kilpailija::find($ktunnus);
        View::make('Kilpailija/kayttajan_tiedot_ja_ilmoittautumiset.html', array('attributes' => $kilpailija, 'ilmoittautumiset' => $ilmoittautumiset));
    }
/**
 * Luo näkymän kilpailijan tietojen muokkaussivulle.
 * @param type $ktunnus
 */
    public static function edit($ktunnus) {

        self::check_logged_in();
        self::check_if_users_page($ktunnus);

        $kilpailija = Kilpailija::find($ktunnus);
        View::make('Kilpailija/kayttajan_sivu_muokkaus.html', array('attributes' => $kilpailija));
    }
/**
 * Päivitä kilpailjian tietoja.
 * @param type $ktunnus
 */
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
/**
 * Poista kilpailija sovelluksesta.
 * @param type $ktunnus
 */
    public static function destroy($ktunnus) {
        $kilpailija = new Kilpailija(array('ktunnus' => $ktunnus));
        $kilpailija->destroy();

        Redirect::to('/rekisteroityminen', array('message' => 'Tunnus poistettu onnistuneesti!'));
    }

}
