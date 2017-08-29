<?php

class Kilpailija extends BaseModel {

    public $ktunnus, $nimi, $kayttajanimi, $salasana, $paaaine, $sijoitus;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validate_nimi', 'validate_kayttajanimi', 'validate_salasana', 'validate_duplicate_kayttajanimi', 'validate_paaaine');
    }

    public static function all() {
        $query = DB::connection()->prepare('SELECT * From kilpailija');

        $query->execute();

        $rows = $query->fetchAll();
        $kilpailijat = array();

        foreach ($rows as $row) {
            $kilpailijat[] = new Kilpailija(array(
                'ktunnus' => $row['ktunnus'],
                'nimi' => $row['nimi'],
                'kayttajanimi' => $row['kayttajanimi'],
                'salasana' => $row['salasana'],
                'paaaine' => $row['paaaine']
            ));
        }
        return $kilpailijat;
    }

    public static function find($ktunnus) {
        $query = DB::connection()->prepare('SELECT * FROM kilpailija WHERE ktunnus = :ktunnus LIMIT 1');
        $query->execute(array('ktunnus' => $ktunnus));
        $row = $query->fetch();

        if ($row) {
            $kilpailija = new Kilpailija(array(
                'ktunnus' => $row['ktunnus'],
                'nimi' => $row['nimi'],
                'kayttajanimi' => $row['kayttajanimi'],
                'salasana' => $row['salasana'],
                'paaaine' => $row['paaaine']
            ));

            return $kilpailija;
        }
    }

    public function save() {
        $query = DB::connection()->prepare('INSERT INTO kilpailija(nimi, kayttajanimi, salasana, paaaine) VALUES(:nimi, :kayttajanimi, :salasana, :paaaine) RETURNING ktunnus');

        $query->execute(array('nimi' => $this->nimi, 'kayttajanimi' => $this->kayttajanimi, 'salasana' => $this->salasana, 'paaaine' => $this->paaaine));

        $row = $query->fetch();
        $this->ktunnus = $row['ktunnus'];
    }

    public function update() {
        $query = DB::connection()->prepare('UPDATE Kilpailija SET nimi = :nimi, kayttajanimi = :kayttajanimi, salasana = :salasana, paaaine = :paaaine WHERE ktunnus = :ktunnus');
        $query->execute(array('nimi' => $this->nimi, 'kayttajanimi' => $this->kayttajanimi, 'salasana' => $this->salasana, 'paaaine' => $this->paaaine, 'ktunnus' => $this->ktunnus));
    }

    public function destroy() {
        $query = DB::connection()->prepare('DELETE FROM Kilpailija WHERE ktunnus = :ktunnus');
        $query->execute(array('ktunnus' => $this->ktunnus));
        $row = $query->fetch();
    }

    public static function authenticate($kayttajanimi, $salasana) {
        $query = DB::connection()->prepare('SELECT * FROM Kilpailija WHERE kayttajanimi = :kayttajanimi AND salasana = :salasana LIMIT 1');
        $query->execute(array('kayttajanimi' => $kayttajanimi, 'salasana' => $salasana));
        $row = $query->fetch();

        if ($row) {
            $kilpailija = new Kilpailija(array(
                'ktunnus' => $row['ktunnus'],
                'nimi' => $row['nimi'],
                'kayttajanimi' => $row['kayttajanimi'],
                'salasana' => $row['salasana'],
                'paaaine' => $row['paaaine']
            ));

            return $kilpailija;
        } else {
            return NULL;
        }
    }

    public static function findAllIlmoittautumiset($ktunnus) {
        //Haetaan kaikki kilpailijan ilmoittautumiset kilpailuihin, joiden päivämäärä on suurempi kuin nykyhetki
        //eli kilpailu on tulossa
        $query = DB::connection()->prepare('SELECT kilpailu.kilpailun_nimi, kilpailun_sarja.painoluokka, kilpailun_sarja.vyoarvo, kilpailun_sarja.sarjatunnus 
            FROM kilpailu, kilpailun_sarja, sarjan_osallistuja WHERE kilpailu.kilpailutunnus = kilpailun_sarja.kilpailutunnus 
            AND kilpailun_sarja.sarjatunnus = sarjan_osallistuja.sarjatunnus AND sarjan_osallistuja.ktunnus = :ktunnus 
            AND kilpailu.ajankohta > NOW()');
        $query->execute(array('ktunnus' => $ktunnus));
        $row = $query->fetchAll();
        $ilmoittautumiset = array();

        if ($row) {
            foreach ($row as $r) {


                $ilmoittautumiset[] = new Kilpailun_sarja(array(
                    'kilpailutunnus' => $r['kilpailun_nimi'],
                    'painoluokka' => $r['painoluokka'],
                    'vyoarvo' => $r['vyoarvo'],
                    'sarjatunnus' => $r['sarjatunnus']
                ));
            }
        }
        return $ilmoittautumiset;
    }

    public function validate_kayttajanimi() {
        return parent::validate_string_length($this->kayttajanimi, 'Käyttäjänimi');
    }

    public function validate_salasana() {
        return parent::validate_string_length($this->salasana, 'Salasana');
    }

    public function validate_nimi() {
        return parent::validate_string_length($this->nimi, 'Nimi');
    }

    public function validate_paaaine() {
        return parent::validate_string_length($this->paaaine, 'Pääaine');
    }

    public function validate_duplicate_kayttajanimi() {
        $query = DB::connection()->prepare('SELECT FROM Kilpailija WHERE kayttajanimi = :kayttajanimi');
        $query->execute(array('kayttajanimi' => $this->kayttajanimi));
        $row = $query->fetchAll();

        if ($row) {
            return "Käyttäjänimi on jo käytössä!";
        }
        return null;
    }

}
