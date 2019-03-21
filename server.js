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

var db = firebase.database();

var ref = db.ref();
ref.orderByChild("ccomune").equalTo("Lanciano").on("child_added", function(data) {
  console.log(data.val().ccomune);
})

/*
ref.on("value",function(snapshot){
  console.log(snapshot.val());
}, function(errorObject){
  console.log("The read failed: " + errorObject.code);
})

console.log("Hello")
*/

/*
const pool = mysql.createPool({
    connectionLimit: 10,
    host: config.my_host(),
    user: config.my_user(),
    password: config.my_pass(),
    database: config.my_db()
})
*/

app.get("/", (req, res) => {
  /*console.log("Responding to root route") */
  res.send("Fuel stations")
})


const PORT = process.env.PORT || 3004
// localhost:PORT
app.listen(PORT, () => {
  console.log("Il server è online e ascolta sulla porta: " + PORT)
})
/*
function getConnection() {
    return pool
}
*/
