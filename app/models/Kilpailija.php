<?php

class Kilpailija extends BaseModel {

    public $ktunnus, $nimi, $kayttajanimi, $salasana, $paaaine;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }

    public static function all() {
        $query = DB::connection()->prepare('SELECT * From kilpailija');

        $query->execute();

        $rows = $query->fetchAll();
        $kilpailijat = array();

        foreach ($rows as $row) {
            $kilpailijat[] = new Kilpailija(array(
                'ktunnus' => $row['ktunnus'],
                'nimi' => $row['nimi'],
                'kayttajanimi' => $row['kayttajanimi'],
                'salasana' => $row['salasana'],
                'paaaine' => $row['paaaine']
            ));
        }
        return $kilpailijat;
    }

    public static function find($ktunnus) {
        $query = DB::connection()->prepare('SELECT * FROM kilpailija WHERE ktunnus = :ktunnus LIMIT 1');
        $query->execute(array('ktunnus' => $ktunnus));
        $row = $query->fetch();

        if ($row) {
            $kilpailija = new Kilpailija(array(
                'ktunnus' => $row['ktunnus'],
                'nimi' => $row['nimi'],
                'kayttajanimi' => $row['kayttajanimi'],
                'salasana' => $row['salasana'],
                'paaaine' => $row['paaaine']
            ));

            return $kilpailija;
        }
    }

   
    public function save() {
        $query = DB::connection()->prepare('INSERT INTO kilpailija(nimi, kayttajanimi, salasana, paaaine) VALUES(:nimi, :kayttajanimi, :salasana, :paaaine) RETURNING ktunnus');

        $query-> execute(array('nimi' => $this->nimi, 'kayttajanimi' => $this->kayttajanimi, 'salasana' => $this->salasana, 'paaaine' => $this->paaaine));

        $row = $query->fetch();
        Kint::trace();
        Kint::dump($row);
        $this->ktunnus = $row['ktunnus'];
        
        }

}
