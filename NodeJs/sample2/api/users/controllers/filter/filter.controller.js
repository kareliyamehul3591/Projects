const { languagebyquery } = require("../../models/filter.service");

module.exports = {
    filterfunctionality: (req, res) => {
        const body = req.body;
        console.log(body);

        var query = "SELECT * FROM `user` u,`therapist_info` t WHERE u.id = t.user_id";

        if(body != "")
        {
            var language = [];
            var therapyType = [];
            var order_by = "";
            if(body.language != "")
            {
                language = body.language;
                language.forEach(rec => {
                    query += " and t.language like '%"+ rec +"%'";
                });
            }


             if(body.therapyType != "")
            {
                therapyType = body.therapyType;
                therapyType.forEach(rec => {
                    query += " and t.therapist_type like '%"+rec+"%'";
                });
               
            }
         
            if(body.order_by != ""){
            if(body.order_by == "Fee Low To High")
            {
                order_by = body.order_by;
                query += " ORDER BY hourly_price ASC"
            }else if(body.order_by == "Fee Low To High"){
                order_by = body.order_by;
                query += " ORDER BY hourly_price DESC"
            }
        } 
        }
        console.log(query);
        languagebyquery(query, (error, result) => {
            if (error) {
                console.log(error);
               
                return res.json({
                    success: 0,
                    message: "Database connection errror"
                });
            } else {
                var data = [];
                    result.forEach(rec => {
                        rec.language = JSON.parse(rec.language);
                        rec.language = rec.language.toString();
						
						/* const myJSON = JSON.stringify(rec.experience); 
						const myArray = myJSON.split(","); */
						 /* var myString = (rec.experience).toString(); */
                        /* rec.experience = JSON.parse(rec.experience); */
						 
						var experience = JSON.parse(rec.experience);
						rec.experience = "";
						var array = [];
						/* rec.experience = experience; */
						   experience.forEach(erc => {
							   array.push(erc[0].split(",").join(""));
						  }); 
						  rec.experience = array.join(" , ");
                        /* rec.experience = JSON.parse(rec.experience); */
		
                        rec.expertise = JSON.parse(rec.expertise);
                        rec.profile_image =(rec.profile_image) ? process.env.AAP_URL+"images/"+rec.profile_image : process.env.AAP_URL+"images/image_not_found.png";
                        data.push(rec);
                    });
                return res.json({
                    success: 1,
                    message: "serch by language Successfully",
                    data: data
                });
            }
        });
    },
    
};