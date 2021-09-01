const express = require('express');
const bodyParser = require('body-parser');
const app = express();
const cors = require('cors');

app.use('/uploads', express.static('uploads')); 

const loginRoutes = require('./routes/loginRoutes');
const empRoutes = require('./routes/empRoutes');
const leaveRoutes = require('./routes/leaveRoutes');
const salaryRoutes = require('./routes/salaryRoutes');
const enquiryRoutes = require('./routes/enquiryRoutes');
const salesRoutes = require('./routes/salesRoutes');
const purchaseRoutes = require('./routes/purchaseRoutes');
const invoicesettingRoutes = require('./routes/invoicesettingRoutes');
const mangalRoutes = require('./routes/mangalRoutes');
app.use(bodyParser.json())
app.use(
  bodyParser.urlencoded({
    extended: true,
  })
);

app.use(cors());
app.use(function (req, res, next) {
  res.setHeader('Access-Control-Allow-Origin', '*');
  res.setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, PUT, PATCH, DELETE');
  res.header("Access-Control-Allow-Headers", "Origin, X-Requested-With, Content-Type, Accept");
  res.setHeader('Access-Control-Allow-Credentials', true);
  next();
});

app.get('/', (request, response) => {
    response.json({ info: 'Node.js, Express, and Postgres API' })
  });
app.use('/login', (loginRoutes));
app.use('/emp', (empRoutes));
app.use('/leave', (leaveRoutes));
app.use('/salary', (salaryRoutes));
app.use('/enquiry', (enquiryRoutes));
app.use('/sales', (salesRoutes));
app.use('/purchase', (purchaseRoutes));
app.use('/invoice', (invoicesettingRoutes));
app.use('/mangal', (mangalRoutes));
module.exports = app;
