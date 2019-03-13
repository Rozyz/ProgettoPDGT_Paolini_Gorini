// load our app server using express somehow....
const express = require('express')
const app = express()
const mysql = require('mysql')
const bodyParser = require('body-parser')
const router = require('./routes/utenti.js')

app.use(bodyParser.urlencoded({extended: false}))
app.use(express.static('./public'))
app.use(router)

app.get("/", (req, res) => {
  console.log("Responding to root route")
  res.send("Prenotazione campi sportivi")
})

const PORT = process.env.PORT || 3000
// localhost:PORT
app.listen(PORT, () => {
  console.log("Il server è online e ascolta sulla porta: " + PORT)
})
