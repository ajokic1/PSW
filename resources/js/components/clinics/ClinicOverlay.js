import React, { Component } from 'react';
import DoctorList from '../doctors/DoctorList';
import Loading from '../partials/Loading';

export default class ClinicOverlay extends Component {
    constructor(props){
        super(props);
        this.state={
            clinic: {},
        }
    }
    componentDidMount() {
        axios.get('/api/clinics/'+this.props.match.params.clinicId)
            .then(json => {
                this.setState({clinic: json.data});
            });
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
                            <DoctorList doctors={this.state.clinic.doctors}/>
                        </div>
                        : <Loading/>
                    }

                </div>
        );
    }
}
