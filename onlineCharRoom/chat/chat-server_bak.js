// Require the packages we will use:
var http = require("http"),
    socketio = require("socket.io"),
    fs = require("fs");

// Listen for HTTP connections.  This is essentially a miniature static file server that only serves our one file, client.html:
var app = http.createServer(function(req, resp){
    // This callback runs when a new connection is made to our HTTP server.

    fs.readFile("client.html", function(err, data){
        // This callback runs when the client.html file has been read from the filesystem.

        if(err) return resp.writeHead(500);
        resp.writeHead(200);
        resp.end(data);
    });
});
app.listen(3456);

let users = {
    name : [],
    id : [],
    room : []
};

let rooms = {
    name : [],
    creator : [],
    password : [],
    ban : [],
    //members : [[]]
};



// Do the Socket.IO magic:
var io = socketio.listen(app);
io.sockets.on("connection", function(socket){
    // This callback runs when a new Socket.IO connection is established.
    //io.sockets.emit('init',users);
    
    socket.on('login', function (username) {
        console.log("login");
        if (!users.name.includes(username)) {
            socket.join("public");
            socket.room = "public";
            socket.name = username;
            users.name.push(username);
            users.id.push(socket.id);
            users.room.push("public");
            io.sockets.emit('init', users);
            io.sockets.emit("message_to_client", {message: "User: " + username + " login..."});
        }else {
            socket.emit('log_error');
        }
    });


    socket.on('message_to_server', function(data) {
        // This callback runs when the server receives a new message from the client.
        io.sockets.in(socket.room).emit("message_to_client",{message:socket.name +": "+ data["message"] }) // broadcast the message to other users
    });

    socket.on('new_room', function (roomname,password) {
        if(rooms.name.includes(roomname)){
            socket.emit('roomname_repeat_error');
        }else{
            socket.leave(socket.room);
            socket.join(roomname);
            socket.room = roomname;
            console.log(socket.name+' create room '+roomname);
            rooms.name.push(roomname);
            rooms.creator.push(socket.name);
            rooms.password.push(password);
            rooms.ban.push(new Array());
            console.log(rooms.ban[0]);
            //rooms.members.push(new Array(socket.name));
            users.room[users.name.indexOf(socket.name)] = roomname;

            io.sockets.emit('update_info',users,rooms);
        }
    });
    
    socket.on('join_room', function (roomname,password) {
        if(!rooms.name.includes(roomname)){
            socket.leave(socket.room);
            socket.join(roomname);
            socket.room = roomname;
            rooms.name.push(roomname);
            rooms.creator.push(socket.name);
            console.log(socket.name+' create room '+roomname);
            rooms.password.push(password);
            rooms.ban.push(new Array());
            //rooms.members.push(new Array(socket.name));
            users.room[users.name.indexOf(socket.name)] = roomname;
            io.sockets.emit('update_info',users,rooms);
        }else{
            let index = rooms.name.indexOf(roomname);
            //let banlist = rooms.ban[index];
            //check ban list
            if(!rooms.ban[index].includes(socket.name)){
                //check password
                if(password == rooms.password[index]){
                    socket.leave(socket.room);
                    socket.join(roomname);
                    socket.room = roomname;
                    users.room[users.name.indexOf(socket.name)] = roomname;
                    io.sockets.emit('update_info',users,rooms);
                }else{
                    socket.emit('password_error');
                }
            }else{
                socket.emit('kick');
            }
        }
    });

    socket.on('back_public',function () {
        let index = users.name.indexOf(socket.name);
        if(index != -1){
            socket.leave(socket.room);
            socket.room = "public";
            socket.join("public");
            users.room[index] = "public";
            io.sockets.emit('update_info',users,rooms);
        }
    });

    //my all room:io.sockets.adapter.roomClients[socket.id]

    socket.on('kick_member',function (membername) {
        if(rooms.creator[rooms.name.indexOf(socket.room)] == socket.name){
            if(users.room[users.name.indexOf(membername)]==socket.room) {
                console.log(membername + " was kicked by " + socket.name);
                socket.to(users.id[users.name.indexOf(membername)]).emit("kick");
            }else{
                socket.emit('kick_error');
            }
        }
    });

    socket.on('ban_member',function (membername) {
        if(rooms.creator[rooms.name.indexOf(socket.room)] == socket.name){
            if(users.room[users.name.indexOf(membername)]==socket.room){
                let index = rooms.name.indexOf(socket.room);
                rooms.ban[index].push(membername);
                console.log(rooms.ban);
                console.log(membername+" was baned by "+socket.name);
                socket.to(users.id[users.name.indexOf(membername)]).emit("kick");
            }else{
                socket.emit('kick_error');
            }

        }
    });

    socket.on('private_msg_server',function (username,content) {
        if(users.room[users.name.indexOf(username)] != socket.room){
            socket.emit('private_msg_error');
        }else{
            let socketid = users.id[users.name.indexOf(username)];
            socket.to(socketid).emit('private_msg_client','[Private message]From'+socket.name+": " + content);
        }
    })
    
    socket.on('logout',function (username) {
        if(users.name.includes(username)){
            socket.leave(socket.room);
            let index = users.id.indexOf(socket.id);
            users.name.splice(index,1);
            users.room.splice(index,1);
            users.id.splice(index,1);
            socket.broadcast.emit("message_to_client", {message: "User: " + username + " logout..."});
            socket.broadcast.emit('init', users);
        }
    })


});