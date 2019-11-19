import React, { Component } from 'react';

export default class ClinicCard extends Component {
    render() {
        return (
        <div className="m-2 card bg-light dark_hover" style={{width: '16rem', height: '20rem'}}>
            <img src={"../images/" + this.props.clinic.photo} className="card-img-top" alt="..."/>
            <div className="card-body">
                <h5 className="card-title">{this.props.clinic.name}</h5>
                <div className="card-text">{this.props.clinic.address}</div>
                <div className="card-text">{this.props.clinic.city}</div>
            </div>
        </div>
        );
    }
}
