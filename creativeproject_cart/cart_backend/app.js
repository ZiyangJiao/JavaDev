//main file to run our project
const express = require('express');
const mongoose = require('mongoose');
const path = require('path');
const cookieParser = require('cookie-parser');
const cors = require('cors');
const error = require('http-errors');
const log = require('morgan');
const session = require('express-session');
// const MongoStore = require('connect-mongo')(session);
// const MongoDBStore = require('connect-mongodb-session')(session);
// const store = new MongoDBStore({
//     uri: 'mongodb://localhost:27017/connect_mongodb_session_test',
//     collection: 'mySessions'
// });
// const cookieSession = require('cookie-session');

const config = require('./config/Config');
const routes = require('./routes/Routes');

const app = express();

mongoose.connect(config.DB, {
    useNewUrlParser: true,
    useUnifiedTopology: true,
}).catch(error => console.log(error));
mongoose.set('useFindAndModify', false);

app.use(cookieParser());
app.use(session({
    secret: 'you-will-never-guess',
    resave: false,
    saveUninitialized: true,
    cookie: {
        maxAge: 1000 * 60 * 3,
    },
    // store: new MongoStore({ mongooseConnection: mongoose.connection })
}));

//enable cross-domain requests from web browsers to servers and WEB APIs
app.use(cors());

app.use(log('dev'));

//express.json() is a method inbuilt in express to recognize the incoming Request Object as a JSON Object.
app.use(express.json());

//express.urlencoded() is a method inbuilt in express to recognize the incoming Request Object as strings or arrays
app.use(express.urlencoded({extended: false}));


// app.set('trust proxy', 1);
// app.use(cookieSession({
//     name: 'session',
//     keys: ['key1', 'key2'],
//     httpOnly: false
// }))

//To serve static files such as images, CSS files, and JavaScript files, use the express.static built-in middleware function in Express.
app.use(express.static(path.join(__dirname, 'public')));
app.use('/cart_backend', routes);
//error
app.use((req, res, next) => {
    next(error(404));
});
app.use((err, req, res) => {
    //only set error in development mode
    //res.locals: An object that contains response local variables scoped to the request, and therefore available only to the view(s) rendered during that request / response cycle (if any). Otherwise, this property is identical to app.locals.
    res.locals.message = err.message;
    res.locals.error = req.app.get('env') === 'development' ? err : {};

    //set error page
    res.status(err.status || 500);
    //Renders a view and sends the rendered HTML string to the client.
    res.render('error');
});


//listen port
app.listen(config.APP_PORT);

module.exports = app;

/**
 * References and many thanks to:
 * https://dev.to/abiodunjames/build-a-todo-app-with-nodejs-expressjs-mongodb-and-vuejs--part-1--29n7
 * https://dev.to/abiodunjames/build-a-todo-app-with-nodejs-expressjs-mongodb-and-vuejs--part-2--3k11
 */
