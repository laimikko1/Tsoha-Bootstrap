<?php

class sarjan_osallistuja extends BaseModel {

    public $ktunnus, $sarjatunnus, $sijoitus;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validate_ilmoittautuminen');
    }

    public function save() {
        $query = DB::connection()->prepare('INSERT INTO Sarjan_osallistuja(ktunnus, sarjatunnus) VALUES(:ktunnus, :sarjatunnus)');

        $query->execute(array('ktunnus' => $this->ktunnus, 'sarjatunnus' => $this->sarjatunnus));
    }

    public function validate_ilmoittautuminen() {
        $query = DB::connection()->prepare('SELECT * FROM sarjan_osallistuja WHERE ktunnus = :ktunnus AND sarjatunnus = :sarjatunnus');

        $query->execute(array('ktunnus' => $this->ktunnus, 'sarjatunnus' => $this->sarjatunnus));

        $rows = $query->fetchAll();

        if ($rows) {
            return "Et voi ilmoittautua samaan sarjaan useasti!";
        }
        return null;
    }

}
