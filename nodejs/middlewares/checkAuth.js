const jwt = require('jsonwebtoken');
const config = require('../config/config');

module.exports=(req,res,next)=>{
    try{
        const token = req.headers.authorization.split(" ")[1];
        console.log(token);
        const decoded = jwt.verify(token, config.JWTsecretKey);
        req.userData = decoded;
        console.log('here');
         console.log(decoded);
        next();
       }catch(error){
           console.log(error);
        return res.status(401).json({
            message:'Auth Failed'
        });
    }
   
};