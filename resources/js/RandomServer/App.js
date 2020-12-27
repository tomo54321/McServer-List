import React, { useEffect, useState } from 'react';
import ReactDOM from 'react-dom';
import { io } from 'socket.io-client';
import Welcome from './Pages/Welcome';
import ServerView from './Pages/ServerView';

function App() {

    const [socketConnected, setSocketConnectedStatus] = useState(false);
    const [socketError, setSocketError] = useState("");
    const [currentServer, setCurrentServer] = useState(null);
    let webSocket;
    let currentServerChecker;
    useEffect(() => {
        webSocket = io("localhost:3000", {
            path: "/randomserver"
        });
        webSocket.on("connect", () => {
            setSocketConnectedStatus(true);
            currentServerChecker = setInterval(requestServerUpdate, 10000);
        });
        webSocket.on("error", (error) => {
            console.log(error);
            setSocketError("Error!");
            setCurrentServer(null);
            clearInterval(currentServerChecker);
        });
        webSocket.on("disconnect", () => {
            clearInterval(currentServerChecker);
            setCurrentServer(null);
            setSocketConnectedStatus(false)
        });
    
        webSocket.on("new server", (server) => {
            setCurrentServer(server);
        });
        webSocket.on("no server", () => setCurrentServer(null));

    }, []);

    const requestServerUpdate = () => {
        webSocket.emit("check server");
    };


    return (
        <div className="container">
            {
                currentServer === null ? 
                <Welcome 
                    socket={webSocket}
                    socketError={socketError}
                    socketConnected={socketConnected}
                /> :
                <ServerView serverId={currentServer} />
            }
        </div>
    );
}

if (document.getElementById('random-server')) {
    ReactDOM.render(<App />, document.getElementById('random-server'));
}
