const mongoose = require('mongoose');

const {Schema} = mongoose;

//define the schema of the items in our carts
const itemSchema = new Schema({
    name: {
        type: String,
    },
    complete: {
        type: Boolean,
    },
    quantity: {
        type: Number,
    },
    username: {
        type: String,
    }

});

const Item = mongoose.model('Item', itemSchema);

module.exports = Item;//export an abstraction[table] about items