const config = require("../config/config");
const { Pool } = require("pg");
const pool = new Pool({
  user: config.postgres.user,
  host: config.postgres.host,
  database: config.postgres.database,
  password: config.postgres.password,
  port: config.postgres.port,
});

pool 
  .connect()
  .then(() => console.log("connected Succesfully"))
  .catch((e) => console.log(e));

module.exports = pool;
