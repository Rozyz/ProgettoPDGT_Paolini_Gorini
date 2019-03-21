// load our app server using express somehow....
const express = require('express')
const app = express()
//const mysql = require('mysql')
const bodyParser = require('body-parser')
/*
const request = require('request')
const https = require('https')
*/
const firebase = require('firebase-admin')
const serviceAccount = require('./serverfirebase.json')

// Inizializzazione del DB di firebase
firebase.initializeApp({
  credential: firebase.credential.cert(serviceAccount),
  databaseURL: "https://fuel-stations-italy.firebaseio.com"
});

// Dichiarazione costanti riguardanti il db
const db = firebase.database();
const rootRef = firebase.database().ref();

app.use(bodyParser.urlencoded({extended: false}))

app.get("/", (req, res) => {
  /*console.log("Responding to root route") */
  res.send("Fuel stations")
})

// GET: restituisce tutti i distributori di quel comune
app.get("/:comune", (req, res)=>{
  rootRef.orderByChild("ccomune")
         .equalTo(req.params.comune)
         .once("value", snap => {
           console.log(snap.val())
           res.send(snap.val())
         })
})


const PORT = process.env.PORT || 3009
// localhost:PORT
app.listen(PORT, () => {
  console.log("Il server è online e ascolta sulla porta: " + PORT)
})
