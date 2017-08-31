<?php
/**
 * Kilpailun sarja vastaa kilpailun_sarja tietokantataulun ilmentymää.
 * Tietokannasta poiketen kilpailun sarjala on sarjan_osallistujat array.
 * Tämä helpottaa tietyn sarjan kilpailijoiden iteroimista ja käsittelyä.
 */
class Kilpailun_sarja extends BaseModel {

    public $sarjatunnus, $kilpailutunnus, $painoluokka, $vyoarvo, $sarjan_osallistujat;
/**
 * Luodaan uusi kilpailun sarja.
 * @param type $attributes
 */
    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validate_painoluokka');
    }
/**
 * Haetaan kaikki tietyn kilpailun sarjat. Tämän jälkeen jokaiseen sarjaan haetaan kaikki tietyn kilpailun sarjan kilpailijat.
 * @param type $kilpailutunnus
 * @return \Kilpailun_sarja
 */
    public static function findAll($kilpailutunnus) {
        $query = DB::connection()->prepare('SELECT * FROM kilpailun_sarja WHERE kilpailutunnus = :kilpailutunnus ORDER BY kilpailun_sarja.vyoarvo ASC');

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
/**
 * Hakee kaikki tietyn kilpailun sarjan osallistujat.
 * @param type $sarjatunnus
 * @return \Kilpailija
 */
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
/**
 * Tallentaa uuden kilpailun sarjan tietokantaan.
 */
    public function save() {
        $query = DB::connection()->prepare('INSERT INTO kilpailun_sarja(kilpailutunnus, painoluokka, vyoarvo) VALUES(:kilpailutunnus, :painoluokka, :vyoarvo) RETURNING sarjatunnus');

        $query->execute(array('kilpailutunnus' => $this->kilpailutunnus, 'painoluokka' => $this->painoluokka, 'vyoarvo' => $this->vyoarvo));

        $row = $query->fetch();
        $this->sarjatunnus = $row['sarjatunnus'];
    }
/**
 * Poistaa halutun kilpailun sarjan tietokannasta.
 */
    public function destroy() {
        $query = DB::connection()->prepare('DELETE FROM kilpailun_sarja WHERE sarjatunnus = :sarjatunnus');
        $query->execute(array('sarjatunnus' => $this->sarjatunnus));
    }
/**
 * Validoi painoluokan, ettei luku ole liian suuri ja että kenttä on täytetty.
 * @return type
 */
    public function validate_painoluokka() {
        $length = parent::validate_string_length($this->painoluokka, 'Painoluokka', 2, 3);
        $req = parent::validate_required_fields($this->painoluokka, 'Painoluokka');
        return parent::merge_validations($length, $req);
    }
/**
 * Validoi kilpailun sarjan keskinäiset sijoitukset. Sijoituksia on kolmea erilaista, jokaisella oma metodinsa.
 * @param type $sijoitusjar
 */
    public static function validateJarjestys($sijoitusjar) {
        sort($sijoitusjar);

        if (count($sijoitusjar) >= 8) {
            self::checkEightOrMorePlacings($sijoitusjar);
        }
        if (count($sijoitusjar) == 4) {
            self::checkFourPlacings($sijoitusjar);
        }
        if (count($sijoitusjar) < 4) {
            self::checkUnderEightPlacings($sijoitusjar);
        }
    }
/**
 * Valdioi että kilpailun tulokset on asetettu oikein, jos kilpailijoita on yli kahdeksan.
 * @param type $sijoitusjar
 * @return boolean
 */
    public static function checkEightOrMorePlacings($sijoitusjar) {
        //tarkistetaan uniikit sijat 1 & 2

        if ($sijoitusjar[0] != 1 && $sijoitusjar[1] != 2) {
            return false;
        }
        //Jos määriteltyjä sijoituksia on 8
        // sijat 1-2 ovat uniikkeja ja sijat 3-7 ovat kaikki tuplia, eli sijoituksevat ovat: 3,3/ 5,5/ 7,7 
        // eli 4 ja 6 sijoja ei ole.
        // Tarkistetaan ensin ettei indeksin arvo ole 2&4&6 ja sen jälkeen arvo saa olla joko
        // indeksi tai indeksi +1
        for ($index = 2; $index < count($sijoitusjar); $index++) {
            if ($sijoitusjar[$index] == 2 || $sijoitusjar[$index] == 4 || [$sijoitusjar[$index]] == 6 || $sijoitusjar[$index] > 7) {
                return false;
            }
            if ($sijoitusjar[$index] == $index || $sijoitusjar[$index] == $index + 1) {
                continue;
            }
            return false;
        }
        return true;
    }
/**
 * Validoi että kilpailun tulokset on asetettu oikein, jos kilpailijoita on alle 8.
 * @param type $sijoitusjar
 * @return boolean
 */
    public static function checkUnderEightPlacings($sijoitusjar) {
        //Jos alle 8 kilpailijaa, kaikkia sijoja on yksi, paitsi erikoistapaus 4 sijoitusta
        for ($index = 0; $index < count($sijoitusjar); $index++) {
            if ($sijoitusjar[$index] != $index + 1) {
                Kint::dump($sijoitusjar);
                return false;
            }
        }
        return true;
    }
/**
 * Validoi neljän kilpailijan sarjan järjestyksen. Huom, tämä on poikkeustapaus ylläolevaan alle kahdeksan
 * kilpailijan sarjaan.
 * @param type $sijoitusjar
 * @return boolean
 */
    public static function checkFourPlacings($sijoitusjar) {
        //Vedetää rumalla kovakoodilla
        if ($sijoitusjar[0] != 1 || $sijoitusjar[1] != 2) {
            return false;
        }

        if ($sijoitusjar[2] != 3 || $sijoitusjar[3] != 3) {
            return false;
        }
        return true;
    }

}
