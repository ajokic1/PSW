import React, { Component } from 'react';
import Select from 'react-select';
import DatePicker from 'react-datepicker';
import "react-datepicker/dist/react-datepicker.css";

export default class DoctorFilters extends Component {
    render() {
        const appTypes = this.props.appointmentTypes
            .map(appType => ({value: appType.id, label: appType.name}));
        return (
            <div className='row m-3'>
                <div className='col-md-3'>
                    <label className='form-label'>Ime:</label>
                    <input 
                        className='form-control' 
                        type='text' 
                        name='name' 
                        onChange={this.props.handleChange} 
                        value={this.props.name}/>
                </div>
                <div className='col-md-3'>
                    <label className='form-label'>Ocjena:</label>
                    <input 
                        className='form-control' 
                        type='number' 
                        name='rating' 
                        onChange={this.props.handleChange} 
                        value={this.props.rating} 
                        min='1' max='5'/>
                </div>
                <div className='col-md-3'>
                    <label className='form-label'>Tip pregleda:</label>
                    <Select 
                        isClearable 
                        value={this.props.appointmentTypeId}
                        name='appointmentTypes' 
                        options={appTypes} 
                        onChange={this.props.handleSelect}/>
                </div>
                <div className='col-md-3'>
                    <label className='form-label'>Datum pregleda:</label>
                    <DatePicker 
                        selected={this.props.date} 
                        onChange={this.props.setDate}
                        dateFormat='dd.MM.yyyy' />
                </div>
                
            </div>
        );
    }
}
