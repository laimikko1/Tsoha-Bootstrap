-- Lisää CREATE TABLE lauseet tähän tiedostoon

CREATE TABLE Kilpailija(
ktunnus SERIAL PRIMARY KEY,
nimi varchar(50) NOT NULL,
kayttajanimi varchar(50) NOT NULL,
salasana varchar(50) NOT NULL,
paaaine varchar(50) NOT NULL
);

CREATE TABLE Ranking_pisteet(
ktunnus INTEGER REFERENCES Kilpailija(ktunnus),
pisteet INTEGER
);

CREATE TABLE Kilpailu(
kilpailutunnus SERIAL PRIMARY KEY,
kilpailun_nimi varchar(50),
kilpailupaikka varchar(50),
ajankohta timestamp,
kilpailun_kuvaus varchar(500)
);

CREATE TABLE Kilpailun_sarja(
sarjatunnus SERIAL PRIMARY KEY,
kilpailutunnus INTEGER REFERENCES Kilpailu(kilpailutunnus),
painoluokka INTEGER,
vyoarvo varchar(50)
);

CREATE TABLE Sarjan_osallistuja(
ktunnus INTEGER REFERENCES Kilpailija(ktunnus),
sarjatunnus INTEGER REFERENCES Kilpailun_sarja(sarjatunnus),
sijoitus INTEGER
);
