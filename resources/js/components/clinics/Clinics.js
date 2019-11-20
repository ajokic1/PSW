import React, { Component } from 'react';
import ClinicList from './ClinicList';
import Sidebar from '../partials/Sidebar';

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
            <div className='row h-100 w-100 mx-0'>
                <div className='h-100 overflow-auto col-lg-3 col-md-4 col-sm-5  bg-light border border-bottom-0 border-top-0 border-left-0'>
                    <Sidebar/>
                </div>                
                <div className='overflow-auto col-lg-9 col-md-8 col-sm-7 bg-white h-100'>
                    <ClinicList clinics={this.state.clinics}/>
                </div>
            </div>
        );
    }
}
