//
const express = require('express')
const mysql = require('mysql')

const router = express.Router()
// Costante che contiene le info per la connessione al DB di heroku
const pool = mysql.createPool({
    connectionLimit: 10,
    host: 'us-cdbr-iron-east-03.cleardb.net',
    user: 'b8b17ae25e2744',
    password: '58d1b5dd',
    database: 'heroku_415db843f7283ae'
})

// Metodo get che resistuisce in formato JSON
// la lista degli utenti iscritti
router.get("/utenti", (req, res) => {
    const connection = getConnection()
    const queryString = "SELECT * FROM utenti"
    connection.query(queryString, (err, rows, fields) => {
      if (err) {
        console.log("Errore della query sugli utenti: " + err)
        res.sendStatus(500)
        return
      }
      res.json(rows)
    })
  })
// Metodo get che ritorna in formato JSON l'utente con un determinato id
router.get('/user/:id', (req, res) => {
    console.log("Fetching user with id: " + req.params.id)

    const connection = getConnection()
    const userId = req.params.id
    const queryString = "SELECT * FROM users WHERE id = ?"
    connection.query(queryString, [userId], (err, rows, fields) => {
        if (err) {
        console.log("Failed to query for users: " + err)
        res.sendStatus(500)
        return
        // throw err
        }

        console.log("I think we fetched users successfully")
        const users = rows.map((row) => {
        return {firstName: row.first_name, lastName: row.last_name}
        })

        res.json(users)
    })
})

// Metodo post per la creazione di un utente
router.post('/user_create', (req, res) => {
    console.log("Trying to create a new user...")
    console.log("How do we get the form data???")

    console.log("First name: " + req.body.create_first_name)
    const firstName = req.body.create_first_name
    const lastName = req.body.create_last_name

    const queryString = "INSERT INTO users (first_name, last_name) VALUES (?, ?)"
    getConnection().query(queryString, [firstName, lastName], (err, results, fields) => {
      if (err) {
        console.log("Failed to insert new user: " + err)
        res.sendStatus(500)
        return
      }

      console.log("Inserted a new user with id: ", results.insertId);
      res.end()
    })
  })

// Funzione per gestire la connessione al DB di Heroku
function getConnection() {
    return pool
}

module.exports = router
