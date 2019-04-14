// Definizione delle librerie.
const express = require('express')
const app = express()
const bodyParser = require('body-parser')
const firebase = require('./configuration/node_modules/firebase-admin')
const serviceAccount = require('./configuration/serverfirebase.json')
const jwt = require('./configuration/node_modules/jsonwebtoken')


// Inizializzazione del DB di firebase
firebase.initializeApp({
  credential: firebase.credential.cert(serviceAccount),
  databaseURL: "https://fuel-stations-italy.firebaseio.com"
});

// Dichiarazione costanti riguardanti il db
const db = firebase.database();
const rootRef = firebase.database().ref();

app.use(bodyParser.urlencoded({extended: false}))
app.use(bodyParser.json())


// GET: restituisce tutti i distributori di quel comune
app.get("/comune/:comune", (req, res)=>{
  db.ref("/Stazioni").orderByChild("ccomune")
         .equalTo(req.params.comune)
         .once("value", snap => {
           console.log(snap.val())
           res.send(snap.val())
         })
})

// GET: restituisce i dati relativi all'utente
app.get("/utente/:nome", (req, res)=>{
	db.ref("/Users").orderByChild("first_name")
	.equalTo(req.params.nome)
	.once("value", snap => {
		console.log(snap.val())
		res.send(snap.val())
	})
})

// POST: inserimento di un utente
app.post("/login", (req, res)=>{
	const reqfirst = req.body.first_name
	const reqlast = req.body.last_name
	const reqid = req.body.id
	if(typeof reqid == 'undefined' || typeof reqlast == 'undefined' || typeof reqfirst == 'undefined'){
		res.sendStatus(400)
		console.log("ERR")
	}
	else{
		const user = {
			first_name : reqfirst,
			last_name: reqlast,
			id: reqid
		}
		jwt.sign({user}, 'secretkey', (err, token) =>{
    		res.json(token)
    		var newPostRef = db.ref("/Users").push()
    		newPostRef.set({first_name : req.body.first_name, last_name: req.body.last_name, id: req.body.id,token: token})
  		})
	}
})

app.post("/stazione/add", (req, res)=>{
	const cnome = req.body.cnome
	const ccomune = req.body.ccomune
	const cprovincia = req.body.cprovincia
      	const cregione = req.body.cregione
      	const clongitudine = req.body.clongitudine
      	const clatitudine = req.body.clatitudine
	if(typeof cnome == 'undefined' ||
		typeof ccomune == 'undefined' ||
		typeof cprovincia == 'undefined' ||
		typeof cregione == 'undefined' ||
		typeof clongitudine == 'undefined' ||
		typeof clatitudine == 'undefined'){
		res.sendStatus(400)
	}
	else{
		var newPostRef = db.ref("/Stazioni").push()
		newPostRef.set({cnome: cnome, ccomune: ccomune, cprovincia: cprovincia, cregione: cregione, clongitudine: clongitudine, clatitudine: clatitudine})
	 	res.json(ccomune)
	}
})

const PORT = process.env.PORT || 3002
// localhost:PORT
app.listen(PORT, () => {
  console.log("Il server è online e ascolta sulla porta: " + PORT)
})