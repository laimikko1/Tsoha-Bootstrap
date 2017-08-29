<?php

class Sarjan_osallistuja extends BaseModel {

    public $ktunnus, $sarjatunnus, $sijoitus;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }

    function save() {
        $query = DB::connection()->prepare('INSERT INTO Sarjan_osallistuja(ktunnus, sarjatunnus) VALUES(:ktunnus, :sarjatunnus)');

        $query->execute(array('ktunnus' => $this->ktunnus, 'sarjatunnus' => $this->sarjatunnus));
    }

    public function updateSijoitus() {
        $query = DB::connection()->prepare('UPDATE sarjan_osallistuja SET sijoitus = :sijoitus WHERE ktunnus = :ktunnus AND sarjatunnus = :sarjatunnus');
        $query->execute(array('ktunnus' => $this->ktunnus, 'sarjatunnus' => $this->sarjatunnus, 'sijoitus' => $this->sijoitus));
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

    public function validate_sijoitus() {

        $v = new Valitron\Validator(array('sijoitus' => $this->sijoitus));
        $v->rule('integer', 'sijoitus')->message('Sijoitus tulee olla numero!');
        $v->rule('min', 'sijoitus', 1)->message('Sijoitus tulee olla vähintään 1!');
        $v->rule('max', 'sijoitus', 7)->message('Sijoitus tulee olla enintään 7!');
        if (!$v->validate()) {
            return $v->errors('sijoitus');
        }
        return null;
    }

    public function destroyIlmoittautuminen() {
        $query = DB::connection()->prepare('DELETE FROM sarjan_osallistuja WHERE ktunnus = :ktunnus AND sarjatunnus = :sarjatunnus');
        $query->execute(array('ktunnus' => $this->ktunnus, 'sarjatunnus' =>$this->sarjatunnus));

    }

}
