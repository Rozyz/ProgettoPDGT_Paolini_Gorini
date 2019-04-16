# :fuelpump: Fuel Stations Italy :fuelpump:
## Trova la stazione di benzina più vicina a te per non rimanere mai a secco! :+1:

### Corso di Laurea in Informatica Applicata
### Piattaforme digitali per la gestione del territorio

## Studenti:
 - [Paolo Paolini matricola 276803](https://github.com/Rozyz)
 - [Francesco Gorini matricola 278123](https://github.com/francescogorini)

## Appello:
 Primo appello sessione estiva: 05/06/2019

## Obiettivi
Il progetto Fuel Stations Italy si pone i seguenti obiettivi:
  1. Cercare la stazione di benzina più vicina a te
  2. Aggiungere i distributori non ancora esistenti all'interno del nostro database

## Componenti
I componenti di questo progetto sono i seguenti:
  1. Web API, sviluppata in linguaggio **NODEJS + EXPRESS**
  2. Client Bot Telegram, sviluppato in linguaggio **PHP**
  <p align="center">
    <img width="460" height="300" src="https://github.com/Rozyz/ProgettoPDGT_Paolini_Gorini/blob/master/img/FuelStations.jpg">
  </p>

## Descrizione 

**API**

Realizzazione di una WEB API (GET e POST) e [relativa documentazione](https://app.swaggerhub.com/apis/francescogorini/FuelStationItaly/1.0#/):
 - Acquisizione di open data dal sito http://datiopen.it
   * I dati sono così strutturati:
     * *cnome*: nome della compagnia che gestisce la stazione di benzina
     * *ccomune*: comune nel quale è ubicata la stazione di benzina
     * *cprovincia*: provincia 
     * *cregione*: regione
     * *canno_inserimento*: anno del carimento nella struttura 
     * *cdata_e_ora_inserimento*: data e ora dell'inserimento
     * *cidentificatore_in_openstreetmap*: identificatore nell'open street map
     * *clatitudine*: latitudine a cui è posizionata la stazione
     * *clongitudine*: longitudine a cui è posizionata la stazione
   * I dati sono forniti in vari formati ed è stato scelto il formato JSON
 - Metodo GET:
   * L'API restituisce tutti i dati dei distributori nel comune richiesto
   * L'API restituisce le informazioni degli utenti autenticati 
 - Metodo POST:
   * L'API può ricevere i dati di una nuova stazione ed inserirla nel database
   * L'API può ricevere i dati di un nuovo utente che desidera autotenticarsi e aggiungere le informazioni al database

Il client utilizza servizi esterni, tramite le loro API HTTP. 
La richiesta è stata fatta alle API di Google: questo servizio è stato molto utile in quanto è possibile, grazie ad esso, ricavare le coordinate geografiche in termini di longitudine e latitudine dall'indirizzo di una città ([Geocoding](https://en.wikipedia.org/wiki/Geocoding)) e viceversa ([reverse Geocoding](https://en.wikipedia.org/wiki/Reverse_geocoding)).

**BOT**

**Fuel_stations_italy_bot** è il client bot per la piattaforma. Permette in pochi semplici passi di comincare con le nostre web API e di eseguirne tutte le operazioni. 
I comandi che il bot mette a disposizione sono i seguenti:
 - */start*: permette di autenticare l'utente e quindi di abilitare il canale per quell'utente.
 - */stazione*: restituisce tutte le stazioni del comune desiderato.
 - */add*: qualora esista una stazione all'interno del nostro sistema, questo comando permette di inserirla.
 - */esci*: permette dit tornare alla configurazioone iniziale.
 <p>
    <img width="216" height="396" src="https://github.com/Rozyz/ProgettoPDGT_Paolini_Gorini/blob/master/img/screenshot1.png">
    <img width="216" height="396" src="https://github.com/Rozyz/ProgettoPDGT_Paolini_Gorini/blob/master/img/screenshot2.png">
    <img width="216" height="396" src="https://github.com/Rozyz/ProgettoPDGT_Paolini_Gorini/blob/master/img/screenshot3.png">
    <img width="216" height="396" src="https://github.com/Rozyz/ProgettoPDGT_Paolini_Gorini/blob/master/img/screenshot4.png">
 </p>
 <!--<p align="right">
    <img width="216" height="396" src="https://github.com/Rozyz/ProgettoPDGT_Paolini_Gorini/blob/master/img/screenshot2.png">
 </p>-->
## Link del progetto

Link per il bot:
Link per l'api: https://fuel-stations-italy.herokuapp.com/
Link per la documentazione: https://app.swaggerhub.com/apis/francescogorini/FuelStationItaly/1.0#/
