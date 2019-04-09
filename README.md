# ProgettoPDGT_Paolini_Gorini 
# :fuelpump: Fuel Stations Italy :fuelpump:
# Trova la stazione di benzina più vicina a te per non rimanere mai a secco! :+1:

### Corso di Laurea in Informatica Applicata
### Piattaforme digitali per la gestione del territorio

## Studenti:
 - [Paolo Paolini matricola 276803](https://github.com/Rozyz)
 - [Francesco Gorini matricola 278123](https://github.com/francescogorini)

## Obiettivi
Il progetto Fuel Stations Italy si pone i seguenti obiettivi:
  1. Cercare la stazione di benzina più vicina a te
  2. Aggiungere i distributori non ancora esistenti all'interno del nostro database

## Componenti
I componenti di questo progetto sono i seguenti:
  1. Web API, sviluppata in linguaggio **NODEJS + EXPRESS**
  2. Client Bot Telegram, sviluppato in linguaggio **PHP**
  
## Descrizione 

**API**

Realizzazione di una WEB API (GET e POST) e [relativa documentazione](https://app.swaggerhub.com/apis/francescogorini/FuelStationItaly/1.0#/):
 - Acquiosizione di open data dal sito http://datiopen.it
 - Metodo GET:
   * L'API restituisce tutti i dati dei distributori nel comune richiesto
   * L'API restituisce le informazioni degli utenti autenticati 
 - Metodo POST:
   * L'API può ricevere i dati di una nuova stazione ed inserirla nel database
   * L'API può ricevere i dati di un nuovo utente che desidera autotenticarsi e aggiungere le informazioni al database
  
**BOT**

**Fuel_stations_italy_bot** è il client bot per la piattaforma. Permette in pochi semplici passi di comincare con le nostre web API e di eseguirne tutte le operazioni. 
I comandi che il bot mette a disposizione sono i seguenti:
 - */start*: permette di autenticare l'utente e quindi di abilitare il canale per quell'utente.
 - */stazione*: restituisce tutte le stazioni del comune desiderato.
 - */add*: qualora esista una stazione all'interno del nostro sistema, questo comando permette di inserirla.

Link per l'api: https://fuel-stations-italy.herokuapp.com/

Link per la documentazione: https://app.swaggerhub.com/apis/francescogorini/FuelStationItaly/1.0#/
