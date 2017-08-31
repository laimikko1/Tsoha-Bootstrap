<?php

class Kilpailu extends BaseModel {

    public $kilpailutunnus, $kilpailun_nimi, $kilpailupaikka, $ajankohta, $kilpailun_kuvaus;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validate_name', 'validate_kilpailun_kuvaus', 'validate_kilpailupaikka', 'validate_ajankohta');
    }

    public static function all($newOrOld) {
// todella pleb ratkaisu, SORI!!

        if ($newOrOld === 0) {
            $query = DB::connection()->prepare('SELECT * FROM kilpailu WHERE ajankohta > LOCALTIMESTAMP ORDER BY ajankohta ASC');
        } else {
            $query = DB::connection()->prepare('SELECT * FROM kilpailu WHERE ajankohta < LOCALTIMESTAMP ORDER BY ajankohta DESC');
        }
        $query->execute();



        $rows = $query->fetchAll();
        $kilpailut = array();

        foreach ($rows as $row) {
            $kilpailut[] = new Kilpailu(array(
                'kilpailutunnus' => $row['kilpailutunnus'],
                'kilpailun_nimi' => $row['kilpailun_nimi'],
                'kilpailupaikka' => $row['kilpailupaikka'],
                'ajankohta' => $row['ajankohta'],
                'kilpailun_kuvaus' => $row['kilpailun_kuvaus']
            ));
        }

        return $kilpailut;
    }

    public static function find($kilpailutunnus) {
        $query = DB::connection()->prepare('SELECT * FROM kilpailu WHERE kilpailutunnus = :kilpailutunnus LIMIT 1');
        $query->execute(array('kilpailutunnus' => $kilpailutunnus));
        $row = $query->fetch();

        if ($row) {
            $kilpailu = new Kilpailu(array(
                'kilpailutunnus' => $row['kilpailutunnus'],
                'kilpailun_nimi' => $row['kilpailun_nimi'],
                'kilpailupaikka' => $row['kilpailupaikka'],
                'ajankohta' => $row['ajankohta'],
                'kilpailun_kuvaus' => $row['kilpailun_kuvaus']
            ));

            return $kilpailu;
        }
    }

    public function save() {
        $query = DB::connection()->prepare('INSERT INTO kilpailu(kilpailun_nimi, kilpailupaikka, ajankohta, kilpailun_kuvaus) VALUES(:kilpailun_nimi, :kilpailupaikka, :ajankohta, :kilpailun_kuvaus) RETURNING kilpailutunnus');

        $query->execute(array('kilpailun_nimi' => $this->kilpailun_nimi, 'kilpailupaikka' => $this->kilpailupaikka, 'ajankohta' => $this->ajankohta, 'kilpailun_kuvaus' => $this->kilpailun_kuvaus));

        $row = $query->fetch();
        $this->ktunnus = $row['kilpailutunnus'];

        return $this->ktunnus;
    }

    public function validate_name() {
        $length = parent::validate_string_length($this->kilpailun_nimi, 'Kilpailun nimi', 2, 50);
        $req = parent::validate_required_fields($this->kilpailun_nimi, 'Kilpailun nimi');
        return parent::merge_validations($length, $req);
    }

    public function validate_kilpailun_kuvaus() {
        $length = parent::validate_string_length($this->kilpailun_kuvaus, 'Kilpailun kuvaus', 10, 500);
        $req = parent::validate_required_fields($this->kilpailun_kuvaus, 'Kilpailun kuvaus');
        return parent::merge_validations($length, $req);
    }

    public function validate_kilpailupaikka() {
        $length = parent::validate_string_length($this->kilpailupaikka, 'Kilpailupaikka', 2, 50);
        $req = parent::validate_required_fields($this->kilpailupaikka, 'Kilpailupaikka');
        return parent::merge_validations($length, $req);
    }

    public function validate_ajankohta() {
        $string = $this->ajankohta;
        $dateObj = new DateTime($string);
        $moi = $dateObj->format('Y-m-d H:i:s');
        $nyt = date("Y-m-d H:i:s");

        if (!$dateObj) {
            return array('ajankohta' => 'Ajankohta tulee olla muodossa VVVV-KK-PP JA TT:MM');
        }
        if (!$this->validate_dates()) {
            return array('ajankohta' => 'Varmista että kuukausi on 1-12, päivä 1-31, tunnit 00-23, minuutit 00-59, sekunnit 00-59!');
        }
        if ($nyt > $moi) {
            return array('ajankohta' => 'Ajankohta ei voi olla menneisyydessä! Jos omistat toimivan aikakoneen, ota yhteys ylläpitoon.');
        }

        return null;
    }

    public function update() {
        $query = DB::connection()->prepare('UPDATE kilpailu SET kilpailun_nimi = :kilpailun_nimi, kilpailupaikka = :kilpailupaikka, ajankohta = :ajankohta, kilpailun_kuvaus = :kilpailun_kuvaus WHERE kilpailutunnus = :kilpailutunnus');
        $query->execute(array('kilpailun_nimi' => $this->kilpailun_nimi, 'kilpailupaikka' => $this->kilpailupaikka, 'ajankohta' => $this->ajankohta, 'kilpailun_kuvaus' => $this->kilpailun_kuvaus, 'kilpailutunnus' => $this->kilpailutunnus));
    }

    public function destroy() {
        $query = DB::connection()->prepare('DELETE FROM kilpailu WHERE kilpailutunnus = :kilpailutunnus');
        $query->execute(array('kilpailutunnus' => $this->kilpailutunnus));
    }

    public function validate_dates() {
        $v = substr($this->ajankohta, 0, 4);
        $k = substr($this->ajankohta, 5, 2);
        $d = substr($this->ajankohta, 8, 2);
        $h = substr($this->ajankohta, 11, 2);
        $m = substr($this->ajankohta, 14, 2);
        $s = substr($this->ajankohta, 17, 2);

        return ($this->testRange($k, 01, 13) && $this->testRange($d, 01, 32) && $this->testRange($h, 00, 24) && $this->testRange($m, 00, 60) && $this->testRange($s, 00, 60));
    }

    function testRange($int, $min, $max) {
        return ($min <= $int && $int < $max);
    }

}
