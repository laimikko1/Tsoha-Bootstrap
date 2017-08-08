<?php


class HelloWorldController extends BaseController {

    public static function index() {
        // make-metodi renderöi app/views-kansiossa sijaitsevia tiedostoja
        View::make('Suunnitelma/index.html');
    }

    public static function sandbox() {
        $etsiYksi = Kilpailija::find(1);
        $etsiKaikki = Kilpailija::all();

        Kint::dump($etsiYksi);
        Kint::dump($etsiKaikki);
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

    public static function rekisteroityminen() {
        View::make('Kilpailija/rekisteroityminen.html');
    }

}
