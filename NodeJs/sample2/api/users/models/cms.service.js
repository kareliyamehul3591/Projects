const pool = require('../../../config/database');

module.exports = {
    cmslist: callback => {
        pool.query(
            'SELECT `id`,`user_id`,`title`,`status`,`unique_id` FROM `cms`',
            [],
            (error,result,fields) => {
                if(error)
                {
                    return callback(error);
                }else{
                    return callback(null,result);
                }
            }
        );
    },
    cmsbyid: (id,callback) => {
        pool.query(
            'SELECT * FROM `cms` where id = ?',
            [
                id
            ],
            (error,result,fields) => {
                if(error)
                {
                    return callback(error);
                }else{
                    return callback(null,result);
                }
            }
        );
    },
    image_slider_list: callback => {
        pool.query(
            'SELECT `id`, `title`, `images`, `content`, `status`, `unique_id` FROM `web_setting`',
            [],
            (error,result,fields) => {
                if(error)
                {
                    return callback(error);
                }else{
                    return callback(null,result);
                }
            }
        );
    },
    image_slider_list_by_id: (id,callback) => {
        pool.query(
            'SELECT * FROM `web_setting` where id = ?',
            [
                id
            ],
            (error,result,fields) => {
                if(error)
                {
                    return callback(error);
                }else{
                    return callback(null,result);
                }
            }
        );
    },
    success_imagelist: callback => {
        pool.query(
            'SELECT `id`, `title`, `name`, `images`, `content`, `status`, `unique_id` FROM `story_image_slider`',
            [],
            (error,result,fields) => {
                if(error)
                {
                    return callback(error);
                }else{
                    return callback(null,result);
                }
            }
        );
    },
    success_image_list_by_id: (id,callback) => {
        pool.query(
            'SELECT * FROM `story_image_slider` where id = ?',
            [
                id
            ],
            (error,result,fields) => {
                if(error)
                {
                    return callback(error);
                }else{
                    return callback(null,result);
                }
            }
        );
    },
    settings_list: callback => {
        pool.query(
            'SELECT `id`, `logo`, `footer_text`, `copyright_text`, `status` FROM `settings` ',
            [],
            (error,result,fields) => {
                if(error)
                {
                    return callback(error);
                }else{
                    return callback(null,result);
                }
            }
        );
    },
    social_media_list: callback => {
        pool.query(
            'SELECT `id`, `logo`, `link`, `status` FROM `social_media`',
            [],
            (error,result,fields) => {
                if(error)
                {
                    return callback(error);
                }else{
                    return callback(null,result);
                }
            }
        );
    },
};