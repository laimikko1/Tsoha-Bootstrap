<?php

class Sarjan_osallistuja extends BaseModel {

    public $ktunnus, $sarjatunnus, $sijoitus;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }

}
