import React, { Component } from 'react';
import moment from 'moment';

export default class History extends Component {
    constructor(props) {
        super(props);
        this.state = {
            history: [],
            appointments: [],
            diagnoses: [],
            loading:true,
        }
    }
    componentDidMount() {
        this.setState({loading: true});
        let promises = [];
        promises.push(axios
            .get("/api/user/"+this.props.user.id+"/appointments")
            .then(json => {
                this.setState({appointments: json.data});
            }));
        promises.push(axios
            .get("/api/diagnoses")
            .then(json => {
                this.setState({diagnoses: json.data});
            }));
        axios.all(promises).then(() => {
            let history = this.state.appointments.concat(this.state.diagnoses);
            history.sort((a,b)=> a.timestamp>b.timestamp);
            const historyItems = history.map(item => 
                <tr className='hoverLightGray'>
                    <td>{moment.unix(item.timestamp).format('DD.MM.YYYY')}</td>
                    <td>{moment.unix(item.timestamp).format('HH:MM')}</td>
                    <td>{item.condition_id?"Dijagnoza":"Pregled"}</td>
                    <td>{item.condition_id?item.condition.name:item.appointment_type.name}</td>
                    <td>{item.doctor.first_name + " " + item.doctor.last_name}</td>
                </tr>
            );
            this.setState({history: historyItems});
        });
    }
    render() {

        return (
            <div className='m-4 h-100'>
                <h1>Zdravstveni karton</h1>
                <table className="table">
                    <thead>
                        <tr>
                            <th scope="col">Datum</th>
                            <th scope="col">Vrijeme</th>
                            <th scope="col">Tip</th>
                            <th scope="col">Naziv</th>                            
                            <th scope="col">Ljekar</th>
                        </tr>
                    </thead>
                    <tbody className='overflow-auto'>
                        {this.state.history}
                    </tbody>
                </table>
            </div>
        );
    }
}
