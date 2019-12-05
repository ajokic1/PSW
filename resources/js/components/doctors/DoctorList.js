import React, { Component } from 'react';
import DoctorCard from './DoctorCard';
import Loading from '../partials/Loading';
import moment from 'moment';

export default class DoctorList extends Component {
    render() {
        let doctorList = [];
        const date = (new Date(this.props.date)).toISOString().split('T')[0];
        if(this.props.doctors){
            doctorList = this.props.doctors.map(doctor =>
                <DoctorCard
                    link={'/appointment/'+doctor.id+'/'+this.props.clinicId+'/'
                        +this.props.appointmentTypeId+'/'+date}
                    doctor={doctor} 
                    key={doctor.id}
                    availability={this.props.availability
                        .filter(a => a.doctor_id==doctor.id 
                            && a.clinic_id==this.props.clinicId)}/>);
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
