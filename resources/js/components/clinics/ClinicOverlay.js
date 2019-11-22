import React, { Component } from 'react';

export default class ClinicOverlay extends Component {
    constructor(props){
        super(props);
        this.state={
            clinic: {},
        }
    }
    componentDidMount() {
        axios.get('/api/clinics/'+this.props.selectedClinic)
            .then(json => {
                this.setState({clinic: json.data});
            });
    }
    render() {
        return (
            <div className='position-fixed dark-overlay w-100 h-100' onClick={this.props.close}>
                <div className='w-75 h-75 bg-white mx-auto mt-5 p-5 rounded'>
                    {this.state.clinic &&
                        <div>
                            <h1>{this.state.clinic.name}</h1>
                            <hr className='mb-4'/>
                            <div className='row'>                            
                                <div className='col-md-5'>
                                    <img className='img-fluid' src={'/images/'+this.state.clinic.photo}/>
                                </div>
                                <div className='col-md-7'>
                                    <p>{this.state.clinic.description}</p>
                                    <p>{this.state.clinic.address}</p>
                                    <p>{this.state.clinic.city}</p>
                                </div>
                            </div>
                        </div>
                    }

                </div>
            </div>
        );
    }
}
