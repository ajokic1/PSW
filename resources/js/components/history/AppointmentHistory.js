import React, { Component } from 'react';
import moment from 'moment';

export default class AppointmentHistory extends Component {
    render() {
        console.log(this.props.item);
        return (
            <div>
                <h1>{this.props.item.appointment_type.name}</h1>
                <hr/>
                <p>Tip pregleda: {this.props.item.appointment_type.type}</p>
                <p>Trajanje pregleda: {this.props.item.appointment_type.duration}</p>
                <p>Ljekar: {this.props.item.doctor.first_name 
                    + " " + this.props.item.doctor.last_name}</p>
                <p>Klinika: {this.props.item.clinic.name}</p>
                <p>Datum i vrijeme pregleda: 
                    {moment.unix(this.props.item.timestamp).format('DD.MM.YYYY HH:mm')}</p>
            </div>
        );
    }
}
