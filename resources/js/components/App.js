import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import { BrowserRouter as Router, Switch, Route, Link } from "react-router-dom";

import PrivateRoute from "./auth/PrivateRoute";
import LoginRoute from "./auth/LoginRoute";
import Register from "./auth/Register";
import Login from "./auth/Login";
import Verify from "./auth/Verify";
import Logout from "./auth/Logout";

export default class App extends Component {
    constructor(props){
        super(props);
        this.state={
            user:{},
            isLoggedIn: false,
            needsVerification: false,
        }
        this.authSuccess = this.authSuccess.bind(this);
        this.logout = this.logout.bind(this);
    }
    componentDidMount() {
        let user = localStorage["user"];
        if (user) {
            user=JSON.parse(user);
            this.setState({ isLoggedIn: true, user: user });
        }
    }
    authSuccess(isSuccess, user, needsVerification){
        if(needsVerification) {
            this.setState({needsVerification: true});
        }
        if(isSuccess){
            this.setState({user: user, isLoggedIn: true});
            localStorage["user"] = JSON.stringify(user);
        } else {
            this.setState({errorMessage: 'Došlo je do greške pri registraciji'});
        }
    }
    logout(event) {
        if(event) event.preventDefault();
        console.log('log out');
        axios
            .post('/api/logout',{}, {headers: {'Authorization': 'Bearer '+this.state.user.auth_token}})
            .then(json =>{
                console.log(json);
            });
        this.setState({
            isLoggedIn: false,
            user: {},
        })
        localStorage['user'] ='';
    }
    render() {
        return (
            <div>
            {this.state.isLoggedIn && <button className='btn btn-primary' onClick={this.logout}>Log out</button>}
            {this.state.needsVerification
            ? <Verify/>
            : <Router>
                <Switch>
                    <PrivateRoute isLoggedIn={this.state.isLoggedIn} exact path="/">
                        <div>Home page</div>
                    </PrivateRoute>
                    <PrivateRoute isLoggedIn={this.state.isLoggedIn} exact path="/logout">
                        <Logout logout={this.logout}/>
                    </PrivateRoute>                    
                    <LoginRoute isLoggedIn={this.state.isLoggedIn} exact path="/register">
                        <Register authSuccess={this.authSuccess}/>
                    </LoginRoute>
                    <LoginRoute isLoggedIn={this.state.isLoggedIn} exact path="/login">
                        <Login authSuccess={this.authSuccess}/>
                    </LoginRoute>                 
                </Switch>
            </Router>
            }
            </div>
        );
    }
}

ReactDOM.render(
    <App />,
    document.getElementById('root'));
