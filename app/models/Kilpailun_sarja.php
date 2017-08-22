<?php

class Kilpailun_sarja extends BaseModel {

    public $sarjatunnus, $kilpailutunnus, $painoluokka, $vyoarvo;

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
        return $kilpailun_sarjat;
    }

    public function save() {

        $query = DB::connection()->prepare('INSERT INTO kilpailun_sarja(kilpailutunnus, painoluokka, vyoarvo) VALUES(:kilpailutunnus, :painoluokka, :vyoarvo) RETURNING sarjatunnus');

        $query->execute(array('kilpailutunnus' => $this->kilpailutunnus, 'painoluokka' => $this->painoluokka, 'vyoarvo' => $this->vyoarvo));

        $row = $query->fetch();
        $this->sarjatunnus = $row['sarjatunnus'];
    }

}
