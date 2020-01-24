const express = require('express');

const app = express.Router();
const cartRepository = require('../repositories/CartRepository');
const userRepository = require('../repositories/UserRepository');

// get all items
//node js style: Non-Blocking I/O
app.get('/:username', (req, res) => {
    // console.log("Get ALL Session:" + req.session.username);
    const {username} = req.params;
    cartRepository.getAll(username).then((items) => {
        res.json(items);
    }).catch((error) => console.log(error));
});

//add a new item in cartRepository
app.post('/', (req, res) => {
    const {name} = req.body;
    const {quantity} = req.body;
    cartRepository.add(name, quantity, req.body.username).then((item) => {
        res.json(item);
    }).catch((error) => console.log(error));
});

//delete an item from cartRepository
app.delete('/:id', ((req, res) => {
    const {id} = req.params;
    cartRepository.deleteById(id).then((ok) => {
        console.log(ok);
        console.log(`Deleted item with id: ${id}`);
        res.status(200).json([]);
    }).catch(error => console.log(error));
}));


//alter an item in cartRepository
app.put('/:id', ((req, res) => {
    const {id} = req.params;
    const item = {name: req.body.name, quantity: req.body.quantity, complete: req.body.complete};
    cartRepository.alterById(id, item)
        .then(res.status(200).json([]))
        .catch(error => console.log(error));
}));

//handle login
app.post('/login', ((req, res) => {
    const username = req.body.username;
    const password = req.body.password;
    //req.session.username = username;
    userRepository.login(username, password)
        .then(loginSuccess => {
            if (loginSuccess) {
                req.session.username = username;
                console.log("login session:" + req.session.username);
                req.session.save();
                res.status(200).json([]);
            } else {
                res.status(500).json([]);
            }
        })
        .catch(error => console.log(error));
}));

//handle register
app.post('/register', ((req, res) => {
    const username = req.body.username;
    const password = req.body.password;
    console.log("register");
    userRepository.register(username, password)
        .then(res.status(200).json([]))
        .catch(error => console.log(error));
}));

//handle logout
app.post('/logout', ((req, res) => {
    //clear session
    res.status(200).json({Meg: "logout success!"});
}));

module.exports = app;