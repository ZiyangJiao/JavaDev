const Item = require('../models/Item');

class CartRepository {
    constructor(model) {
        this.model = model;
    }

    //add a new item in cart
    add(name, quantity, username) {
        console.log("additem:" + username);
        const newItem = {name: name, quantity: quantity, complete: false, username: username};
        const item = new this.model(newItem);

        return item.save();
    }

    //return all items
    getAll(username) {
        return this.model.find({username: username});
    }

    //get an item by id
    getById(id) {
        return this.model.findById(id);
    }

    //delete an item by id
    deleteById(id) {
        return this.model.findByIdAndDelete(id);
    }

    //alter an item by id
    alterById(id, obj) {
        const query = {_id: id};
        return this.model.findOneAndUpdate(query, {
            $set: {
                name: obj.name,
                quantity: obj.quantity,
                complete: obj.complete
            }
        });
    }

}


module.exports = new CartRepository(Item);

