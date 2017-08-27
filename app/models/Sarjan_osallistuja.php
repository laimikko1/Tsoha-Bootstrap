<?php

class Sarjan_osallistuja extends BaseModel {

    public $ktunnus, $sarjatunnus, $sijoitus;

    public function __construct($attributes) {
        parent::__construct($attributes);
//        $this->validators = array('validate_ilmoittautuminen');
    }

    function save() {
        $query = DB::connection()->prepare('INSERT INTO Sarjan_osallistuja(ktunnus, sarjatunnus) VALUES(:ktunnus, :sarjatunnus)');

        $query->execute(array('ktunnus' => $this->ktunnus, 'sarjatunnus' => $this->sarjatunnus));
    }

    function validate_ilmoittautuminen() {
        $query = DB::connection()->prepare('SELECT * FROM sarjan_osallistuja WHERE ktunnus = :ktunnus AND sarjatunnus = :sarjatunnus');

        $query->execute(array('ktunnus' => $this->ktunnus, 'sarjatunnus' => $this->sarjatunnus));

        $rows = $query->fetchAll();

        if ($rows) {
            return "Et voi ilmoittautua samaan sarjaan useasti!";
        }
        return null;
    }

    function validate_sijoitus() {
        //basemodel-validaattorit tähän :)
    }

    public function updateSijoitus() {
        $query = DB::connection()->prepare('UPDATE sarjan_osallistuja SET sijoitus = :sijoitus WHERE ktunnus = :ktunnus AND sarjatunnus = :sarjatunnus');
        $query->execute(array('ktunnus' => $this->ktunnus, 'sarjatunnus' => $this->sarjatunnus, 'sijoitus' => $this->sijoitus));
    }

}
