const http=require('http');
const app=require('./app');
const config = require('./config/config');
const server = http.createServer(app);

server.listen(config.port, res=>{
    console.log(`server listening on ${config.port}`);
});
