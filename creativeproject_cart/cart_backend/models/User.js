const mongoose = require('mongoose');

const {Schema} = mongoose;

//define the schema of the users in our carts
const userSchema = new Schema({
    username: {
        type: String,
    },
    password: {
        type: String,
    },

});

const User = mongoose.model('User', userSchema);

module.exports = User;//export an abstraction[table] about users