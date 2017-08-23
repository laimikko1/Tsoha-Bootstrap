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

    public function validate_string_length($string) {
        if ($string == '' || $string == NULL) {
            return FALSE;
        }
        if (strlen($string) < 3) {
            return FALSE;
        }
        return TRUE;
    }

    

    public function errors() {
//         Lisätään $errors muuttujaan kaikki virheilmoitukset taulukkona
        $errors = $this->validators;
        $yhdistetty = array();



        foreach ($errors as $val) {
            if ($this->{$val}() == NULL) {
                continue;
            }
            $yhdistetty[] = $this->{$val}();
        }

        return $yhdistetty;
    }

}
