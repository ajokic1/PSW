import React, { Component } from 'react';
import { withRouter, Redirect } from 'react-router-dom';

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
            isSubmitted: false,
            submitting: false,
        }
        this.submitAppointment = this.submitAppointment.bind(this);
    }
    componentDidMount() {
        const doctorId = this.props.match.params.doctorId;
        const clinicId = this.props.match.params.clinicId;
        const appTypeId = this.props.match.params.appTypeId;
        const date = this.props.match.params.date;
        axios
            .get('/api/appointments/details/' + doctorId+'/'+clinicId+'/'+appTypeId+'/'+date)
            .then(json => {
                console.log(json.data);
                this.setState({
                    doctor: json.data.doctor,
                    clinic: json.data.clinic,
                    appointment_type: json.data.appointment_type,
                    availability: json.data.availability,
                    loaded: true,
                }); 
            });
    }
    submitAppointment(time) {
        const date = this.props.match.params.date;
        this.setState({submitting: true});

        let formData = new FormData(); 
        formData.append('doctor_id', this.state.doctor.id);
        formData.append('clinic_id', this.state.clinic.id);
        formData.append('user_id', this.props.user.id);
        formData.append('appointment_type_id', this.state.appointment_type.id);
        formData.append('date', date);
        formData.append('time', time);

        axios
            .post('/api/appointments',formData)
            .then(() => {
                this.setState({isSubmitted: true});
            });
        
    }
    render() {
        if(this.state.isSubmitted)
            return <Redirect to='/appointments'/>;
        const appTimes = this.state.availability 
            ? this.state.availability.map(a => <AppointmentTime submit={this.submitAppointment} a={a} key={a.id}/>)
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
                            {this.state.submitting
                                ? 'Zakazivanje u toku...'
                                : appTimes}
                        </div>
                    </div>
                    : "Loading..."}
                </div>                
            </div>
        );
    }
}

export default withRouter(SubmitAppointment);