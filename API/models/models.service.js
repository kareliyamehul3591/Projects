const mongoose = require('mongoose');

// category
const categorys = new mongoose.Schema({
    category_id: {
        type: String,
        require: true
    },
    category_img: {
        type: String,
        require: true
    },
    category_name: {
        type: String,
        require: true
    },
    description: {
        type: String,
        require: true
    },
    status: {
        type: String,
        require: true
    },
    create_at: {
        type: Date,
        default: Date.now
    },
    update_at: {
        type: Date,
        default: Date.now
    }
});
const category = mongoose.models.category || mongoose.model("category", categorys);

//Tax

const taxs = new mongoose.Schema({
    tax_name: {
        type: String,
        require: true
    },
    percentage: {
        type: mongoose.Types.Decimal128,
        require: true
    },
    create_at: {
        type: Date,
        default: Date.now
    },
    update_at: {
        type: Date,
        default: Date.now
    }
});
const tax = mongoose.models.tax || mongoose.model("tax", taxs);

//Seller Commission

const sellerCommissions = new mongoose.Schema({
    seller_name: {
        type: String,
        require: true
    },
    commission_type: {
        type: String,
        require: true
    },
    commission: {
        type: String,
        require: true
    },
    create_at: {
        type: Date,
        default: Date.now
    },
    update_at: {
        type: Date,
        default: Date.now
    }
});
const sellerCommission = mongoose.models.sellerCommission || mongoose.model("sellerCommission", sellerCommissions);

//customer
const customerss = new mongoose.Schema({
    customer_name: {
        type: String,
        require: true
    },
    customer_email: {
        type: String,
        require: true
    },
    customer_phone: {
        type: String,
        require: true
    },
    shipping_address: {
        type: String,
        require: true
    },
    status: {
        type: String,
        require: true
    },
}, { timestamps: { createdAt: 'created_at', updatedAt: 'updated_at' } });

const customer = mongoose.models.customers || mongoose.model("customers", customerss);

//product
const productss = new mongoose.Schema({
    product_id: {
        type: String,
        require: true
    },
    product_name: {
        type: String,
        require: true
    },
    brand_name: {
        type: String,
        require: true
    },
    variants: {
        type: String,
        require: true
    },
    price: {
        type: String,
        require: true
    },
    discount: {
        type: String,
        require: false
    },
    saving: {
        type: String,
        require: false
    },
    product_category: {
        type: String,
        require: true
    },
    product_type: {
        type: String,
        require: true
    },
    description: {
        type: String,
        require: true
    },
    State_origin: {
        type: String,
        require: false
    },
    reviews: {
        type: String,
        require: false
    },
    no_items: {
        type: String,
        require: true
    },
    product_img: {
        type: String,
        require: true
    },
    product_video: {
        type: String,
        require: true
    },
    product_gallery: {
        type: Array,
        require: true
    },
    tags: {
        type: String,
        require: true
    },
}, { timestamps: { createdAt: 'created_at', updatedAt: 'updated_at' } });

const product = mongoose.models.products || mongoose.model("products", productss);

//order
const orders = new mongoose.Schema({
    orderid: {
        type: String,
        require: true
    },
    customer: {
        type: String,
        require: true
    },
    category:{
        type: String,
        require: true
    },
    product: {
        type: String,
        require: true
    },
    order_date: {
        type: String,
        require: true
    },
    amount: {
        type: String,
        require: true
    },
    payment_method: {
        type: String,
        require: true
    },
    delivered_status:{
        type: String,
        require: true
    },
    create_at: {
        type: Date,
        default: Date.now
    },
    update_at: {
        type: Date,
        default: Date.now
    }
});
const order = mongoose.models.order || mongoose.model("order", orders);

//CMS
const cms_data = new mongoose.Schema({}, { collection: 'cms' , strict: false});
const cms = mongoose.models.cms || mongoose.model("cms", cms_data);

//Test user
const test_user = new mongoose.Schema({
    name: {
        type: String,
        require: true
    },
    phone: {
        type: String,
        require: true
    },
    phoneOtp: {
        type: String
    },
    address: {
        type: String
    },
    token: {
        type: String
    },
    create_at: {
        type: Date,
        default: Date.now
    },
    update_at: {
        type: Date,
        default: Date.now
    }
});
const test_users = mongoose.model("test_users", test_user);

const itemSchema = mongoose.Schema({
    productId: {
      type: mongoose.Schema.Types.ObjectId,
      ref: "product",
    },
    quantity: {
      type: Number,
      default: 0,
    },
});

const cartSchema = mongoose.Schema({
    products: [itemSchema],
    userId: {
      type: mongoose.Schema.Types.ObjectId,
      ref: "test_users",
    },
    total: {
      type: Number,
      default: 0,
    },
    __v: { type: Number, select: false },
});
const Cart = mongoose.model("Cart", cartSchema);

module.exports = {
    category,
    tax,
    sellerCommission,
    customer,
    product,
    order,
    cms,
    test_users,
    Cart
};