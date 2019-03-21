// load our app server using express somehow....
const express = require('express')
const app = express()
const mysql = require('mysql')
const bodyParser = require('body-parser')
const request = require('request')
const https = require('https')

app.use(bodyParser.urlencoded({extended: false}))

app.get("/", (req, res) => {
  /*console.log("Responding to root route") */
  res.send("Fuel stations")
})


const PORT = process.env.PORT || 3005
// localhost:PORT
app.listen(PORT, () => {
  console.log("Il server è online e ascolta sulla porta: " + PORT)
})
