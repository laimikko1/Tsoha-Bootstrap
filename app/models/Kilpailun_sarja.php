<?php

class Kilpailun_sarja extends BaseModel {

    public $sarjatunnus, $kilpailutunnus, $painoluokka, $vyoarvo, $sarjan_osallistujat;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }

    public static function findAll($kilpailutunnus) {
        $query = DB::connection()->prepare('SELECT * FROM kilpailun_sarja WHERE kilpailutunnus = :kilpailutunnus');

        $query->execute(array('kilpailutunnus' => $kilpailutunnus));

        $rows = $query->fetchAll();

        $kilpailun_sarjat = array();

        if ($rows) {
            foreach ($rows as $row) {
                $kilpailun_sarjat[] = new Kilpailun_sarja(array(
                    'sarjatunnus' => $row['sarjatunnus'],
                    'kilpailutunnus' => $row['kilpailutunnus'],
                    'painoluokka' => $row['painoluokka'],
                    'vyoarvo' => $row['vyoarvo']
                ));
            }
        }
        foreach ($kilpailun_sarjat as $sarja) {
            $sarjan_osallistujat = self::getAllClassCompetitors($sarja->sarjatunnus);
            $sarja->sarjan_osallistujat = $sarjan_osallistujat;
        }


        return $kilpailun_sarjat;
    }

    public static function getAllClassCompetitors($sarjatunnus) {
        $query = DB::connection()->prepare('SELECT kilpailija.nimi, kilpailija.paaaine, sarjan_osallistuja.sarjatunnus, '
                . 'sarjan_osallistuja.sijoitus, sarjan_osallistuja.ktunnus FROM sarjan_osallistuja '
                . 'LEFT JOIN kilpailija '
                . 'ON sarjan_osallistuja.ktunnus = kilpailija.ktunnus '
                . 'WHERE sarjan_osallistuja.sarjatunnus = :sarjatunnus '
                . 'ORDER BY sarjan_osallistuja.sijoitus ASC');
        $query->execute(array('sarjatunnus' => $sarjatunnus));

        $rows = $query->fetchAll();

        $sarjan_osallistujat = array();

        if ($rows) {
            foreach ($rows as $row) {
                $sarjan_osallistujat[] = new Kilpailija(array(
                    'ktunnus' => $row['ktunnus'],
                    'nimi' => $row['nimi'],
                    'paaaine' => $row['paaaine'],
                    'sijoitus' => $row['sijoitus']
                ));
            }
        }
        return $sarjan_osallistujat;
    }

    public function save() {

        $query = DB::connection()->prepare('INSERT INTO kilpailun_sarja(kilpailutunnus, painoluokka, vyoarvo) VALUES(:kilpailutunnus, :painoluokka, :vyoarvo) RETURNING sarjatunnus');

        $query->execute(array('kilpailutunnus' => $this->kilpailutunnus, 'painoluokka' => $this->painoluokka, 'vyoarvo' => $this->vyoarvo));

        $row = $query->fetch();
        $this->sarjatunnus = $row['sarjatunnus'];
    }

    public function destroy() {
        $query = DB::connection()->prepare('DELETE FROM kilpailun_sarja WHERE sarjatunnus = :sarjatunnus');
        $query->execute(array('sarjatunnus' => $this->sarjatunnus));
    }

    public static function validateJarjestys($sijoitusjar) {
        sort($sijoitusjar);
        if (count($sijoitusjar) >= 8) {
            Kint::dump(self::checkOverEightBracket($sijoitusjar));
            View::make('/');
        }
//        if (count($sijoitusjar) < 6) {
//            self::checkUnderSixBracket($sijoitusjar);
//        } else {
//            self::checkSixSevenBrackets($sijoitusjar);
//        }
    }

    public static function checkOverEightBracket($sijoitusjar) {
        //tarkistetaan uniikit sijat 1 & 2

        if ($sijoitusjar[0] != 1 && $sijoitusjar[1] != 2) {
            return false;
        }
        //sijat 3-7 ovat kaikki tuplia, eli sijoituksevat ovat: 3,3/ 5,5/ 7,7 eli 4,6 sijoja ei ole,
        // eli tarkistetaan ensnin ettei indeksin arvo ole 4&6 ja sen jälkeen arvo saa olla joko
        // indeksi tai indeksi +1
        for ($index = 2; $index < count($sijoitusjar); $index++) {
            if ($sijoitusjar[$index] == $index || $sijoitusjar[$index] == $index + 1) {
                continue;
            }
            if ($sijoitusjar[$index] == 4 || [$sijoitusjar[$index]] == 6 || $sijoitusjar[$index] > 7) {
                return false;
            }
            Kint::dump('tääne kosahti');
            Kint::dump($index);

            return false;
        }
        return true;
    }

    public static function checkUnderSixBracket($sijoitusjar) {
        
    }

    public static function checkSixSevenBrackets($sijoitusjar) {
        
    }

}
