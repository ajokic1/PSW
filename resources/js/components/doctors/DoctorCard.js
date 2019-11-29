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
        <div className="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-xs-12 m-0">
        <div onClick={this.props.onClick} className="m-1 card bg-light dark_hover row" style={{width: '100%', height: '8rem'}}>
            <div style={photoStyle} className="h-100 col-sm-4 card-body p-0 m-0 card-img-left"/>
            <div className="col-sm-8 card-body">
                <h5 className="card-title">{this.props.doctor.first_name + ' ' + this.props.doctor.last_name}</h5>
                <div className="card-text">Radno vrijeme:</div>
                <div className="card-text">{this.props.doctor.pivot.works_from.substring(0,5)
                     + ' - ' + this.props.doctor.pivot.works_to.substring(0,5)}</div>
            </div>
        </div>
        </div>
        );
    }
}
