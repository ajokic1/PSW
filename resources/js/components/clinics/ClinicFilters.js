import React, { Component } from 'react';
import Select from 'react-select';
import DatePicker from 'react-datepicker';
import "react-datepicker/dist/react-datepicker.css";

export default class ClinicFilters extends Component {
    render() {
        const appTypes = this.props.appointmentTypes
            .map(appType => ({value: appType.id, label: appType.name}));
        const names = this.props.clinics
            .map(clinic => ({value: clinic.name, label: clinic.name}));
        const distinctCities = [...new Set(this.props.clinics.map(clinic => clinic.city))];
        const cities = distinctCities
            .map(city => ({value: city, label: city}));
        
        return (
            <div>
                <label className='form-label'>Tip pregleda:</label>
                <Select 
                    isClearable 
                    name='appointmentTypes' 
                    options={appTypes} 
                    onChange={this.props.handleChange}/>
                <label className='form-label mt-3'>Datum pregleda:</label>
                <div>
                <DatePicker 
                    selected={this.props.date} 
                    onChange={this.props.setDate}
                    dateFormat='dd.MM.yyyy'
                    placeholderText='Odaberi datum pregleda' />
                </div>
                <label className='form-label mt-3'>Naziv klinike:</label>
                <Select 
                    isClearable 
                    name='name' 
                    options={names} 
                    onChange={this.props.handleChange}/>
                <label className='form-label mt-3'>Grad:</label>
                <Select 
                    isClearable 
                    name='city' 
                    options={cities} 
                    onChange={this.props.handleChange}/>

            </div>
        );
    }
}
