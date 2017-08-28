<?php

class Kilpailun_sarja extends BaseModel {

    public $sarjatunnus, $kilpailutunnus, $painoluokka, $vyoarvo, $sarjan_osallistujat;

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
        foreach ($kilpailun_sarjat as $sarja) {
            $sarjan_osallistujat = self::getAllClassCompetitors($sarja->sarjatunnus);
            $sarja->sarjan_osallistujat = $sarjan_osallistujat;
        }


        return $kilpailun_sarjat;
    }

    public static function getAllClassCompetitors($sarjatunnus) {
        $query = DB::connection()->prepare('SELECT kilpailija.nimi, kilpailija.paaaine, sarjan_osallistuja.sarjatunnus, '
                . 'sarjan_osallistuja.sijoitus, sarjan_osallistuja.ktunnus FROM sarjan_osallistuja '
                . 'LEFT JOIN kilpailija '
                . 'ON sarjan_osallistuja.ktunnus = kilpailija.ktunnus '
                . 'WHERE sarjan_osallistuja.sarjatunnus = :sarjatunnus '
                . 'ORDER BY sarjan_osallistuja.sijoitus ASC');
        $query->execute(array('sarjatunnus' => $sarjatunnus));

        $rows = $query->fetchAll();

        $sarjan_osallistujat = array();

        if ($rows) {
            foreach ($rows as $row) {
                $sarjan_osallistujat[] = new Kilpailija(array(
                    'ktunnus' => $row['ktunnus'],
                    'nimi' => $row['nimi'],
                    'paaaine' => $row['paaaine'],
                    'sijoitus' => $row['sijoitus']
                ));
            }
        }
        return $sarjan_osallistujat;
    }

    public function save() {

        $query = DB::connection()->prepare('INSERT INTO kilpailun_sarja(kilpailutunnus, painoluokka, vyoarvo) VALUES(:kilpailutunnus, :painoluokka, :vyoarvo) RETURNING sarjatunnus');

        $query->execute(array('kilpailutunnus' => $this->kilpailutunnus, 'painoluokka' => $this->painoluokka, 'vyoarvo' => $this->vyoarvo));

        $row = $query->fetch();
        $this->sarjatunnus = $row['sarjatunnus'];
    }

    public function destroy() {
        $query = DB::connection()->prepare('DELETE FROM kilpailun_sarja WHERE sarjatunnus = :sarjatunnus');
        $query->execute(array('sarjatunnus' => $this->sarjatunnus));
    }

}
