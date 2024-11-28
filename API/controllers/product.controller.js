const { product } = require("../models/models.service");

module.exports = {
    product_listing: (req, res) => {
        try {
            const body = req.body;
            console.log(body)
            const asynchronousFunction = async() => {
                var price_data = await product.find( { price: { $gte: body.start_price, $lte: body.end_price } } );
                var cat_data = await product.find( { product_category: { $in: [ body.cat_id ] } } );
                var discount_data = await product.find( { discount: { $in: [ body.discount ] } } );
                var tags_data = await product.find( { tags: { $in: [ body.tags ] } } );

                return { "price_list": price_data, "cat_list": cat_data, "discount_list": discount_data, "tags_list": tags_data }
            }
            (async() => {
                var data = await asynchronousFunction();
                return res.status(200).json({
                type: "success",
                message: "Product Listing",
                data: {
                    user_price: data.price_list,
                    user_cat: data.cat_list,
                    user_discount: data.discount_list,
                    user_tags: data.tags_list,
                }
              });
            })()
        } catch (error) {
            res.send(error)
        }
    },
    product_detail: (req, res) => {
        try {
            const body = req.body;
            console.log(body)
            const asynchronousFunction = async() => {
                var data = await product.findById({_id: body.prod_id});
                return data
            }
            (async() => {
              return res.status(200).json({
                type: "success",
                message: "Product Details",
                data: await asynchronousFunction()
              });
            })()
        } catch (error) {
            res.send(error)
        }
    },
};