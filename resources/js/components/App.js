
import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import { BrowserRouter as Router, Switch, Route, Link } from "react-router-dom";
import moment from 'moment';

import PrivateRoute from "./auth/PrivateRoute";
import LoginRoute from "./auth/LoginRoute";
import Register from "./auth/Register";
import Login from "./auth/Login";
import Verify from "./auth/Verify";
import Logout from "./auth/Logout";
import AuthControls from "./auth/AuthControls";
import Clinics from "./clinics/Clinics";
import Navbar from "./partials/Navbar";
import SubmitAppointment from './appointments/SubmitAppointment';
import Appointments from './appointments/Appointments';
import Home from './Home';
import History from './history/History';
import Profile from './patient/Profile';

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
        moment().locale('sr');
        if (user) {
            user=JSON.parse(user);
            this.setState({ isLoggedIn: true, user: user });
            axios.defaults.headers.common['Authorization'] = 
                'Bearer ' + user.auth_token;
        }
    }
    authSuccess(isSuccess, user, needsVerification){
        if(needsVerification) {
            this.setState({needsVerification: true});
        }
        if(isSuccess){
            this.setState({user: user, isLoggedIn: true});
            localStorage["user"] = JSON.stringify(user);
            axios.defaults.headers.common['Authorization'] = 
                'Bearer ' + user.auth_token;
        } else {
            this.setState({errorMessage: 'Došlo je do greške pri registraciji'});
        }
    }
    logout(event) {
        if(event) event.preventDefault();
        console.log('log out');
        axios
            .post('/api/logout',{})
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
            <div className='h-100' style={{paddingTop: '3.5rem'}}>
            {this.state.needsVerification
            ? <Verify/>
            : <Router>
                <Navbar user={this.state.user} isLoggedIn={this.state.isLoggedIn} logout={this.logout}/>
                <Switch>
                    <PrivateRoute isLoggedIn={this.state.isLoggedIn} path="/clinics">
                        <Clinics />
                    </PrivateRoute>
                    <PrivateRoute isLoggedIn={this.state.isLoggedIn} exact path="/">
                        <Home/>
                    </PrivateRoute>
                    <PrivateRoute isLoggedIn={this.state.isLoggedIn} path="/appointment/:doctorId/:clinicId/:appTypeId/:date">
                        <SubmitAppointment />
                    </PrivateRoute>
                    <PrivateRoute isLoggedIn={this.state.isLoggedIn} path="/appointments">
                        <Appointments user={this.state.user}/>
                    </PrivateRoute>
                    <PrivateRoute isLoggedIn={this.state.isLoggedIn} path="/medical_history">
                        <History user={this.state.user}/>
                    </PrivateRoute>                    
                    <PrivateRoute isLoggedIn={this.state.isLoggedIn} path="/profile">
                        <Profile user={this.state.user}/>
                    </PrivateRoute>                    
                    <LoginRoute isLoggedIn={this.state.isLoggedIn} path="/register">
                        <Register authSuccess={this.authSuccess}/>
                    </LoginRoute>
                    <LoginRoute isLoggedIn={this.state.isLoggedIn} path="/login">
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
