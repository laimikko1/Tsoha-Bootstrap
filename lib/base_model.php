<?php

class BaseModel {

    // "protected"-attribuutti on käytössä vain luokan ja sen perivien luokkien sisällä
    protected $validators;

    public function __construct($attributes = null) {
        // Käydään assosiaatiolistan avaimet läpi
        foreach ($attributes as $attribute => $value) {
            // Jos avaimen niminen attribuutti on olemassa...
            if (property_exists($this, $attribute)) {
                // ... lisätään avaimen nimiseen attribuuttin siihen liittyvä arvo
                $this->{$attribute} = $value;
            }
        }
    }

    public function validate_string_length($string, $message) {
        if ($message == "Pääaine") {
            $v = new Valitron\Validator(array('string' => $string));
            $v->rule('lengthMax', 'string', 50)->message($message . '  saa olla enintään 50 merkkiä pitkä!');
            if (!$v->validate()) {
                return $v->errors('string');
            }
            return null;
        }
        $v = new Valitron\Validator(array('string' => $string));

        $v->rule('required', 'string')->message($message . ' on pakollinen kenttä!');
        $v->rule('lengthMin', 'string', 2)->message($message . ' tulee olla vähintään 3 merkkiä pitkä!');
        $v->rule('lengthMax', 'string', 50)->message($message . ' saa olla enintään 50 merkkiä pitkä!');
        if (!$v->validate()) {
            return $v->errors('string');
        }
        return null;
    }

    public function errors() {
//         Lisätään $errors muuttujaan kaikki virheilmoitukset taulukkona
        Kint::dump($this->validators);
        $errors = $this->validators;
        $yhdistetty = array();



        foreach ($errors as $val) {
            if ($this->{$val}() == NULL) {
                continue;
            }
            foreach ($this->{$val}() as $value) {
                $yhdistetty[] = $value;
            }
        }

        return $yhdistetty;
    }

}
