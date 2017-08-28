<?php

class yllapitajan_controller extends BaseController {

    public static function index() {
        self::check_if_administrator();

        $kilpailijat = Kilpailija::all();
        $tulossaOlevatK = Kilpailu::all(0);
        $menneetK = Kilpailu::all(1);


        View::make('Yllapitaja/yllapitajan_sivu.html', array('kilpailijat' => $kilpailijat, 'tulossaOlevat' => $tulossaOlevatK, 'menneet' => $menneetK));
    }

    public static function viewMuokattava($kilpailutunnus) {
        $muokattava = Kilpailu::find($kilpailutunnus);
        $muokattava_sarjat = Kilpailun_sarja::findAll($kilpailutunnus);

        View::make('Yllapitaja/muokkaa_kilpailun_tietoja.html', array('attributes' => $muokattava, 'muokattavat' => $muokattava_sarjat));
    }

    public static function viewMuokattavaTulokset($kilpailutunnus) {
        $muokattava = Kilpailu::find($kilpailutunnus);
        $muokattava_sarjat = Kilpailun_sarja::findAll($kilpailutunnus);

        View::make('Yllapitaja/muokkaa_kilpailun_tuloksia.html', array('kilpailu' => $muokattava, 'sarjat' => $muokattava_sarjat));
    }

    public static function updateSijoitukset() {
        $params = $_POST;
        $sijoitusjarj = array();
        $tarkistettuSarjat = 0;

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
            if ($index != 0 && $osallistujat[$index]->sarjatunnus != $osallistujat[$index - 1]->sarjatunnus) {
                $tarkistettuSarjat++;
                $errors = Kilpailun_sarja::validateJarjestys($sijoitusjarj);
                $sijoitusjarj = array();
            }
            if ($osallistujat[$index]->sijoitus != NULL) {
                $sijoitusjarj[] = $params['sijoitus'][$index];                
            }

            $errors = $osallistujat[$index]->validate_sijoitus();
        }
        //viimeinen sarja, joka mahd jäänyt kesken pitää vielä tarkastaa, mahdollisesti aina
        //tarkastetaan siis tuplana yksi sarja...
        Kilpailun_sarja::validateJarjestys($sijoitusjarj);
        
        Kint::dump($sijoitusjarj);
        View::make('/');

//
//        if (count($errors) > 0) {
//            View::make('Yllapitaja/muokkaa_kilpailun_tuloksia.html', array('errors' => $errors));
//        }






        foreach ($osallistujat as $osal) {
            $osal->updateSijoitus();

            Redirect::to('/yllapitajan_sivu', array('message' => 'Kilpailun tuloksia muokattua onnistuneesti!'));
        }
    }

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

    public static function uusi() {
        View::make('Kilpailu/uusi_kilpailu.html');
    }

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

}
