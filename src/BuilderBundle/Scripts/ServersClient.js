#!/usr/bin/env node
var WebSocketClient = require('websocket').client;

var client = new WebSocketClient();

var args = process.argv.slice(2);

var address = args[0];

var response = args[1];

client.on('connectFailed', function (error) {
    console.log('Connect Error: ' + error.toString());
});

client.on('connect', function (connection) {
    console.log('WebSocket Client Connected');
    connection.on('error', function (error) {
        console.log("Connection Error: " + error.toString());
    });
    connection.on('close', function () {
        console.log('echo-protocol Connection Closed');
    });
    connection.on('message', function (message) {
        if (message.type === 'utf8') {
            console.log("Received: '" + message.utf8Data + "'");
        }
    });

    function sendResponse() {
        if (connection.connected) {
            connection.sendUTF(response);
            setTimeout(sendResponse, 1000);
        }
    }

    sendResponse();
});

client.connect(address);