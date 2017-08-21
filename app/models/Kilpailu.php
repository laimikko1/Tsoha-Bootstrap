<?php

class Kilpailu extends BaseModel {

    public $kilpailutunnus, $kilpailun_nimi, $kilpailupaikka, $ajankohta, $kilpailun_kuvaus;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }

 

    public static function all() {
        $query = DB::connection()->prepare('SELECT * FROM kilpailu');

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
        $query = DB::connection()->prepare('SELECT * FROM kilpailu WHERE ktunnus = :kilpailutunnus LIMIT 1');
        $query->execute(array('ktunnus' => $ktunnus));
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

}
