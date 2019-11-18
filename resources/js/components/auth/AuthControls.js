import React, { Component } from 'react';

export default class AuthControls extends Component {
    render() {
        return (
            <div>
                {this.props.isLoggedIn && 
                    <div>
                        <strong>{this.props.user.first_name}</strong>
                        {this.props.user.role=='admin' && <small>(admin)</small>}
                        <button className='btn btn-primary' onClick={this.props.logout}>Log out</button>
                    </div>}
            </div>
        );
    }
}
