// load our app server using express somehow....
const express = require('express')
const app = express()
//const mysql = require('mysql')
const bodyParser = require('body-parser')

const request = require('request')
const https = require('https')

const firebase = require('firebase-admin')
const serviceAccount = require('./serverfirebase.json')

const jwt = require('jsonwebtoken')

app.use(express.static('./public'))


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
app.post("/login", (req, res)=>{
	// mock user
	const user = {
		id : 2,
		username : 'fra'
	}


	jwt.sign({user}, 'secretkey', (err, token) =>{
		res.json({
			token
		})
	})
})
/*app.post("/login", (req, res)=>{
	const utente = req.body.username
	const password = req.body.password

	console.log(utente)
	console.log(password)

	if(utente == "paolo" && password == "danabella")
		console.log("SI")
	else
		console.log("NO")

	res.send("OK")
})*/


app.post("/add", verifyToken, (req, res)=>{
	jwt.verify(req.token, 'secretkey', (err, authData)=>{
		if(err){
			res.sendStatus(403)
		}else{
			// devo capire come utilizzare la post 
			// ma l'auth funziona
			/*
			const cnome = req.body.cnome
			const ccomune = req.body.ccomune
			const cprovincia = req.body.cprovincia

			console.log(cnome)
			console.log(ccomune)
			console.log(cprovincia)
	
			var newPostRef = db.ref().push()
			newPostRef.set({cnome: cnome, ccomune: ccomune, cprovincia: cprovincia})
			*/
			res.json({
				authData 
			})
		}
	})
})


function verifyToken(req, res, next){
	const bearerHeader = req.headers['authorization']
	if(typeof bearerHeader !== 'undefined'){
		// trasforma una stringa in un array
		const bearer = bearerHeader.split(' ')

		const bearerToken = bearer[1]

		req.token = bearerToken

		next();
	}else{
		//VIETATO
		res.sendStatus(403)
	}
}

const PORT = process.env.PORT || 3009
// localhost:PORT
app.listen(PORT, () => {
  console.log("Il server è online e ascolta sulla porta: " + PORT)
})
