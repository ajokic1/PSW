import React, { Component } from 'react';
import ClinicList from './ClinicList';

export default class Clinics extends Component {
    constructor(props) {
        super(props);
        this.state = {
            clinics: [],
        }
    }
    componentDidMount() {
        axios
            .get('/api/clinics')
            .then(json => {
                this.setState({clinics: json.data});
            });
    }
    render() {
        return (
            <div className='container my-4'>
                <ClinicList clinics={this.state.clinics}/>
            </div>
        );
    }
}
