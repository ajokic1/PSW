import React, { Component } from 'react';
import ClinicCard from './ClinicCard';

export default class ClinicList extends Component {
    render() {
        const clinicList = this.props.clinics.map( clinic =>
            <ClinicCard clinic={clinic} key={clinic.id}/>
        );
        return (
            <div className='row p-3 overflow-auto'>
                {clinicList}
            </div>
        );
    }
}
