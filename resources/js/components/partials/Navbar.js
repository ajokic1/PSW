import React, { Component } from 'react';
import {Link} from 'react-router-dom';
import AuthControls from '../auth/AuthControls';

export default class Navbar extends Component {
    render() {
        return (
            <nav className="navbar fixed-top navbar-expand-md navbar-dark bg-dark">
                <a className="navbar-brand" href="#">Pregledaj.me</a>
                <button className="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span className="navbar-toggler-icon"></span>
                </button>
                <div className="collapse navbar-collapse" id="navbarNav">
                    {this.props.isLoggedIn &&
                    <ul className="navbar-nav mr-auto">
                        <li className="nav-item active">
                            <Link to='/'><div className="nav-link" href="#">Početna</div></Link>
                        </li>
                        <li className="nav-item active">
                            <Link to='/clinics'><div className="nav-link" href="#">Klinike</div></Link>
                        </li>
                        <li className="nav-item active">
                            <Link to='/appointments'><div className="nav-link" href="#">Zakazani pregledi</div></Link>
                        </li>
                        <li className="nav-item active">
                            <Link to='/medical_history'><div className="nav-link" href="#">Karton</div></Link>
                        </li>
                        
                    </ul>
                    }
                    <AuthControls 
                        user={this.props.user} 
                        isLoggedIn={this.props.isLoggedIn}
                        logout={this.props.logout}/>
                </div>
            </nav>
        );
    }
}
