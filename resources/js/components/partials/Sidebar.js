import React, { Component } from 'react';

export default class Sidebar extends Component {
    render() {
        return (
            <div className='h-100 p-2'>{this.props.children}</div>
        );
    }
}
