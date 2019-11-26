import React, { Component } from 'react';
import { withRouter } from 'react-router-dom';

class Overlay extends Component {
    render() {
        const children = React.Children.map(this.props.children, child => {
            return React.cloneElement(child,  {match: this.props.match});
        });
        return (
            <div id='overlay' className='position-fixed dark-overlay w-100 h-100 overflow-auto' onClick={this.props.history.goBack}>
                {children}
            </div>
        );
    }
}

export default withRouter(Overlay);
