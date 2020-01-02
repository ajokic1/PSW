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
            availability: [],
            needsFiltering: false,
            date: null,
            name: "",
            city: "",
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
                this.setState({appointmentTypeId: selectedOption.value, date: null, needsFiltering: true});
            }
            if(target.action=='clear'){
                this.setState((state, props) => ({
                    appointmentTypeId:-1,
                    filteredByAppType:-1, 
                    filteredClinics:state.clinics}))
            }
        }
        else {
            if(target.action=='select-option')
                this.setState({[target.name]: selectedOption.value, needsFiltering: true});
            if(target.action=='clear')
                this.setState({[target.name]: "", needsFiltering: true});
        }

    }
    setDate(date){
        const formattedDate = ((new Date(date)).toISOString().split('T')[0])
        this.setState({date});
        if(this.state.appointmentTypeId != -1){
            axios.get('/api/availability/date/'+formattedDate)
                .then(json =>
                    this.setState({availability: json.data, needsFiltering: true})
                );

        }
    }
    filterClinics() {
        if(this.state.clinics && this.state.needsFiltering){
            const appointmentType = this.state.appointmentTypes
                .find(t => t.id = this.state.appointmentTypeId);

            // Filter definitions
            const filterByAppType = function(clinic) {
                if(this.state.appointmentTypeId!=-1)
                    return clinic.appointment_types.includes(this.state.appointmentTypeId);
                else return true;
            }
            const filterByAvailability = function(clinic) {
                if(this.state.availability.length>0 && this.state.date) {
                    return this.state.availability.some(a => a.clinic_id == clinic.id 
                        && a.duration>appointmentType.duration);
                }
                else return true;
            }
            const filterByName = function(clinic) {
                if(this.state.name) {
                    return clinic.name == this.state.name;
                }
                else return true;
            }
            const filterByCity = function(clinic) {
                if(this.state.city) {
                    return clinic.city == this.state.city;
                }
                else return true
            }

            const filters = [
                filterByAppType.bind(this),
                filterByAvailability.bind(this),
                filterByName.bind(this),
                filterByCity.bind(this),
            ];            
            
            //Filtering
            const filteredClinics = this.state.clinics.filter(clinic =>
                filters.every(filterFunction => filterFunction(clinic))
            );
            this.setState((state, props) => ({
                filteredClinics: filteredClinics,
                needsFiltering: false, 
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
                            setDate={this.setDate}
                            clinics={this.state.clinics}
                            name={this.state.name}
                            city={this.state.city}/>
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
                            handleChange={this.handleSelect}
                            availability={this.state.availability}/>
                    </Overlay>
                </Route>
            </div>
        );
    }
}
