import React, { Component } from 'react';
import CancelButton from './CancelButton';
import Selector from '../partials/Selector';

export default class Appointments extends Component {
    constructor(props) {
        super(props);
        this.state={
            appointments: [],
            appointmentsHistory: [],
            message: '',
            selected: 'current',
        }
        this.cancelAppointment = this.cancelAppointment.bind(this);
        this.handleSelect = this.handleSelect.bind(this);
    }
    componentDidMount() {
        axios
            .get('/api/user/' + this.props.user.id + '/appointments')
            .then(json => {
                this.setState({appointments: json.data});
            });
        axios
            .get('/api/user/' + this.props.user.id + '/appointments/history')
            .then(json => {
                this.setState({appointmentsHistory: json.data});
            });
    }
    cancelAppointment(id) {
        axios
            .delete('/api/appointments/'+id)
            .then(response => {
                this.setState({message: 'Pregled je uspješno otkazan'});
                setTimeout(()=>this.setState({message:''}), 5000);
                axios
                    .get('/api/user/' + this.props.user.id + '/appointments')
                    .then(json => {
                        this.setState({appointments: json.data});
                    });
            });
        

    }
    handleSelect(selected) {
        this.setState({selected});
    }
    render() {
        const selectedList = this.state.selected=='current'
            ? this.state.appointments
            : this.state.appointmentsHistory;
        const appointments = selectedList.map(a => (
                <tr key={a.id}>
                    <td>{a.appointment_type.name}</td>
                    <td>{a.clinic.name}</td>
                    <td>{a.doctor.first_name + ' ' + a.doctor.last_name}</td>
                    <td>{a.date}</td>
                    <td>{a.time}</td>
                    <td>{a.appointment_type.duration}</td>
                    <td>
                        {this.state.selected=='current' &&
                            <CancelButton a={a} cancelAppointment={this.cancelAppointment}/>}
                    </td>
                </tr>
            ));

        const currentOrHistory = [
            {value: 'current', label: 'Zakazani pregledi'},
            {value: 'history', label: 'Istorija pregleda'}
        ];
        const title = currentOrHistory.find(item => item.value==this.state.selected).label;
        return (
            <div className='m-4 h-100'>
                {this.state.message &&
                    <span className='position-fixed p-3 fixed-top mt-5 bg-light text-center'>
                        {this.state.message}
                    </span>
                }
                <Selector 
                    options={currentOrHistory} 
                    selected={this.state.selected} 
                    handleSelect={this.handleSelect}/>
                <h1>{title}</h1>
                <table className="table">
                    <thead>
                        <tr>
                            <th scope="col">Vrsta pregleda</th>
                            <th scope="col">Klinika</th>
                            <th scope="col">Ljekar</th>
                            <th scope="col">Datum</th>
                            <th scope="col">Vrijeme</th>
                            <th scope="col">Trajanje pregleda</th>
                            <th scope="col">Akcije</th>
                        </tr>
                    </thead>
                    <tbody className='overflow-auto'>
                        {appointments}
                    </tbody>
                </table>
            </div>
        );
    }
}
