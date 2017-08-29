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
        $dateObj = DateTime::createFromFormat('Y-m-d H:i:s', $string);
        $nyt = date("Y-m-d H:i:s");
        if ($nyt > $dateObj) {
            return array('ajankohta' => 'Ajankohta ei voi olla menneisyydessä! Jos omistat toimivan aikakoneen, ota yhteys ylläpitoon.');
        }
        if (!$dateObj) {
            return array('ajankohta' => 'Ajankohta tulee olla muodossa VVVV-KK-PP JA TT:MM');
        }
        return null;
    }

    public function update() {
        $query = DB::connection()->prepare('UPDATE kilpailu SET kilpailun_nimi = :kilpailun_nimi, kilpailupaikka = :kilpailupaikka, ajankohta = :ajankohta, kilpailun_kuvaus = :kilpailun_kuvaus WHERE kilpailutunnus = :kilpailutunnus');
        $query->execute(array('kilpailun_nimi' => $this->kilpailun_nimi, 'kilpailupaikka' => $this->kilpailupaikka, 'ajankohta' => $this->ajankohta, 'kilpailun_kuvaus' => $this->kilpailun_kuvaus, 'kilpailutunnus' => $this->kilpailutunnus));
    }

}
