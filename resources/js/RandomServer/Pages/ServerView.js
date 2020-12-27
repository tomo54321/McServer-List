import Axios from 'axios';
import React, { useEffect, useState } from 'react';

import LoadingServerView from './LoadingServerView';

function ServerView({ serverId }) {

    const [server, setServer] = useState({});
    const [loading, isLoading] = useState(true);

    useEffect(() => {
        Axios.get("api/server/" + serverId)
        .then(data => {
            setServer(data.data);
            isLoading(false);
        })
        .catch(ex => {
            console.log(ex.message);
        });

    }, []);

    if(loading) { return <LoadingServerView/> }

    return (
        <div className="the-server">

            {
                server.header_path ?
                    <span 
                    className="d-block server-view-header"
                    style={{
                        backgroundImage: `url(/storage/${server.id}/${server.header_path})`
                    }}/>
                : null
            }

            {/* Page Header */}
            <div className="row my-4">
                <div className="col-12 col-md-6">
                    <div className="d-flex align-items-center w-100">
                        {
                            server.icon_path ? 
                            <img src={"/storage/" + server.id + "/" + server.icon_path} alt={server.name} className="mr-2"/>
                            : null
                        }
                        <div>
                            <small className="text-muted">You're currently connected to:</small>
                            <h1>{server.name}</h1>
                        </div>
                    </div>
                </div>
                <div className="col-12 col-md-6 mt-3 mt-md-0 text-center text-md-right">
                    <a href={"/server/" + server.id} className="btn btn-dark">View Server</a>{' '}
                    {server.website ? <><a href={"http://" + server.website} className="btn btn-primary">Website</a>{' '}</> : null }
                    {server.discord ? <a href={"https://discord.gg/" + server.discord} target="_blank" rel="noreferrer" className="btn btn-discord">Discord</a> : null }
                </div>
            </div>
            
            {/* Page Content */}
            <div className="row">
                <div className="col-12 col-md-6">
                    <div className="card">
                        <div className="card-body">
                            { server.description }
                        </div>
                    </div>
                    {
                        server.youtube_id ? 
                        <div className="card mt-3">
                            <div className="card-header">YouTube Video</div>
                            <div className="card-body">
                                <iframe 
                                width="100%" 
                                height="315" 
                                src={"https://www.youtube.com/embed/" + server.youtube_id}
                                frameborder="0" 
                                allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" 
                                allowfullscreen/>
                            </div>
                        </div>
                        :null
                    }
                </div>
                <div className="col-12 col-md-6">
                    <div className="card mt-3 mt-md-0">
                        <div className="card-header">Details</div>
                        <div className="card-body">
                            <table className="table table-striped">
                                <tbody>
                                    <tr>
                                        <th>Name</th>
                                        <th>{server.name}</th>
                                    </tr>
                                    <tr>
                                        <th>IP</th>
                                        <td>
                                            <div className="d-flex">
                                                <span className="flex-grow-1">{server.ip}{server.port != 25565 ? ":" + server.port : null}</span>
                                                <button className="btn btn-dark btn-sm copy-ip-btn">Copy IP</button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}

export default ServerView;