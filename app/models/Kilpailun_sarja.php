<?php

class Kilpailun_sarja extends BaseModel {

    public $sarjatunnus, $kilpailutunnus, $painoluokka, $vyoarvo;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }

}
