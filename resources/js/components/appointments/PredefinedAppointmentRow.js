import React, {Component} from 'react';

class PredefinedAppointmentRow extends Component {
    render() {
        return (
            <tr className='dark_hover' onClick={() => this.props.handleClick(this.props.predef.id)}>
                <td>{this.props.predef.date}</td>
                <td>{this.props.predef.time}</td>
                <td>{this.props.predef.doctor.first_name + this.props.predef.doctor.last_name}</td>
                <td>{this.props.predef.clinic.name}</td>
                <td>{this.props.predef.appointment_type.name}</td>
            </tr>
        );
    }
}

export default PredefinedAppointmentRow;
