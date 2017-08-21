<?php

class Kilpailun_sarja extends BaseModel {

    public $sarjatunnus, $kilpailutunnus, $painoluokka, $vyoarvo;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }

    public static function save() {

        $query = DB::connection()->prepare('INSERT INTO kilpailun_sarja(kilpailutunnus, painoluokka, vyoarvo) '
                . 'VALUES(:kilpailutunnus, :painoluokka, :vyoarvo) RETURNING sarjatunnus)');

        $query->execute(array('kilpailutunnus' => $this->kilpailutunnus, 'painoluokka' => $this->painoluokka,
            'voyarvo' => $this->vyoarvo));

        $row = $query->fetch();
        $this->sarjatunnus = $row['sarjatunnus'];
    }

}
