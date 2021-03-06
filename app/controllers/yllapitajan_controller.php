<?php

/**
 * Ylläpitäjän controller on vastuussa ylläpitäjän toiminnallisuutta vastaavista sivuista.
 */
class yllapitajan_controller extends BaseController {

    /**
     * Luo näkymän yleiselle esittelysivulle, johon kaikki pääsevät.
     */
    public static function aloitus() {
        // make-metodi renderöi app/views-kansiossa sijaitsevia tiedostoja
        View::make('Yleiset-sivut/esittely.html');
    }

    /**
     * Luo näkymän ylläpitäjän hallintasivulle.
     */
    public static function index() {
        self::check_if_administrator();

        $kilpailijat = Kilpailija::all();
        $tulossaOlevatK = Kilpailu::all(0);
        $menneetK = Kilpailu::all(1);


        View::make('Yllapitaja/yllapitajan_sivu.html', array('kilpailijat' => $kilpailijat, 'tulossaOlevat' => $tulossaOlevatK, 'menneet' => $menneetK));
    }

    /**
     * Näyttää kilpailun muokkaussivun, josta voi poistaa/lisätä kilpailun sarjoja ja muuttaa sen tietoja.
     * @param type $kilpailutunnus
     */
    public static function viewMuokattava($kilpailutunnus) {
        self::check_if_administrator();

        $muokattava = Kilpailu::find($kilpailutunnus);
        $muokattava_sarjat = Kilpailun_sarja::findAll($kilpailutunnus);

        View::make('Yllapitaja/muokkaa_kilpailun_tietoja.html', array('attributes' => $muokattava, 'muokattavat' => $muokattava_sarjat));
    }

    /**
     * Luo näkymän kilpailun tulossivulle, josta tuloksia voi muokata.
     * @param type $kilpailutunnus
     */
    public static function viewMuokattavaTulokset($kilpailutunnus) {
        self::check_if_administrator();

        $muokattava = Kilpailu::find($kilpailutunnus);
        $muokattava_sarjat = Kilpailun_sarja::findAll($kilpailutunnus);

        View::make('Yllapitaja/muokkaa_kilpailun_tuloksia.html', array('kilpailu' => $muokattava, 'sarjat' => $muokattava_sarjat));
    }

    /**
     * Päivittää kilpailun sijoitukset. 
     * Metodi on toodella pitkä, kommentteja lisätty väleihin.
     */
    public static function updateSijoitukset() {
        //alustetaan tarvittavat tietueet
        $params = $_POST;
        $sijoitusjarj = array();
        $tarkistettuSarjat = 0;
        $errors = array();
        //jos ei sijoituksia, tyssätään tähän
        if (empty($params)) {
            Redirect::to('/yllapitajan_sivu', array('message' => 'Muokattavia tuloksia ei löytynyt!'));
        }
        // ruvetaan käymään sarjoja läpi
        for ($index = 0; $index < count($params['sarjatunnus']); $index++) {
            $osallistujat[] = new Sarjan_osallistuja(array(
                'ktunnus' => $params['kilpailija'][$index],
                'sarjatunnus' => $params['sarjatunnus'][$index],
                'sijoitus' => $params['sijoitus'][$index]
            ));
            if ($osallistujat[$index]->sijoitus == "") {
                $osallistujat[$index]->sijoitus = null;
            }
            // tarkista vaihtuuko kilpailun sarja (useita sarjoja samassa parametrijoukossa)
            // jos näin on, tee taikoja eli validoi sarjan järjestys.
            if ($index != 0 && $osallistujat[$index]->sarjatunnus != $osallistujat[$index - 1]->sarjatunnus) {
                $tarkistettuSarjat++;
                // jos tarkistusmetodi palauttaa NULL, eli ei virheitä, voidaan jatkaa
                $check = Kilpailun_sarja::validateJarjestys($sijoitusjarj);
                //muuten lisätään virheviesti listaan
                if (!is_null($check)) {
                    $errors[] = $check;
                    Kint::dump($errors);
                }
                $sijoitusjarj = array();
            }
            if ($osallistujat[$index]->sijoitus != NULL) {
                $sijoitusjarj[] = $params['sijoitus'][$index];
            }
            //sama mekaniikka kuin yllä
            $check = $osallistujat[$index]->validate_sijoitus();
            if (!is_null($check)) {
                $errors[] = $check;
            }
        }
        //viimeinen sarja, joka mahd jäänyt kesken pitää vielä tarkastaa, 
        $check = Kilpailun_sarja::validateJarjestys($sijoitusjarj);
        if (!is_null($check)) {
            $errors[] = $check;
        }

        //tsekataan löytyykö virheitä
        if (count($errors) > 0) {
            $kilpailutunnus = $params['kilpailutunnus'];
            $muokattava = Kilpailu::find($kilpailutunnus);
            $muokattava_sarjat = Kilpailun_sarja::findAll($kilpailutunnus);
            Redirect::to('/kilpailun_sivu/' . $kilpailutunnus . '/muokkaa_tuloksia', array('errors' => $errors, 'muokattava' => $muokattava, 'sarjat' => $muokattava_sarjat));
        }
        //virheitä ei ollut, voidaan päivitellä
        foreach ($osallistujat as $osal) {
            $osal->updateSijoitus();
        }
        Redirect::to('/yllapitajan_sivu', array('message' => 'Kilpailun tuloksia muokattua onnistuneesti!'));
    }

