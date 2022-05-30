const pool =  require("../../../config/database");

module.exports = {
    languagebyquery: (query,callback) => {
        pool.query(
            query,
            [],
            (error, result, fields) => {
                if (error) {
                    return callback(error);
                } else {
                    return callback(null, result);
                }
            });
    }, 
    therapist_typebyquery: (query,callback) => {
        pool.query(
            query,
            [],
            (error, result, fields) => {
                if (error) {
                    return callback(error);
                } else {
                    return callback(null, result);
                }
            });
    }
};