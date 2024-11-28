const { category, product } = require("../models/models.service");

module.exports = {
    category_list: (req, res) => {
        try {
            const asynchronousFunction = async() => {
                var data = await category.aggregate([
                { $sort: { "category_name": 1 } },
                {
                    $project: {
                        _id: 1,
                        category_id: 1,
                        category_img: 1,
                        category_name: 1,
                        description: 1,
                    }
                }
                ]);
                return data
            }
            (async() => {
              return res.status(200).json({
                type: "success",
                message: "Category Listing",
                data: await asynchronousFunction()
              });
            })()
        } catch (error) {
            res.send(error)
        }
    },
    category_detail_page: (req, res) => {
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
                message: "Category Detail Page",
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
};




    