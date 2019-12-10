import React, { Component } from 'react';

export default class Appointments extends Component {
    constructor(props) {
        super(props);
        this.state={
            appointments: [],
        }
    }
    componentDidMount() {
        axios
            .get('/api/user/' + this.props.user.id + '/appointments')
            .then(json => {
                this.setState({appointments: json.data});
            });
    }
    render() {
        const appointments = this.state.appointments.map(a => (
                <tr key={a.id}>
                    <td>{a.appointment_type.name}</td>
                    <td>{a.clinic.name}</td>
                    <td>{a.doctor.first_name + ' ' + a.doctor.last_name}</td>
                    <td>{a.date}</td>
                    <td>{a.time}</td>
                    <td>{a.appointment_type.duration}</td>
                    <td></td>
                </tr>
            ));
        return (
            <div className='m-4 h-100'>
                <h1>Zakazani pregledi</h1>
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
