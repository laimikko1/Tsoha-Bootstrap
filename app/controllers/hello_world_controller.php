<?php

class HelloWorldController extends BaseController {

    public static function index() {
        // make-metodi renderöi app/views-kansiossa sijaitsevia tiedostoja
        View::make('Suunnitelma/index.html');
    }

    public static function sandbox() {
        // Testaa koodiasi täällä
        View::make('helloworld.html');
    }

    public static function esittely() {
        View::make('Suunnitelma/esittely.html');
    }

    public static function kirjautuminen() {
        View::make('Suunnitelma/kirjautumissivu.html');
    }
    
    public static function ranking() {
        View::make('Suunnitelma/rankinglistaus.html');
    }
    
    public static function kilpailut() {
        View::make('Suunnitelma/kilpailujen_tulokset.html');
    }
    

}
