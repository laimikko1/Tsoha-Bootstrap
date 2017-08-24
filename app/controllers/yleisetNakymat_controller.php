<?php

/**
 * yleisetNakymat_controller on vastuussa yleisten näkymien esittämisestä, jotka ovat kaikkien käytössä,
 * eivätkä vaadi kirjautumista
 * 
 *  
 */
class yleisetNakymat_controller extends BaseController {

    /**
     * index-metodi renderöi app/views kansiossa olevan etusivun
     * 
     */
    public static function index() {
        // make-metodi renderöi app/views-kansiossa sijaitsevia tiedostoja
        View::make('Suunnitelma/index.html');
    }

    /**
     * esittely-metodi renderöi app/views kansiossa olevan esittelysivun
     */
    public static function esittely() {
        View::make('Suunnitelma/esittely.html');
    }

    public static function ranking() {
        View::make('Suunnitelma/rankinglistaus.html');
    }

    public static function rekisteroityminen() {
        View::make('Kilpailija/rekisteroityminen.html');
    }

    public static function kilpailut() {
        $kilpailut = kilpailu::all(0);
        View::make('Kilpailu/tulossa_olevat_kilpailut.html', array('kilpailut' => $kilpailut));
    }

    public static function menneet_Kilpailut() {
        $kilpailut = kilpailu::all(1);
        View::make('Kilpailu/menneet_kilpailut.html', array('kilpailut' => $kilpailut));
        
    }

}
