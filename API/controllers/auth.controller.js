const { test_users } = require("../models/models.service");
const { createJwtToken } = require("../utils/token.util");
const { generateOTP, fast2sms } = require("../utils/otp.util");

module.exports = {
  loginWithPhoneOtp: (req, res, next) => {
      try {
          const body = req.body;
          const otp = generateOTP(6);
          console.log(body);
          (async() => {
          // check duplicate phone Number
          const phoneExist = await test_users.findOne({ phone: body.phone });
          if (!phoneExist) {
            const user = await test_users.create({
              phone: body.phone,
              phoneOtp: otp,
              token: "",
              created_at: new Date(),
              updated_at: new Date()
            });
            res.status(201).json({
              type: "success",
              message: "OTP send to your phone number",
              data: {
              user: user,
              },
            });
          } else {
            phoneExist.phoneOtp = otp;
            await phoneExist.save();
            res.status(201).json({
              type: "success",
              message: "OTP send to your phone number",
              data: {
              user: phoneExist,
              },
            });
          }          
          })()           
      } catch (error) {
      next(error);
      }
  },
  verifyPhoneOtp: async (req, res, next) => {
      try {
          const body = req.body;
          const user_id = body.userId;
          console.log(body);
          (async() => {
          const user = await test_users.findById(user_id);  
          // check otp 
          if (user.phoneOtp !== body.otp) {
            res.status(201).json({
              type: "success",
              message: "Incorrect OTP"
            });
          } else {
            const token = createJwtToken({ userId: user._id });
            user.token = token;
            user.phoneOtp = "";
            await user.save();
      
            res.status(201).json({
            type: "success",
            message: "OTP verified successfully",
            data: {
              token,
              user: user,
            },
            });
          }
          })()
      } catch (error) {
        next(error);
      }
  },
  fetchCurrentUser: async (req, res, next) => {
      try {
        const currentUser = res.locals.user;     
        return res.status(200).json({
          type: "success",
          message: "fetch current user",
          data: {
            user:currentUser,
          },
        });
      } catch (error) {
        next(error);
      }
  }
};
