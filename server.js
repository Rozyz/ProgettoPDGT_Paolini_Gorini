// load our app server using express somehow....
const express = require('express')
const app = express()
const mysql = require('mysql')
const bodyParser = require('body-parser')
const request = require('request')
const https = require('https')
const firebase = require('firebase-admin')
const serviceAccount = require('./serverfirebase.json')

app.use(bodyParser.urlencoded({extended: false}))

firebase.initializeApp({
  credential: firebase.credential.cert(serviceAccount),
  databaseURL: "https://fuel-stations-italy.firebaseio.com"
});
/*
const pool = mysql.createPool({
    connectionLimit: 10,
    host: config.my_host(),
    user: config.my_user(),
    password: config.my_pass(),
    database: config.my_db()
})
*/

var db = firebase.database();
var ref = db.ref("restricted_access/secret_document");
ref.once("value", function(snapshot) {
  console.log(snapshot.val());
});

var usersRef = ref.child("users");
usersRef.set({
  alanisawesome: {
    date_of_birth: "June 23, 1912",
    full_name: "Alan Turing"
  },
  gracehop: {
    date_of_birth: "December 9, 1906",
    full_name: "Grace Hopper"
  }
});


app.get("/", (req, res) => {
  /*console.log("Responding to root route") */
  res.send("Fuel stations")
})


const PORT = process.env.PORT || 3005
// localhost:PORT
app.listen(PORT, () => {
  console.log("Il server è online e ascolta sulla porta: " + PORT)
})
/*
function getConnection() {
    return pool
}
*/
