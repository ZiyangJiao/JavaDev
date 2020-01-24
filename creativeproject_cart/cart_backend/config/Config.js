/*
 * set DB to environment variable MONGO_URL if defined,
 * otherwise defaults to mongodb://localhost:27017/Cart.
 * We also did the same with APP_PORT
 */
module.exports = {
    DB: process.env.MONGO_URL ? process.env.MONGO_URL : "mongodb://localhost:27017/Cart",
    APP_PORT: process.env.APP_PORT ? process.env.APP_PORT : 8888,
};
//brew services start mongodb-community