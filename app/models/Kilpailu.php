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
            $kilpailijat[] = new Kilpailija(array(
                'kilpailutunnus' => $row['kilpailutunnus'],
                'kilpailun_nimi' => $row['kilpailun_nimi'],
                'kilpailupaikka' => $row['kilpailupaikka'],
                'ajankohta' => $row['ajankohta'],
                'kilpailun_kuvaus' => $row['kilpailun_kuvaus']
            ));
        }
    }

}
