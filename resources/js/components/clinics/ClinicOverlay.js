import React, { Component } from 'react';
import DoctorList from '../doctors/DoctorList';
import Loading from '../partials/Loading';
import DoctorFilters from '../doctors/DoctorFilters';
import StarRatings from './react-star-ratings';

export default class ClinicOverlay extends Component {
    constructor(props){
        super(props);
        this.state={
            clinic: {},
            filteredDoctors: [],
            name: '',
            rating: '',
            needsFiltering: false,
            currentRating: 0,
        }

        this.filterDoctors = this.filterDoctors.bind(this);
        this.handleChange = this.handleChange.bind(this);
        this.handleSelect = this.handleSelect.bind(this);
        this.changeRating = this.changeRating.bind(this);
    }
    componentDidMount() {
        axios.get('/api/clinics/'+this.props.match.params.clinicId)
            .then(json => {
                this.setState({clinic: json.data, filteredDoctors: json.data.doctors});
            });
    }
    handleChange(event) {
        this.setState({[event.target.name]: event.target.value, needsFiltering: true});
    }
    handleSelect(...attributes) {
        this.setState({needsFiltering: true});
        this.props.handleChange(...attributes);

    }
    filterDoctors() {
        if(this.state.clinic.doctors){
            const filteredDoctors = this.state.clinic.doctors.filter(doctor => 
            (this.state.name=='' || 
                (doctor.first_name + ' ' + doctor.last_name).toLowerCase()
                .includes(event.target.value.toLowerCase()))
            && (this.props.appointmentTypeId==-1 || 
                doctor.appointment_types.includes(this.props.appointmentTypeId))
            && (this.state.rating=='' ||
                doctor.rating >= this.state.rating)

        );
        this.setState({filteredDoctors: filteredDoctors, needsFiltering: false});    
        }       
    }
    changeRating( newRating, name ) {
      this.setState({ currentRating: newRating });
      axios.post('/clinics/' + this.state.clinic.id + '/rate', newRating)
        .then(json =>{
            let clinic = this.state.clinic;
            clinic.rating = json.data;
            this.setState({clinic});
        });
    }
    render() {
        if(this.state.needsFiltering) this.filterDoctors();
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
                                    <p>Ocjena: <StarRatings starRatedColor='red' rating={this.state.clinic.rating}/> 
                                        {this.state.clinic.rating}</p>
                                    { this.state.clinic.canRate &&
                                        <p> Ocijeni:
                                            <StarRatings
                                                rating={this.state.currentRating}
                                                starRatedColor='red'
                                                changeRating='this.changeRating'
                                                numberOfStars={5}
                                                name='clinicRating'/>
                                        </p>
                                    }
                                    <p>{this.state.clinic.description}</p>
                                    <p>{this.state.clinic.address}</p>
                                    <p>{this.state.clinic.city}</p>
                                </div>
                            </div>
                            <h3>Ljekari</h3>
                            <hr className='mb-4'/>
                            <DoctorFilters 
                                handleChange={this.handleChange} 
                                name={this.state.name}
                                rating={this.state.rating}
                                date={this.state.date} 
                                setDate={this.setDate}
                                handleSelect={this.handleSelect}
                                appointmentTypes={this.props.appointmentTypes}
                                appointmentTypeId={this.props.appointmentTypeId}/>
                            <DoctorList
                                appointmentTypeId={this.props.appointmentTypeId}
                                date={this.props.date} 
                                clinicId={this.state.clinic.id}
                                doctors={this.state.filteredDoctors} 
                                availability={this.props.availability}/>
                        </div>
                        : <Loading/>
                    }
                    <div style={{width: '1rem', height:'1rem', color: 'white'}}>.</div>

                </div>
        );
    }
}
