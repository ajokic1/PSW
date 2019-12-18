import React, { Component } from 'react';
import { withRouter } from 'react-router-dom';

class Overlay extends Component {
    constructor(props) {
        super(props);
        this.handleClick=this.handleClick.bind(this);
    }
    handleClick(event) {
        if(event.target.id=='overlay')
            this.props.history.goBack();
    }
    render() {
        const children = React.Children.map(this.props.children, child => {
            return React.cloneElement(child,  {match: this.props.match});
        });
        return (
            <div id='overlay' className='position-fixed dark-overlay w-100 h-100 overflow-auto' onClick={this.handleClick}>
                {children}am
            </div>
        );
    }
}

export default withRouter(Overlay);
