const jwt = require('jsonwebtoken');

module.exports = {
  createJwtToken: (payload) => {
    const token = jwt.sign(payload, process.env.JWT_SECRET, { expiresIn: "12h" });
    return token;
  },
  verifyJwtToken: (token, next) => {
    try {
      const { userId } = jwt.verify(token, process.env.JWT_SECRET);
      return userId;
    } catch (err) {
      next(err);
    }
  }
};
