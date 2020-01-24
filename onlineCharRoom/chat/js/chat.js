// Initial values
let username = "";
let users;
let room = "public";
let rooms; //for test;

var socketio = io.connect();
socketio.on("message_to_client", function (data) {
    if(username != ""){
        //Append an HR thematic break and the escaped HTML of the new message
        document.getElementById("chatlog").appendChild(document.createElement("hr"));
        document.getElementById("chatlog").appendChild(document.createTextNode('[Public] '+data['message']));
    }
});

// Send public messages
$("#send_msg").on('click', function () {
        // var msg = document.getElementById("message_input").value;
        socketio.emit("message_to_server", {message: document.getElementById("message_input").value});
        $("#message_input").val("");
    });


//login and init module
$("#login_button").on('click', function () {
    if (username == "") {
        console.log("login!");
        username = $("#username_input").val();
        socketio.emit('login', username);
        $("#chatlog").empty();
        // $("#after_login").toggle();
        // $("#login").toggle();
        //$("#show_info").toggle();
    }
});

socketio.on("log_error", function () {
    alert("Username has already existed!");
    username = "";
});

socketio.on("init", function (data,server_rooms) {
    if (username != "") {
        users = data;
        $("#chatlog").show();
        $("#after_login").show();
        $("#right-part").show();
        $("#login").hide();
        $("#others").empty();
        $("#me").empty();
        $("#room").empty();
        $("#me").append(username);
        $("#room").append(users.room[users.name.indexOf(username)]);
        //show other users
        $.each(users.name, function (index, item) {
            if (item != username && users.room[index] == room) {
                $("#others").append("<span>" + item + ";" + "&nbsp;" + "</span>");
            }
        })
        //show available room
        $("#avl_rooms").empty();
        let show_room = new Array();
        show_room.push('public');
        $("#avl_rooms").append('public'+";   " + "&nbsp;");
        $.each(server_rooms.name, function (index, item) {
            if (!show_room.includes(item)) {
                $("#avl_rooms").append(item+";   " + "&nbsp;");
                show_room.push(item);
            }
        });
        $("#show_info").show();
    }
});

//logout module
$("#logout").on("click", function () {
    socketio.emit('logout', username);
    console.log('logout');
    $("#chatlog").empty();
    $("#others").empty();
    $("#me").empty();
    $("#room").empty();
    $("#after_login").hide();
    $("#login").show();
    $("#show_info").hide();
    $("#kick").hide();
    $("#chatlog").hide();
    $("#right-part").hide();
    username = "";
});

//create room module
$("#create").on("click",function () {
    let roomname = $("#room_name").val();
    let password = $("#room_password").val();
    if($.trim(roomname) == "public"){
        alert("Public room is shared by all users, you can not create it!");
    }else{
        if($.trim(password) == ""){
            password = "";
        }
        socketio.emit('new_room',roomname,password,username, room);
        $("#chatlog").empty();
    }
});

socketio.on('roomname_repeat_error',function () {
    alert("Room name already exists! Please choose another room name!")
});

socketio.on('update_info',function (users,rooms) {
    this.users = users;
    //this.rooms = rooms;
    $("#room").empty();
    room = users.room[users.name.indexOf(username)];
    $("#room").append(room);
    if( username == rooms.creator[rooms.name.indexOf(room)]){
        $("#room").append("[You're creator]");
        $("#kick").show();
    }else{
        $("#kick").hide();
    }
    $("#others").empty();
    $.each(users.name, function (index, item) {
        if (item != username && users.room[index] == room) {
            $("#others").append("<span>" + item + ";   " + "&nbsp;"+ "</span>");
        }
    })
    //show available room
    $("#avl_rooms").empty();
    let show_room = new Array();
    show_room.push('public');
    $("#avl_rooms").append('public'+";   " + "&nbsp;");
    $.each(rooms.name, function (index, item) {
        if (!show_room.includes(item)) {
            $("#avl_rooms").append(item+";   " + "&nbsp;");
            show_room.push(item);
        }
    });
});

//join room module
$("#join").on("click",function () {
    let roomname = $("#join_name").val();
    let password = $("#join_password").val();
    if($.trim(roomname) == "public"){
        socketio.emit('back_public');
    }
    else {
        password = ($.trim(password) === "") ? "" : password;
        socketio.emit('join_room',roomname,password,username);
    }

});
socketio.on('ban_nochange',function () {
    alert("You are baned by the creator!");
})
socketio.on('room_not_exist',function () {
    alert("Room does not exist!");
})
socketio.on('create_by_join',function () {
    alert("Room does not exist. Now creating this room.");
})

socketio.on('ban_err',function () {
    alert("You are baned by the creator. Click to go to public room");
    socketio.emit('back_public');
});

socketio.on('password_error',function () {
    alert("Password is not correct!");
});


//back public module
$("#back_public").on("click",function () {
    socketio.emit('back_public');
});

//ban module
$("#ban_btn").on('click',function () {
    let membername = $("#member_name").val();
    socketio.emit('ban_member',$.trim(membername));
});

socketio.on('kick',function () {
    socketio.emit('back_public');
    alert("You are kicked out from the current room. Now back to public room")
});

socketio.on('kick_error',function () {
    alert("User not in this room");
});

//kick module
$("#kick_btn").on('click',function () {
    let membername = $("#member_name").val();
    socketio.emit('kick_member',$.trim(membername));
});

//private module
$("#private_msg_btn").on('click',function () {
    let name = $("#private_message_username").val();
    let content = $("#private_message_content").val();
    socketio.emit('private_msg_server',name,content);
    // $("#private_message_content").val("");
    document.getElementById("chatlog").appendChild(document.createElement("hr"));
    // document.getElementById("chatlog").appendChild(document.createTextNode('[Private to '+username+"]: " + content));
});

socketio.on('private_msg_client',function (message) {
    document.getElementById("chatlog").appendChild(document.createElement("hr"));
    document.getElementById("chatlog").appendChild(document.createTextNode('[Private from ' + message));
});

socketio.on('private_msg_success',function () {
    let name = $("#private_message_username").val();
    let content = $("#private_message_content").val();
    $("#private_message_content").val("");
    document.getElementById("chatlog").appendChild(document.createTextNode('[Private to '+username+"]: " + content));
});

socketio.on('private_msg_error',function () {
    alert('User not exists in this room. Please verify user name');
});

$("#save_record").bind('click',function () {
    let download = document.createElement('a');
    let record = $("#chatlog").text();
    var blob = new Blob([record], {
        type: "text/plain;charset=utf-8"
    });
    download.href = window.URL.createObjectURL(blob);
    download.download = "chat_record.txt";
    download.click();
});

$("#font_customize").bind("click",function () {
    // alert($("#font_color").val());
    $("#chatlog").css('color',$("#font_color").val());
    $("#chatlog").css('font-size',$("#font_size").val());
    $("#chatlog").css('font-family',$("#font_family").val());
    // $("#font_color").val("");
    $("#font_size").val("")
    $("#font_family").val("")
});

//check refresh, if refresh send logout
function refresh() {
    socketio.emit('logout',username);
    return "logout!";
}