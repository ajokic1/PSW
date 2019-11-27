import React, { Component } from 'react';
import Select from 'react-select';

export default class DoctorFilters extends Component {
    render() {
        return (
            <div className='row'>
                <div className='col-md-3'>
                    <label className='form-label'>Ime:</label>
                    <input className='form-control' type='text' name='name' onChange={this.props.handleChange} value={this.props.name}/>
                </div>
                <div className='col-md-2'>
                    <label className='form-label'>Ocjena:</label>
                    <input className='form-control' type='number' name='rating' onChange={this.props.handleChange} value={this.props.rating} min='1' max='5'/>
                </div>
                
            </div>
        );
    }
}
