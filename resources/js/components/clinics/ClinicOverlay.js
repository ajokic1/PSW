import React, { Component } from 'react';
import DoctorList from '../doctors/DoctorList';
import Loading from '../partials/Loading';
import DoctorFilters from '../doctors/DoctorFilters';

export default class ClinicOverlay extends Component {
    constructor(props){
        super(props);
        this.state={
            clinic: {},
            filteredDoctors: [],
            doctorNameFilter: '',
            doctorRatingFilter: '',
        }

        this.filterDoctors = this.filterDoctors.bind(this);
    }
    componentDidMount() {
        axios.get('/api/clinics/'+this.props.match.params.clinicId)
            .then(json => {
                this.setState({clinic: json.data, filteredDoctors: json.data.doctors});
            });
    }
    filterDoctors(event) {
        if(event.target.name=='name'){
            this.setState({doctorNameFilter: event.target.value});
            console.log(event.target.value);
            if(event.target.value!=''){
                const filteredDoctors = this.state.clinic.doctors.filter(doctor => 
                    (doctor.first_name + ' ' + doctor.last_name).toLowerCase()
                    .includes(event.target.value.toLowerCase()));
                this.setState({filteredDoctors: filteredDoctors});}
            else this.setState({filteredDoctors: this.state.clinic.doctors});

        }
    }
    render() {
        return (
                <div className='w-75 bg-white mx-auto mt-5 p-5 rounded'>
                    {this.state.clinic 
                        ? <div>
                            <h1>{this.state.clinic.name}</h1>
                            <hr className='mb-4'/>
                            <div className='row mb-4'>                            
                                <div className='col-md-5'>
                                    <img className='img-fluid' src={'/images/'+this.state.clinic.photo}/>
                                </div>
                                <div className='col-md-7'>
                                    <p>{this.state.clinic.description}</p>
                                    <p>{this.state.clinic.address}</p>
                                    <p>{this.state.clinic.city}</p>
                                </div>
                            </div>
                            <h3>Ljekari</h3>
                            <hr className='mb-4'/>
                            <DoctorFilters 
                                handleChange={this.filterDoctors} 
                                name={this.state.doctorNameFilter}
                                rating={this.state.doctorRatingFilter}/>
                            <DoctorList doctors={this.state.filteredDoctors}/>
                        </div>
                        : <Loading/>
                    }

                </div>
        );
    }
}
