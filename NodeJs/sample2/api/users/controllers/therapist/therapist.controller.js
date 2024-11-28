const { therapistlist, therapistbyid } = require("../../models/therapist.service");

module.exports = {
    therapistlist: (req, res) => {
        therapistlist((error, result) => {
            if (error) {
                console.log(error);
                return res.json({
                    success: 0,
                    message: "Database connection errror"
                });
            } else {
                if(result)
                {
                    var data = [];
                    result.forEach(rec => {
                        delete rec.password;
                        rec.language = JSON.parse(rec.language);
                        rec.language = rec.language.toString();
					
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
                        data: data
                    });
                }else{
                    return res.json({
                        success: 0,
                        message: "Record Not Found"
                    });
                }

            }
        });
    },
    therapistbyid: (req,res) => {
        const therapist_id = req.params.therapist_id;
        therapistbyid(therapist_id,(error, result) => {
            if (error) {
                console.log(error);
                return res.json({
                    success: 0,
                    message: "Database connection errror"
                });
            } else {
                if(result)
                {
                    delete result.password;
                    result.language = JSON.parse(result.language);
                    result.language = result.language.toString();
                    result.experience = JSON.parse(result.experience);
                    result.expertise = JSON.parse(result.expertise);
                    result.profile_image =(result.profile_image) ? process.env.AAP_URL+"images/"+result.profile_image : process.env.AAP_URL+"images/image_not_found.png";
                    return res.json({
                        success: 1,
                        data: result
                    });
                }else{
                    return res.json({
                        success: 0,
                        message: "Record Not Found"
                    });
                }

            }
        });
    }
};