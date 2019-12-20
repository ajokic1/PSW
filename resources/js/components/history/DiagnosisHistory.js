import React, { Component } from 'react';
import moment from 'moment';

export default class DiagnosisHistory extends Component {
    render() {
        return (
            <div>
                <h1>{this.props.item.condition.name}</h1>
                <hr/>
                <p>Opis: {this.props.item.condition.description}</p>
                <p>Simptomi: {this.props.item.condition.symptoms}</p>
                <p>Prognoza: {this.props.item.condition.prognosis}</p>
                <p>Tip oboljenja: {this.props.item.condition.type}</p>
                <p>Detalji: {this.props.item.details}</p>
                <p>Terapija: {this.props.item.therapy}</p>
                <p>Ljekar: {this.props.item.doctor.first_name 
                    + " " + this.props.item.doctor.last_name}</p>
                <p>Datum i vrijeme dijagnoze: &nbsp; 
                    {moment.unix(this.props.item.timestamp).format('DD.MM.YYYY HH:mm')}</p>
            </div>
        );
    }
}
