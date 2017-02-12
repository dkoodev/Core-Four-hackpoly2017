const express = require('express');
const path = require('path');
const request = require('request');
const bodyParser = require('body-parser');
// const YouTube = require('youtube-node')
// const yt = new YouTube()
const config = require('./config');
// const ytApi = config.key
const app = express();

app.set('port', (process.env.PORT || 3333));
app.use(bodyParser.json())
app.use(bodyParser.urlencoded({ extended: true })); // support encoded bodies
app.use(express.static('./dist'));
// yt.setKey(ytApi)

app.get('/', function(req,res,next){
   var name = req.query.name;
   console.log(name);
}); 

// app.get('/2', (req, res) => {
//   res.sendFile(path.join(__dirname, './index2.html'))
// })

app.listen(app.get('port'), () => {
  console.log('Running app...')
})
