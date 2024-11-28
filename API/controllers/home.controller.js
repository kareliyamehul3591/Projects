const { category, product, cms } = require("../models/models.service");

module.exports = {
    home: (req, res) => {
        try {
            const asynchronousFunction = async() => {
                var cms_data = await cms.find();
                var cat_data = await category.aggregate([
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
                var popular_data = await product.aggregate([
                    { $match: { tags: "Popular" } },
                    {
                        $project: {
                            _id: 1,
                            product_name: 1,
                            product_id: 1,
                            product_img: 1,
                            price: 1,
                            discount: 1,
                            tags: 1
                        }
                    }
                ]);
                var sub_data = await product.aggregate([
                    { $match: { tags: "Subscription" } },
                    {
                        $project: {
                            _id: 1,
                            product_name: 1,
                            product_id: 1,
                            product_img: 1,
                            price: 1,
                            discount: 1,
                            tags: 1
                        }
                    }
                ]);

                return { "cms_list": cms_data, "cat_list": cat_data, "popular_list": popular_data, "sub_list": sub_data }
            }
            (async() => {
                var data = await asynchronousFunction();
                return res.status(200).json({
                type: "success",
                message: "Home Page",
                home_data: {
                    cms: data.cms_list,
                    category: data.cat_list,
                    popular: data.popular_list,
                    subscription: data.sub_list,
                }
              });
            })()
        } catch (error) {
            res.send(error)
        }
    },
};