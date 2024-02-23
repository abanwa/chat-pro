the databases are not included and the CSS amd image files are not also included. However, the code works fine in my localhost but when i uploaded it online, it does not work. i contacted my hosting support and they sent this message to me and i have no clue on how to go about it. i am using websocket for the first time



"According to the Ratchet github, which is used to establish a server here /home/myUsername/public_html/users/bin/server.php, https://github.com/ratchetphp/Ratchet/issues/1041 it does not support direct connections through wss.



The possible solution could be binding the server here /home/myUsername/public_html/users/bin/server.php to localhost and 8080 port, instead of the current configuration where it is bind to the 0.0.0.0 and open for external connections.



Setting up proxy_pass directive to redirect all requests from some secure URL, for example https://tdcmobilestore.net/websocket to ws://localhost:8080 and adjusting the code in the chat-room.php file to connect to https://tdcmobilestore.net/websocket instead of wss://tdcmobilestore.net:8080.



With current setup, the connection to the websocket fails, most likely, only due to Ratchet's inability to serve connections through wss."
