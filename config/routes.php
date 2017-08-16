<?php

$routes->get('/', function() {
    HelloWorldController::index();
});

$routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
});

$routes->get('/esittely', function() {
    HelloWorldController::esittely();
});

$routes->get('/kirjautuminen', function() {
    HelloWorldController::kirjautuminen();
});

$routes->get('/kilpailut', function() {
    Kilpailu_controller::index();
});

$routes->get('/ranking', function() {
    HelloWorldController::ranking();
});

$routes->get('/rekisteroityminen', function() {
    HelloWorldController::rekisteroityminen();
});

$routes->get('/yllapitajan_sivu', function() {
    Kilpailija_controller::index();
});


$routes->post('/rekisteroityminen', function() {
    Kilpailija_controller::store();
});

$routes->get('/rekisteroityminen', function() {
    Kilpailija_controller::create();
});

$routes->get('/kayttajan_sivu/:ktunnus', function($ktunnus) {
    Kilpailija_controller::edit($ktunnus);
});

$routes->post('/kayttajan_sivu/:ktunnus', function($ktunnus) {
    Kilpailija_controller::update($ktunnus);
});
