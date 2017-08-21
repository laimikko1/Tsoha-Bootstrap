    <?php

class Kilpailija extends BaseModel {

    public $ktunnus, $nimi, $kayttajanimi, $salasana, $paaaine;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validate_name', 'validate_kayttajatunnus', 'validate_salasana');
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

        $query->execute(array('nimi' => $this->nimi, 'kayttajanimi' => $this->kayttajanimi, 'salasana' => $this->salasana, 'paaaine' => $this->paaaine));

        $row = $query->fetch();
        $this->ktunnus = $row['ktunnus'];
    }

    public function update() {
        $query = DB::connection()->prepare('UPDATE Kilpailija SET nimi = :nimi, kayttajanimi = :kayttajanimi, salasana = :salasana, paaaine = :paaaine WHERE ktunnus = :ktunnus');

        $query->execute(array('nimi' => $this->nimi, 'kayttajanimi' => $this->kayttajanimi, 'salasana' => $this->salasana, 'paaaine' => $this->paaaine, 'ktunnus' => $this->ktunnus));
        $row = $query->fetch();
    }

    public function destroy() {
        $query = DB::connection()->prepare('DELETE FROM Kilpailija WHERE ktunnus = :ktunnus');
        $query->execute(array('ktunnus' => $this->ktunnus));
        $row = $query->fetch();
    }

    public function validate_name() {
        if (parent::validate_string_length($this->nimi) == FALSE) {
            return 'Nimi ei saa olla tyhjä tai alle 3 merkkiä!';
        }
        return NULL;
    }

    public function validate_kayttajatunnus() {
        if (parent::validate_string_length($this->kayttajanimi) == FALSE) {
            return 'Käyttäjätunnus ei saa olla tyhjä tai alle 3 merkkiä!';
        }
        return null;
    }

    public function validate_salasana() {
        if (parent::validate_string_length($this->salasana) == FALSE) {
            return 'Salasana ei saa olla tyhjä tai alle 3 merkkiä!';
        }
        return null;
    }

    public static function authenticate($kayttajanimi, $salasana) {
        $query = DB::connection()->prepare('SELECT * FROM Kilpailija WHERE kayttajanimi = :kayttajanimi AND salasana = :salasana LIMIT 1');
        $query->execute(array('kayttajanimi' => $kayttajanimi, 'salasana' => $salasana));
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
        } else {
            return NULL;
        }
    }

}
