<?php

class HelloWorldController extends BaseController {

    public static function index() {
        // make-metodi renderöi app/views-kansiossa sijaitsevia tiedostoja
        View::make('Suunnitelma/index.html');
    }

    public static function sandbox() {

        $mikko = new Kilpailija(array(
            'nimi' => 'm',
            'kayttajanimi' => 'm',
            'salasana' => 'm',
            'paaaine' => 'Tietojenkäsittelytiede'
        ));
        $errors = $mikko->errors();
        Kint::dump($errors);
    }

    public static function esittely() {
        View::make('Suunnitelma/esittely.html');
    }


    public static function ranking() {
        View::make('Suunnitelma/rankinglistaus.html');
    }

    public static function rekisteroityminen() {
        View::make('Kilpailija/rekisteroityminen.html');
    }

}
