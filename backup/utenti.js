//
const express = require('express')
const mysql = require('mysql')
const config = require('../configuration.js')
const router = express.Router()
const request = require('request')

// Costante che contiene le info per la connessione al DB di heroku
const pool = mysql.createPool({
    connectionLimit: 10,
    host: config.my_host(),
    user: config.my_user(),
    password: config.my_pass(),
    database: config.my_db()
})


// Metodo get che resistuisce in formato JSON
// la lista degli utenti iscritti
router.get("/utenti", (req, res) => {
    const connection = getConnection()
    const queryString = "SELECT * FROM utente"
    connection.query(queryString, (err, rows, fields) => {
      if (err) {
        console.log("Errore della query sugli utenti: " + err)
        res.sendStatus(500)
        return
      }
      res.json(rows)
    })
  })
router.get("/prova", (req, res) => {
	var fs = require("fs")
	console.log("\n Start");
	var contents = fs.readFileSync("file.json")
	var jsonContent = JSON.parse(contents)
	var i = 0
	while (jsonContent[i]){
		console.log(jsonContent[i].username)
		console.log(jsonContent[i].email)
		console.log(jsonContent[i].password)
		i++
	}
	res.send("OK")
})
router.get("/c", (req, res) => {
   const connection = getConnection()
  const queryString = "CREATE TABLE prenotazione(data DATETIME PRIMARY KEY, idUtente int, FOREIGN KEY (idUtente) REFERENCES utente(id))"
 // const queryString = "DROP TABLE utente"
	connection.query(queryString, (err, rows, fields) => {
   	if(err){
	console.log("Err: " + err)
	res.sendStatus(500)
	return
	}
	console.log("OK")
   })
})
router.get("/prenotazioni", (req, res) => {
   const connection = getConnection()
   const queryString = "SELECT * FROM prenotazione"
   connection.query(queryString, (err, rows, fields) => {
   	if(err){
	console.log("Err: " + err)
	res.sendStatus(500)
	return
	}
	res.json(rows)
   })
})
// Metodo get che ritorna in formato JSON l'utente con un determinato id
router.get('/utente/:id', (req, res) => {
    console.log("Cerco utente con id: " + req.params.id)

    const connection = getConnection()
    const userId = req.params.id
    const queryString = "SELECT * FROM utente WHERE id = ?"
    connection.query(queryString, [userId], (err, rows, fields) => {
        if (err) {
        console.log("Failed to query for users: " + err)
        res.sendStatus(500)
        return
        // throw err
        }

        console.log("OK")
        const users = rows.map((row) => {
        return {nome: row.nome, cognome: row.cognome}
        })

        res.json(users)
    })
})

// Metodo post per la creazione di un utente
router.post('/crea_utente', (req, res) => {
    console.log("Trying to create a new user...")
    console.log("How do we get the form data???")

    console.log("First name: " + req.body.create_first_name)
    const firstName = req.body.create_first_name
    const lastName = req.body.create_last_name

    const queryString = "INSERT INTO utente (nome, cognome) VALUES (?, ?)"
    getConnection().query(queryString, [firstName, lastName], (err, results, fields) => {
      if (err) {
        console.log("Failed to insert new user: " + err)
        res.sendStatus(500)
        return
      }
      res.send("Nuovo utente inserito con id: ", result.insertId)
      console.log("Nuovo utente inserito con id: ", results.insertId)
      res.end()
    })
  })

/*router.get('/cancella', (req, res) => {
    const queryString = "DELETE FROM prenotazione"
    getConnection().query(queryString, (err, results, fields) => {
        if(err) {
        console.log("ERR: " + err)
	res.sendStatus(500)
	return
        }
	console.log("OK")
    })
})*/
router.post('/crea_prenotazione', (req, res) => {
    const id = req.body.idUtente
    const data = req.body.data
    const queryString = "INSERT INTO prenotazione VALUES (?, ?)"
    getConnection().query(queryString, [data, id], (err, results, fields) => {
        if (err) {
	console.log("Errore in inserimento prenotazione: " + err)
	res.sendStatus(500)
	return
	}
	res.send("Nuova prenotazione inserita")
	console.log("Nuova prenotazione inserita")
	res.end()
    })
})
// Funzione per gestire la connessione al DB di Heroku
function getConnection() {
    return pool
}

module.exports = router
