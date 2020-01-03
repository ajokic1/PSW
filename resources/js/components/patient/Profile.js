import React, { Component } from 'react';
import { Route, Link } from 'react-router-dom';
import Overlay from '../partials/Overlay';
import EditOverlay from './EditOverlay';

export default class Profile extends Component {
    render() {
        return (
            <div>
                <div className='container my-4'>
                    
                    <h1 className='mb-4'>{this.props.user.first_name + ' ' + this.props.user.last_name}</h1>
                    <div className='row'>
                        <div className='col-sm-3'>
                            <img className='img-fluid' src={'/images/' + this.props.user.photo}/>
                        </div>
                        <div className='col-sm-9'>
                            <p><strong>Ime: </strong>{this.props.user.first_name}</p>
                            <p><strong>Prezime: </strong>{this.props.user.last_name}</p>
                            <p><strong>E-mail: </strong>{this.props.user.email}</p>
                            <p><strong>Adresa: </strong>{this.props.user.address}</p>
                            <p><strong>Grad: </strong>{this.props.user.city}</p>
                            <p><strong>Država: </strong>{this.props.user.country}</p>
                            <p><strong>Telefon: </strong>{this.props.user.phone_no}</p>
                            <p><strong>Jedinstveni broj osiguranika: </strong>{this.props.user.insurance_no}</p>
                            <Link to='/profile/edit'>
                                <button className='btn btn-primary'><i class='fas fa-edit mr-2'/>Izmijeni podatke</button>
                            </Link>
                        </div>
                    </div>
                </div>
                <Route path='/profile/edit'>
                    <Overlay>
                        <EditOverlay
                            user={this.props.user}
                            authSuccess={this.props.authSuccess}/>
                    </Overlay>
                </Route>
            </div>
        );  
    }
}
