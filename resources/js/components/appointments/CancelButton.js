import React, { Component } from 'react';
import moment from 'moment'

export default class CancelButton extends Component {
    render() {
        let time = moment(this.props.a.date + ' ' + this.props.a.time, 'YYYY-MM-DD HH:mm:ss');
        time.subtract(moment.duration('P1D'));
        const isCancellable = moment().isBefore(time);
        return (
            <button 
                className={'btn btn-' + (isCancellable?'danger':'secondary') }
                onClick={() => this.props.cancelAppointment(this.props.a.id)} 
                disabled={!isCancellable}>
                    Otkaži                
            </button>
        );
    }
}