    /**
     * Päivittää kilpailun tietoja.
     * @param type $kilpailutunnus
     */
    public static function update($kilpailutunnus) {
        $params = $_POST;


        $attributes = array(
            'kilpailutunnus' => $kilpailutunnus,
            'kilpailun_nimi' => $params['kilpailun_nimi'],
            'kilpailupaikka' => $params['kilpailupaikka'],
            'ajankohta' => $params['ajankohta'],
            'kilpailun_kuvaus' => $params['kilpailun_kuvaus']
        );

        $kilpailu = new Kilpailu($attributes);
        $errors = $kilpailu->errors();



        if (count($errors) > 0) {
            View::make('Yllapitaja/muokkaa_kilpailun_tietoja.html', array('errors' => $errors, 'attributes' => $attributes));
        } else {
            $kilpailu->update();
            Redirect::to('/yllapitajan_sivu', array('message' => 'Kilpailun tietoja päivitetty onnistuneesti!'));
        }
    }

    /**
     * Luo näkymän uuden kilpailun luomisen sivulle.
     */
    public static function uusi() {
        self::check_if_administrator();

        View::make('Kilpailu/uusi_kilpailu.html');
    }

    /**
     * Tallentaa uuden kilpailun sovellukseen ja tietokantaan. Metodi on melko pitkä, sillä samalla luodaan myös kilpailun painoluokat.
     */
    public static function store() {
        $params = $_POST;

        $kilpailu = new Kilpailu(array(
            'kilpailun_nimi' => $params['kilpailun_nimi'],
            'kilpailupaikka' => $params['kilpailupaikka'],
            'ajankohta' => $params['kilpailun_paiva'] . ' ' . $params['kilpailun_kellonaika'] . ':00',
            'kilpailun_kuvaus' => $params['kilpailun_kuvaus']
        ));
        $errors = $kilpailu->errors();

        if (empty($params['painoluokat_alemmat']) && empty($params['painoluokat_ylemmat'])) {
            $errors[] = 'Valitse ainakin yksi painoluokka kilpailuun!';
        } else {
            $alemmat_painoluokat = $params['painoluokat_alemmat'];
            $ylemmat_painoluokat = $params['painoluokat_ylemmat'];
        }

        if (count($errors) == 0) {
            $id = $kilpailu->save();
            kilpailun_sarja_controller::store($id, $alemmat_painoluokat, $ylemmat_painoluokat);

            Redirect::to('/kilpailun_sivu/' . $kilpailu->ktunnus, array('message' => 'Kilpailu ja sen painoluokat luotu!'));
        } else {
            View::make('Kilpailu/uusi_kilpailu.html', array('errors' => $errors, 'attributes' => $kilpailu));
        }
    }

    /**
     * Poistaa kilpailun järjestelmästä ja tietokannasta.
     */
    public static function destroy() {
        $params = $_POST;

        $kilpailu = new Kilpailu(array(
            'kilpailutunnus' => $params['kilpailutunnus']
        ));

        $kilpailu->destroy();
        Redirect::to('/yllapitajan_sivu', array('message' => 'Kilpailu poistettu!'));
    }

}
