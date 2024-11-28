const router = require('express').Router();

const { checkAuth } = require("../middlewares/checkAuth");
const { loginWithPhoneOtp, verifyPhoneOtp, fetchCurrentUser } = require("../controllers/auth.controller");
const { category_list, category_detail_page } = require("../controllers/category.controller");
const { product_listing, product_detail } = require("../controllers/product.controller");
const { home } = require("../controllers/home.controller");
const { addItemToCart, getCart, removeItem, decreaseQuantity } = require("../controllers/checkout.controller");

router.use(function(req, res, next) {


    res.setHeader('Access-Control-Allow-Origin', '*');


    res.setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, PUT, PATCH, DELETE');

    res.setHeader('Access-Control-Allow-Headers', 'X-Requested-With,content-type');

    res.setHeader('Access-Control-Allow-Credentials', true);

    // Pass to next layer of middleware
    next();
});

//Login
// router.post("/register", createNewUser);
router.post("/login", loginWithPhoneOtp);
router.post("/verify", verifyPhoneOtp);
router.post("/me", checkAuth, fetchCurrentUser);

//category
router.post("/category_list", category_list);
router.post("/category_detail_page", category_detail_page);

//Product
router.post("/product_listing", product_listing);
router.post("/product_detail", product_detail);

//Home
router.post("/home", home);

//Checkout
router.post("/:userId", addItemToCart);
router.get("/:userId", getCart);
router.patch("/:userId", decreaseQuantity);
router.delete("/:userId", removeItem);

module.exports = router;