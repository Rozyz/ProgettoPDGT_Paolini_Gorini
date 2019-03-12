const express = require('express')
const app = express()

app.get("/",(req,res) => {
  console.log("")
  res.send("Prenotazione campi sportivi")
})

app.get("/utenti",(req,res) => {
  res.send(" ")
})

const PORT = process.env.PORT || 3000
//localhost:3000
app.listen(PORT, () => {
  console.log("Il server è on e sta agendo sulla porta 3000...")
})
