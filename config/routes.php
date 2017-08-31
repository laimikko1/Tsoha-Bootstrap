<?php

$routes->get('/', function() {
    yllapitajan_controller::aloitus();
});

$routes->get('/kilpailut', function() {
    kilpailu_controller::kilpailut();
});
//
$routes->get('/kilpailut/menneet_kilpailut', function() {
    kilpailu_controller::menneet_Kilpailut();
});

$routes->get('/rekisteroityminen', function() {
    login_controller::rekisteroityminen();
});

$routes->get('/kirjautuminen', function() {
    login_controller::login();
});

$routes->get('/yllapitajan_sivu', function() {
    yllapitajan_controller::index();
});

$routes->post('/rekisteroityminen', function() {
    kilpailija_controller::store();
});

$routes->get('/kayttajan_sivu/:ktunnus', function($ktunnus) {
    kilpailija_controller::view($ktunnus);
});

$routes->get('/kayttajan_sivu_muokkaa/:ktunnus', function($ktunnus) {
    kilpailija_controller::edit($ktunnus);
});
$routes->post('/kayttajan_sivu/:ktunnus', function($ktunnus) {
    kilpailija_controller::update($ktunnus);
});

$routes->post('/kayttajan_sivu/:ktunnus/destroy', function($ktunnus) {
    kilpailija_controller::destroy($ktunnus);
});

$routes->post('/kirjautuminen', function() {
    login_controller::handle_login();
});

$routes->post('/kirjaudu_ulos', function() {
    login_controller::logout();
});

$routes->get('/uusi_kilpailu', function() {
    yllapitajan_controller::uusi();
});

$routes->post('/uusi_kilpailu', function() {
    yllapitajan_controller::store();
});

$routes->get('/kilpailun_sivu/:kilpailutunnus', function($kilpailutunnus) {
    kilpailu_controller::showKilpailunSivu($kilpailutunnus);
});

$routes->get('/kilpailun_sivu/tulokset/:kilpailutunnus', function($kilpailutunnus) {
    kilpailu_controller::showTulokset($kilpailutunnus);
});

$routes->get('/kilpailun_sivu/:kilpailutunnus/ilmoittautuminen', function($kilpailutunnus) {
    kilpailu_controller::showIlmoittautuminen($kilpailutunnus);
});

$routes->post('/kilpailun_sivu/:kilpailutunnus/destroy', function() {
    yllapitajan_controller::destroy();
});

$routes->post('/kilpailun_sivu/:kilpailutunnus/ilmoittautuminen/', function($kilpailutunnus) {
    kilpailun_sarja_controller::ilmoittaudu($kilpailutunnus);
});



$routes->post('/kayttajan_sivu/:sarjatunnus/peru/', function($kilpailutunnus) {
    kilpailun_sarja_controller::destroyIlmoittautuminen($kilpailutunnus);
});

$routes->get('/kilpailun_sivu/:kilpailutunnus/muokkaa', function($kilpailutunnus) {
    yllapitajan_controller::viewMuokattava($kilpailutunnus);
});

$routes->post('/kilpailun_sivu/:kilpailutunnus/muokkaa', function($kilpailutunnus) {
    yllapitajan_controller::update($kilpailutunnus);
});

$routes->get('/kilpailun_sivu/:kilpailutunnus/muokkaa_tuloksia', function($kilpailutunnus) {
    yllapitajan_controller::viewMuokattavaTulokset($kilpailutunnus);
});

$routes->post('/kilpailun_sivu/:kilpailutunnus/muokkaa_tuloksia', function($kilpailutunnus) {
    yllapitajan_controller::updateSijoitukset($kilpailutunnus);
});

$routes->post('/kilpailun_sarja/destroy', function() {
    kilpailun_sarja_controller::destroy();
});

$routes->post('/kilpailun_sarja/:kilpailutunnus/add', function($kilpailutunnus) {
    kilpailun_sarja_controller::add($kilpailutunnus);
});

