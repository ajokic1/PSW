import React, {Component} from 'react';
import PredefinedAppointmentRow from "./PredefinedAppointmentRow";

class PredefinedAppointments extends Component {
    render() {
        let predefs = null;
        if(this.props.predefs) {
            predefs = this.props.predefs.map(predef => <PredefinedAppointmentRow handleClick={this.props.handleClick}
                                                                                 key={predef.id}
                                                                                 predef={predef}/>);
        }
        return (
            <div>
                <table className="table">
                    <thead className="thead-dark">
                    <tr>
                        <th scope="col">Datum</th>
                        <th scope="col">Vrijeme</th>
                        <th scope="col">Ljekar</th>
                        <th scope="col">Klinika</th>
                        <th scope="col">Tip pregleda</th>
                    </tr>
                    </thead>
                    <tbody>
                    {predefs}
                    </tbody>
                </table>
            </div>
        );
    }
}

export default PredefinedAppointments;
