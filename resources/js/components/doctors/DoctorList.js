import React, { Component } from 'react';
import DoctorCard from './DoctorCard';
import Loading from '../partials/Loading';

export default class DoctorList extends Component {
    render() {
        let doctorList = [];
        if(this.props.doctors){
            doctorList = this.props.doctors.map(doctor =>
                <DoctorCard onClick={() => this.props.handleClick(doctor.id)} doctor={doctor} key={doctor.id}/>);
        } else{
            doctorList = <Loading/>;
        }
        return (
            <div className='row p-2 overflow-auto'>
                {doctorList}
            </div>
        );
    }
}
