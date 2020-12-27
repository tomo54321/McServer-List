import React from 'react';

function LoadingServerView() {

    

    return (
        <div className="the-server">
            <div className="d-block server-view-header loading-anim"/>


            {/* Page Header */}
            <div className="row my-4">
                <div className="col-12 col-md-6">
                    <div className="d-flex align-items-center w-100">
                        <div className="server-icon-holder mr-2 loading-anim" />
                        <h1 className="loading-anim"></h1>
                    </div>
                </div>
            </div>
            
            {/* Page Content */}
            <div className="row">
                <div className="col-12 col-md-6">
                    <div className="card">
                        <div className="card-body">
                            <p className="loading-anim"></p>
                            <p className="loading-anim"></p>
                            <p className="loading-anim"></p>
                        </div>
                    </div>
                </div>
                <div className="col-12 col-md-6">
                    <div className="card mt-3 mt-md-0">
                        <div className="card-header">Details</div>
                        <div className="card-body">
                            <table className="table table-striped">
                                <tbody>
                                    <tr>
                                        <th>Name</th>
                                        <th><span className="loading-anim" /></th>
                                    </tr>
                                    <tr>
                                        <th>IP</th>
                                        <td><span className="loading-anim" /></td>
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

export default LoadingServerView;