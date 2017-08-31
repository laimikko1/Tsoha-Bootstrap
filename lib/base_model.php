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

    public function validate_string_length($string, $message, $min, $max) {
        $v = new Valitron\Validator(array('string' => $string));

        $v->rule('lengthMin', 'string', $min)->message($message . ' tulee olla vähintään ' . ($min + 1) . ' merkkiä pitkä!');
        $v->rule('lengthMax', 'string', $max)->message($message . ' saa olla enintään ' . $max . ' merkkiä pitkä!');
        if (!$v->validate()) {
            return $v->errors('string');
        }
    }

    public function validate_required_fields($string, $message) {
        $v = new Valitron\Validator(array('string' => $string));
        $v->rule('required', 'string')->message($message . ' on pakollinen kenttä!');
        if (!$v->validate()) {
            return $v->errors('string');
        }
    }

    public function errors() {
//         Lisätään $errors muuttujaan kaikki virheilmoitukset taulukkona
        $errors = $this->validators;
        $yhdistetty = array();
//
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

    public static function merge_validations($req, $length) {
        if (!is_null($length) && !is_null($req)) {
            return array_merge($length, $req);
        }
        if (is_null($length)) {
            return $req;
        }
        return $length;
    }

}
