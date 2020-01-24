const User = require('../models/User');

class UserRepository {
    constructor(model) {
        this.model = model;
    }

    login(username, password) {
        console.log(username);
        console.log(password);
        return this.model.exists({username: username, password: password});
    }

    register(username, password) {
        const newUser = {username: username, password: password};
        const item = new this.model(newUser);
        return item.save();
    }

}


module.exports = new UserRepository(User);

