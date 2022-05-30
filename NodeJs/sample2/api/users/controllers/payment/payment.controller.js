
module.exports = {
    add_card_details:(req, res) => {
        /* const body = req.body; */
            const stripe = require("stripe")(
              "sk_test_51K5RgGGlIMsag8BjH8npragR1gDR0uoRLzXKLBPTTs4l3WblDsqNgOwjVhGhQwtOj2g7tSXZR2EdF9qujfBFXEtn00oCbMk57D"
            );
          
            const { amount, email, token } = req.body;
          
            stripe.customers
              .create({
                email: email,
                source: token.id,
                name: token.card.name,
              })
              .then((customer) => {
                return stripe.charges.create({
                  amount: parseFloat(amount) * 100,
                  description: `Payment for USD ${amount}`,
                  currency: "USD",
                  customer: customer.id,
                });
              })
              .then((charge) => res.status(200).send(charge))
              .catch((err) => console.log(err));
    }
};