import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import { BrowserRouter as Router, Switch, Route, Link } from "react-router-dom";

import PrivateRoute from "./auth/PrivateRoute";
import LoginRoute from "./auth/LoginRoute";
import Register from "./auth/Register";

export default class App extends Component {
    constructor(props){
        super(props);
        this.state={
            user:{},
            isLoggedIn:false,
        }
        this.authSuccess = this.authSuccess.bind(this);
    }
    authSuccess(isSuccess, user){
        if(isSuccess){
            this.setState({user: user, isLoggedIn: true});
            localStorage["user"] = JSON.stringify(user);
        } else {
            this.setState({errorMessage: 'Došlo je do greške pri registraciji'});
        }
    }
    render() {
        return (
            <Router>
                <Switch>
                    <PrivateRoute isLoggedIn={this.state.isLoggedIn} exact path="/">
                        <div>Home page</div>
                    </PrivateRoute>                    
                    <LoginRoute isLoggedIn={this.state.isLoggedIn} exact path="/register">
                        <Register authSuccess={this.authSuccess}/>
                    </LoginRoute>                    
                </Switch>
            </Router>
        );
    }
}

ReactDOM.render(
    <App />,
    document.getElementById('root'));
