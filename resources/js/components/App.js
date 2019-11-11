import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import { BrowserRouter as Router, Switch, Route, Link } from "react-router-dom";

import PrivateRoute from "./auth/PrivateRoute";
import LoginRoute from "./auth/LoginRoute";
import Register from "./auth/Register";

export default class App extends Component {
    render() {
        return (
            <Router>
                <Switch>
                    <PrivateRoute isLoggedIn={false} exact path="/">
                        <div>Home page</div>
                    </PrivateRoute>                    
                    <LoginRoute isLoggedIn={false} exact path="/register">
                        <Register/>
                    </LoginRoute>                    
                </Switch>
            </Router>
        );
    }
}

ReactDOM.render(
    <App />,
    document.getElementById('root'));
