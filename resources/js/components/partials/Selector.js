import React, { Component } from 'react';

export default class Selector extends Component {
    render() {
        const options = this.props.options.map(option =>
            <div 
                    className={'mx-1 p-2 flex-grow-0 flex-shrink-0 btn' + (option.value==this.props.selected?' btn-outline-primary':' btn-primary')} 
                    onClick={() => this.props.handleSelect(option.value)}>
                {option.label}
            </div>
        );
        return (
            <div className="w-100 d-flex flex-row justify-content-center">
                {options}
            </div>
        );
    }
}
