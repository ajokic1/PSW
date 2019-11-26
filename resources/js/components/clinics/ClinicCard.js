import React, { Component } from 'react';
import { Link } from 'react-router-dom';

export default class ClinicCard extends Component {
    render() {
        const photoStyle = {
            backgroundImage: 'url(../images/' + this.props.clinic.photo + ')',
            backgroundPosition: 'center',
            backgroundSize: 'cover',
            width: '100%',
            height: '10rem'
        }
        return (
        <div className="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-xs-12 m-0">
            <Link to={'/clinics/'+this.props.clinic.id} style={{ textDecoration: 'none', color: 'black' }}>
                <div className="m-1 card bg-light dark_hover" style={{width: '100%', height: '22rem'}}>
                    <div style={photoStyle} className="card-img-top"/>
                    <div className="card-body h-50">
                        <h5 className="card-title">{this.props.clinic.name}</h5>
                        <div className="card-text">{this.props.clinic.address}</div>
                        <div className="card-text">{this.props.clinic.city}</div>
                    </div>
                </div>
            </Link>
        </div>
        );
    }
}
