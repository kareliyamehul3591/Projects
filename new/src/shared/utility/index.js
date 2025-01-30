'use strict';
module.exports.slugify = ( text ) => {
    return text.toString().toLowerCase()
        .replace( /\s+/g, '-' ) // Replace spaces with -
        .replace( /[^\w\-.]+/g, '' ) // Remove all non-word chars
        .replace( /--+/g, '-' ) // Replace multiple - with single -
        .replace( /^-+/, '' ) // Trim - from start of text
        .replace( /-+$/, '' ); // Trim - from end of text
};

module.exports.padZero = ( n, width, z ) => {
    // eslint-disable-next-line no-param-reassign
    z = z || '0';
    // eslint-disable-next-line no-param-reassign
    n = `${n }`;
    return n.length >= width ? n : new Array( width - n.length + 1 ).join( z ) + n;
};
