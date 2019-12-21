import React, { Component } from 'react';
import moment from 'moment';
import HistoryOverlay from './HistoryOverlay';

export default class History extends Component {
    constructor(props) {
        super(props);
        this.state = {
            history: [],
            appointments: [],
            diagnoses: [],
            loading:true,
            overlay: false,
            overlayItem: {},
        }
        this.hideOverlay = this.hideOverlay.bind(this);
        this.showOverlay = this.showOverlay.bind(this);
    }
    hideOverlay() {
        this.setState({overlay: false});
    }
    showOverlay(item) {
        this.setState({overlay:true, overlayItem: item})
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
            let history = this.state.appointments.concat(this.state.diagnoses
                .map(diag => {diag.id+=10000; return diag}));
            history.sort((a,b)=> a.timestamp>b.timestamp);
            const historyItems = history.map(item => 
                <tr key={item.id} className='hoverLightGray' onClick={() => this.showOverlay(item)}>
                    <td>{moment.unix(item.timestamp).format('DD.MM.YYYY')}</td>
                    <td>{moment.unix(item.timestamp).format('HH:mm')}</td>
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
            <div>
            {this.state.overlay && 
                <HistoryOverlay 
                    hideOverlay={this.hideOverlay} 
                    item={this.state.overlayItem}/>}
                <div className='m-4 h-100'>
                    <h1 className='mb-3'>Zdravstveni karton</h1>
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
            </div>
        );
    }
}
