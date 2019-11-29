import React, { Component } from 'react';
import ClinicList from './ClinicList';
import Sidebar from '../partials/Sidebar';
import ClinicFilters from './ClinicFilters';
import ClinicOverlay from './ClinicOverlay';
import { Route } from 'react-router-dom';
import Overlay from '../partials/Overlay';

export default class Clinics extends Component {
    constructor(props) {
        super(props);
        this.state = {
            clinics: [],
            filteredClinics: [],
            appointmentTypes: [],
            appointmentTypeId: -1,
            date: new Date(),
        }
        this.handleSelect = this.handleSelect.bind(this);
        this.filterClinics = this.filterClinics.bind(this);
        this.setDate = this.setDate.bind(this);
    }
    componentDidMount() {
        axios
            .get('/api/clinics')
            .then(json => {
                this.setState({clinics: json.data, filteredClinics: json.data});
            });
        axios
            .get('/api/appointment_types')
            .then(json => {
                this.setState({appointmentTypes: json.data});
            });
    }
    handleSelect(selectedOption, target){
        if(target.name=='appointmentTypes'){
            if(target.action=='select-option'){
                this.setState({appointmentTypeId: selectedOption.value});
            }
            if(target.action=='clear'){
                this.setState((state, props) => ({
                    appointmentTypeId:-1,
                    filteredByAppType:-1, 
                    filteredClinics:state.clinics}))
            }
        }

    }
    setDate(date){
        this.setState({date});
    }
    filterClinics() {
        if(this.state.clinics 
            && this.state.filteredByAppType != this.state.appointmentTypeId
            && this.state.appointmentTypeId != -1){
            const filteredClinics = this.state.clinics.filter(clinic => 
                clinic.appointment_types.includes(this.state.appointmentTypeId));
            this.setState((state, props) => ({
                filteredClinics: filteredClinics, 
                filteredByAppType: this.state.appointmentTypeId}));
        }        
    }
    render() {
        this.filterClinics();
        return (
            <div className='row h-100 w-100 mx-0'>
                <div className='h-100 overflow-auto col-lg-3 col-md-4 col-sm-5  bg-light border border-bottom-0 border-top-0 border-left-0'>
                    <Sidebar>
                        <ClinicFilters 
                            appointmentTypes={this.state.appointmentTypes} 
                            handleChange={this.handleSelect}
                            date={this.state.date}
                            setDate={this.setDate}/>
                    </Sidebar>
                </div>                
                <div className='overflow-auto col-lg-9 col-md-8 col-sm-7 bg-white h-100'>
                    <ClinicList clinics={this.state.filteredClinics}/>
                </div>
                <Route path='/clinics/:clinicId'>
                    <Overlay>
                        <ClinicOverlay 
                            date={this.state.date} 
                            setDate={this.setDate} 
                            appointmentTypes={this.state.appointmentTypes} 
                            appointmentTypeId={this.state.appointmentTypeId}
                            handleChange={this.handleSelect}/>
                    </Overlay>
                </Route>
            </div>
        );
    }
}
