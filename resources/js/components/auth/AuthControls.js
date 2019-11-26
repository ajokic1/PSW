import React, { Component } from 'react';

export default class AuthControls extends Component {
    render() {
        return (
            <div>
            {this.props.isLoggedIn && 
                <div className='text-light'>
                <strong>{this.props.user.first_name + ' ' + this.props.user.last_name}</strong>
                {this.props.user.role=='admin' && <small className='mx-1'>(admin)</small>}
                <button className='btn btn-outline-light mx-2' onClick={this.props.logout}>Log out</button></div>
            }
            </div>
        );
    }
}
