<?php

$routes->get('/', function() {
    yleisetNakymat_controller::index();
});

$routes->get('/esittely', function() {
    yleisetNakymat_controller::esittely();
});

$routes->get('/kilpailut', function() {
    yleisetNakymat_controller::kilpailut();
});

$routes->get('/ranking', function() {
    yleisetNakymat_controller::ranking();
});

$routes->get('/rekisteroityminen', function() {
    yleisetNakymat_controller::rekisteroityminen();
});

$routes->get('/kirjautuminen', function() {
    yleisetNakymat_controller::login();
});

$routes->get('/yllapitajan_sivu', function() {
    Kilpailija_controller::index();
});


$routes->post('/rekisteroityminen', function() {
    Kilpailija_controller::store();
});

$routes->get('/kayttajan_sivu/:ktunnus', function($ktunnus) {
    Kilpailija_controller::edit($ktunnus);
});

$routes->post('/kayttajan_sivu/:ktunnus', function($ktunnus) {
    Kilpailija_controller::update($ktunnus);
});

$routes->post('/kayttajan_sivu/:ktunnus/destroy', function($ktunnus) {
    Kilpailija_controller::destroy($ktunnus);
});


$routes->post('/kirjautuminen', function() {
    LoginController::handle_login();
});

$routes->post('/kirjaudu_ulos', function() {
    LoginController::logout();
});

$routes->post('/kilpailut', function() {
    Kilpailu_controller::store();
});

$routes->get('/uusi_kilpailu', function() {
    Kilpailu_controller::uusi();
});

$routes->post('/uusi_kilpailu', function() {
    Kilpailu_controller::store();
});

$routes->get('/kilpailun_sivu/:kilpailutunnus', function($kilpailutunnus) {
    Kilpailu_controller::showKilpailunSivu($kilpailutunnus);
});

$routes->get('/kilpailun_sivu/:kilpailutunnus/ilmoittautuminen', function($kilpailutunnus) {
    Kilpailu_controller::showIlmoittautuminen($kilpailutunnus);
});

$routes->post('/kilpailun_sivu/:kilpailutunnus/ilmoittautuminen/', function($kilpailutunnus) {
    Kilpailun_sarja_controller::ilmoittaudu($kilpailutunnus);
});
