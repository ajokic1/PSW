import React, { Component } from 'react';
import Select from 'react-select';
import DatePicker from 'react-datepicker';
import "react-datepicker/dist/react-datepicker.css";

export default class ClinicFilters extends Component {
    render() {
        const appTypes = this.props.appointmentTypes
            .map(appType => ({value: appType.id, label: appType.name}));
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
            </div>
        );
    }
}
