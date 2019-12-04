import React, { Component } from 'react';

export default class DoctorCard extends Component {
    render() {
        const photoStyle = {
            backgroundImage: 'url(../images/' + this.props.doctor.photo + ')',
            backgroundPosition: 'center',
            backgroundSize: 'cover',
            width: '100%',
        }
        return (
        <div className="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12 m-0">
        <div onClick={this.props.onClick} className="m-1 card bg-light dark_hover row" style={{width: '100%', height: '8rem'}}>
            <div style={photoStyle} className="h-100 col-sm-4 card-body p-0 m-0 card-img-left"/>
            <div className="col-sm-8 card-body">
                <h5 className="card-title">{this.props.doctor.first_name + ' ' + this.props.doctor.last_name}</h5>
                <div className="card-text">Slobodni termini:</div>
                <div className="card-text">{this.props.availability.map(a => a.time + '(' + a.duration + '), ')}</div>
            </div>
        </div>
        </div>
        );
    }
}
