import React, { Component } from 'react';
import { withRouter } from 'react-router-dom';

import AppointmentTime from './AppointmentTime';

class SubmitAppointment extends Component {
    constructor(props) {
        super(props);
        this.state={
            doctor: {},
            clinic: {},
            appointment_type: {},
            availability: [],
            loaded: false,
        }
    }
    componentDidMount() {
        const doctorId = this.props.match.params.doctorId;
        const clinicId = this.props.match.params.clinicId;
        const appTypeId = this.props.match.params.appTypeId;
        const date = this.props.match.params.date;
        axios
            .get('/api/appointments/details/' + doctorId+'/'+clinicId+'/'+appTypeId+'/'+date)
            .then(json => {
                this.setState({
                    doctor: json.data.doctor,
                    clinic: json.data.clinic,
                    appointment_type: json.data.appointment_type,
                    availability: json.data.availability,
                    loaded: true,
                }); 
            });
    }
    render() {
        const appTimes = this.state.availability 
            ? this.state.availability.map(a => <AppointmentTime a={a} key={a.id}/>)
            : "Loading...";
        
        return (
            <div className='card shadow col-md-12 col-lg-8 mx-auto my-5 px-5'>
                <div className='card-body'>
                    <h1>Zakazivanje pregleda</h1>
                    <hr/>
                    { (this.state.doctor && this.state.clinic && this.state.appointment_type) ?
                    <div>
                        <p>Odabrani ljekar: {this.state.doctor.first_name + ' ' + this.state.doctor.last_name}</p>
                        <p>Odabrana klinika: {this.state.clinic.name}</p>
                        <p>Odabrani pregled: {this.state.appointment_type.name}</p>
                        <p>Trajanje odabranog pregleda: {this.state.appointment_type.duration}</p>
                        <p>Slobodni termini:</p>
                        <div>
                            {appTimes}
                        </div>
                    </div>
                    : "Loading..."}
                </div>                
            </div>
        );
    }
}

export default withRouter(SubmitAppointment);