// load our app server using express somehow....
const express = require('express')
const app = express()
const mysql = require('mysql')
const bodyParser = require('body-parser')
const router = require('./routes/utenti.js')
const request = require('request')
const https = require('https')

app.use(bodyParser.urlencoded({extended: false}))
app.use(express.static('./public'))
app.use(router)

function richiesta(res) {

      var uri = "https://ghibliapi.herokuapp.com/films"
      var options = {
        uri: uri,
        json: true
      };

      request(options, function(error, response, body) {
        var status = body["status"]
        var titolo = body['0']["title"]
        var data_store = {}
        data_store["lista"] = []
        data_store["lista"]["titolo"] = titolo
        console.log(data_store)
        res.json(data_store)
      });
}

function rich(res){
  var data_store = {};
  data_store["lista"] = [];

  https.get('https://ghibliapi.herokuapp.com/films', response => {
    console.log(res.statusCode)
    let body = ''
    response.on('data', data => {
      body += data
    })

    response.on('end', () => {
      var data = JSON.parse(body);
      var titolo = data['0']['title']
      res.send(titolo)
    })
  }).on('error', error => console.error(error.message))
}


app.get("/", (req, res) => {
  /*console.log("Responding to root route") */
  res.send("Root route")
})

app.get("/titolo",(req,res)=>{
  richiesta(res)
})


const PORT = process.env.PORT || 3005
// localhost:PORT
app.listen(PORT, () => {
  console.log("Il server è online e ascolta sulla porta: " + PORT)
})
