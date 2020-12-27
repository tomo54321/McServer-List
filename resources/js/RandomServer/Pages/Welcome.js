import React from 'react';

function Welcome({socket, socketError, socketConnected}) {
    const steps = [
        "Open Minecraft and goto the Servers screen",
        "Click on the Add Server button",
        "Enter random.mcserver-list.net as the server IP and the name can be anything you wish",
        "Click on the Done button",
        "Watch as a random server appears each time you refresh your server list!"
    ];

    const steps_list = steps.map((v, i) => (
        <div 
        key={"wait_step_" + i}
        className={"d-flex align-items-center" + (i > 0 ? " mt-3" : "")}>
            <div className="step-number">{ i + 1 }</div>
            <div className="step">{ v }</div>
        </div>
    ));

    return (
        <>
            <h1>Random Server</h1>
            <p>Add our random server to your server list in your game and watch as you refresh the list a different server appears. Once you join you will magically see this page update with the server you have joined.</p>
            <div className="connection mt-4">
                <h2>Get Connected</h2>
                <div className="row align-items-center">
                    <div className="col-12 col-md-6">
                        {steps_list}
                    </div>
                    <div className="d-none d-md-block col-6 text-center">
                        <div className="wait-icon"></div>
                        <span className="d-block">
                            {socketConnected ? 
                                "Waiting for you to join..." : 
                            socketError ? socketError : 
                                "Connecting to the random server..."
                            }
                        </span>
                        {socketConnected ? <span className="d-block">Connect to: <b>random.mcserver-list.net</b></span> : null}
                    </div>
                </div>
            </div>
        </>
    );
}

export default Welcome;