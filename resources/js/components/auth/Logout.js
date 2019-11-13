import React, { Component } from 'react';
import Message from '../partials/Message';

export default class Logout extends Component {
    componentDidMount() {
        this.props.logout();
    }
    render() {
        return (
            <div>
            <Message
                title='Uspjesno ste se odjavili sa sistema.'
                message=''/>
            </div>
        );
    }
}
