const { test_users, category, product, Cart } = require("../models/models.service");
const { isValidObjectId } = require("mongoose");

module.exports = {
    addItemToCart: async (req, res) => {
        let userId = req.params.userId;
        let user = await test_users.exists({ _id: userId });

        if (!userId || !isValidObjectId(userId) || !user)
            return res.status(400).send({ status: false, message: "Invalid user ID" });

        let productId = req.body.productId;
        let qty = req.body.quantity;
        let total = req.body.total;
        
        if (!productId)
            return res.status(400).send({ status: false, message: "Invalid product" });

        let cart = await Cart.findOne({ userId: userId });

        if (cart) {
            let itemIndex = cart.products.findIndex((p) => p.productId == productId);

            if (itemIndex > -1) {
                let productItem = cart.products[itemIndex];
                productItem.quantity += qty;
                cart.products[itemIndex] = productItem;
            } else {
                cart.products.push({ productId: productId, quantity: qty });
            }
            cart = await cart.save();
            return res.status(200).send({ status: true, updatedCart: cart });
        } else {
            const newCart = await Cart.create({
                userId,
                products: [{ productId: productId, quantity: qty }],
                total: total
            });

            return res.status(201).send({ status: true, newCart: newCart });
        }
    },
    getCart: async (req, res) => {
        let userId = req.params.userId;
        let user = await test_users.exists({ _id: userId });

        if (!userId || !isValidObjectId(userId) || !user)
            return res.status(400).send({ status: false, message: "Invalid user ID" });

        let cart = await Cart.findOne({ userId: userId });
        if (!cart)
            return res
                .status(404)
                .send({ status: false, message: "Cart not found for this user" });

        res.status(200).send({ status: true, cart: cart });
    },
    removeItem: async (req, res) => {
        let userId = req.params.userId;
        let user = await test_users.exists({ _id: userId });
        let productId = req.body.productId;

        if (!userId || !isValidObjectId(userId) || !user)
            return res.status(400).send({ status: false, message: "Invalid user ID" });

        let cart = await Cart.findOne({ userId: userId });
        if (!cart)
            return res
                .status(404)
                .send({ status: false, message: "Cart not found for this user" });

        let itemIndex = cart.products.findIndex((p) => p.productId == productId);
        if (itemIndex > -1) {
            cart.products.splice(itemIndex, 1);
            cart = await cart.save();
            return res.status(200).send({ status: true, updatedCart: cart });
        }
        res
            .status(400)
            .send({ status: false, message: "Item does not exist in cart" });
    },
    decreaseQuantity: async (req, res) => {
        // use add product endpoint for increase quantity
        let userId = req.params.userId;
        let user = await test_users.exists({ _id: userId });
        let productId = req.body.productId;
      
        if (!userId || !isValidObjectId(userId) || !user)
          return res.status(400).send({ status: false, message: "Invalid user ID" });
      
        let cart = await Cart.findOne({ userId: userId });
        if (!cart)
          return res
            .status(404)
            .send({ status: false, message: "Cart not found for this user" });
      
        let itemIndex = cart.products.findIndex((p) => p.productId == productId);
      
        if (itemIndex > -1) {
          let productItem = cart.products[itemIndex];
          productItem.quantity -= 1;
          cart.products[itemIndex] = productItem;
          cart = await cart.save();
          return res.status(200).send({ status: true, updatedCart: cart });
        }
        res
          .status(400)
          .send({ status: false, message: "Item does not exist in cart" });
    },
};