const mongoose = require('mongoose');
async = require('async');
const user = new mongoose.Schema({
    survey_no: {
        type: String,
        require: true
    },
    name: {
        type: String,
        require: true
    },
    password: {
        type: String,
        require: true
    },
    address: {
        type: String,
        require: true
    },
    pincode: {
        type: String,
        require: true
    },
    contact_person: {
        type: String,
        require: true
    },
    no_of_emp: {
        type: String,
        require: true
    },
    year_of_establishment: {
        type: String,
        require: true
    },
    registred_company: {
        type: String,
        require: true
    },
    fssi_no: {
        type: String,
        require: true
    },
    gst_no: {
        type: String,
        require: true
    },
    website: {
        type: String,
        require: true
    },
    join_platform: {
        type: String,
        require: true
    },
    speciality_items: {
        type: String,
        require: true
    },
    capacity_to_supply: {
        type: String,
        require: true
    },
    logistics_managed: {
        type: String,
        require: true
    },
    prod_name: {
        type: Array,
        require: true
    },
    prod_price: {
        type: Array,
        require: true
    },
    contact_no: {
        type: String,
        require: true
    },
    email: {
        type: String,
        require: true
    },
    supply_demand: {
        type: String,
        require: true
    },
    bussiness_info: {
        type: String,
        require: true
    },
    reviews: {
        type: String,
        require: true
    },
    role: {
        type: String,
        require: true
    },
    status: {
        type: String,
        require: true
    },
    approved_status:{
        type: String,
        require: true
    },
    reason:{
        type: String,
        require: true
    },
}, { timestamps: { createdAt: 'created_at', updatedAt: 'updated_at' } });

module.exports = mongoose.model("users", user);

