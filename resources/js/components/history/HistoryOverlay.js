import React, { Component } from 'react';
import AppointmentHistoryItem from './AppointmentHistoryItem';
import DiagnosisHistoryItem from './DiagnosisHistoryItem';

export default class HistoryOverlay extends Component {
    render() {
        return (
            <div style={{top: '3rem'}} id='overlay' className='position-fixed dark-overlay w-100 h-100 overflow-auto' onClick={this.props.hideOverlay}>
                <div className='w-75 bg-white mx-auto mt-5 p-5 rounded'>
                    {this.props.item.condition
                        ? <DiagnosisHistoryItem item={this.props.item}/>
                        : <AppointmentHistoryItem item={this.props.item}/>
                    }
                </div>
            </div>
        );
    }
}
