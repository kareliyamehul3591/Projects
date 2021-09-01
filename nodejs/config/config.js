var config = {
  port: process.env.PORT || 4000,
  // webAppUrl: 'http://oms.raksoftech.com', // Ending with hash followed by a slash
  webAppUrl: "http://localhost:4200",

  //Database Credentials
  postgres: {
    user: "postgres",
    password: "123456",
    host: "localhost",
    port: 5432,
    database: "appdb",
    // user: "postgres",
    // host: '79.137.30.8',
    // database: 'oms1',
    // password: 'mM!NW&6yJt8L',
    // user: 'omsappuser',
    // host: '127.0.0.1',
    // database: 'postgres',
    // password: 'Amol@123',
    // port: 5432,

    // user: "root@165.22.222.109",
    // password: "B!m2E4T6.2X4zJd",
    // host: "165.22.222.109",
    // port: 5432,
    // database: "appdb",
  },
  JWTsecretKey: "signature",
};

module.exports = config;
