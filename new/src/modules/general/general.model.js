'use strict';
const { City } = require( '../../modules/city/city.model' );
const { Community } = require( '../../modules/community/community.model' );
const { Admin } = require( '../../modules/admin/admin.model' );
const { Genre } = require( '../../modules/genre/genre.model' );
const { AgeGroup } = require( '../../modules/age-group/age-group.model' );
const { Book } = require( '../../modules/book/book.model' );

// get all instances
const CityModel = new City().getInstance();
const CommunityModel = new Community().getInstance();
const AmbassadorModel = new Admin().getInstance();
const GenreModel = new Genre().getInstance();
const AgeGroupModel = new AgeGroup().getInstance();
const BookModel = new Book().getInstance();

module.exports = { CityModel, CommunityModel, AmbassadorModel, GenreModel, AgeGroupModel, BookModel };
