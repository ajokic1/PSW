import React, { Component } from 'react';
import { Redirect } from 'react-router-dom'

export default class EditOverlay extends Component {
        constructor(props) {
        super(props);
        this.state={
            first_name: '',
            last_name: '',
            password: '',
            confirm_password: '',
            address: '',
            city: '',
            country: '',
            phone_no: '',
            pogresanPass: false,
            submitting: false,
            done: false,
        }
        this.form = React.createRef();
        this.handleChange=this.handleChange.bind(this);
        this.handleSubmit=this.handleSubmit.bind(this);
    }
    componentDidMount() {
        this.setState({
            first_name: this.props.user.first_name,
            last_name: this.props.user.last_name,
            address: this.props.user.address,
            city: this.props.user.city,
            country: this.props.user.country,
            phone_no: this.props.user.phone_no,
        });
    }
    handleSubmit(event) {
        event.preventDefault();
        if(this.form.current.reportValidity()){
            this.updateUser(event);
        }
    }
    handleChange(event) {
        this.setState({[event.target.name]: event.target.value});
        if(event.target.name==='confirm_password'){
            if(this.state.password !== event.target.value) 
                this.setState({pogresanPass: true});
            else
                this.setState({pogresanPass: false});

        }
    }
    updateUser(event) {        
        let formData = new FormData(); 
        if(this.state.password)
            formData.append("password", this.state.password);
        formData.append("first_name", this.state.first_name);
        formData.append("last_name", this.state.last_name);
        formData.append("address", this.state.address);
        formData.append("city", this.state.city);
        formData.append("country", this.state.country);
        formData.append("phone_no", this.state.phone_no);
        this.setState({submitting: true});

        axios
            .post('/api/user/update', formData)
            .then((json) => {
                if(json.data.success) {
                    this.props.authSuccess(true, json.data.user, false);
                } else this.props.authSuccess(false);
                this.setState({done: true});
            }).catch(error => {
                console.log(error);
                this.props.authSuccess(false);
            });
    }

    render() {
        if(this.state.done)
            return <Redirect to='/profile'/>;
        return (
            <div className='w-75 bg-white mx-auto mt-5 p-5 rounded'>
                <h2 className='d-block text-center my-4'>Izmjena podataka</h2>
                <form ref={this.form} className='row needs-validation' method='post' noValidate>
                    <div className='col-sm-6'>
                        <div className='form-group'>
                            <label htmlFor='first_name'>Ime:</label>
                            <input id='first_name' value={this.state.first_name} onChange={this.handleChange} type='text' className='form-control' name='first_name' required/>
                            <div className="invalid-feedback">
                                Unesite vaše ime.
                            </div>
                        </div>
                        <div className='form-group'>
                            <label htmlFor='last_name'>Prezime:</label>
                            <input id='last_name' value={this.state.last_name} onChange={this.handleChange} type='text' className='form-control' name='last_name' required/>
                            <div className="invalid-feedback">
                                Unesite vaše prezime.
                            </div>
                        </div>
                        <div className='form-group'>
                            <label htmlFor='password'>Password:</label>
                            <input id='password' value={this.state.password} onChange={this.handleChange} type='password' className='form-control' name='password'/>
                            <div className="invalid-feedback">
                                Unesite željeni password.
                            </div>
                        </div>
                        <div className='form-group'>
                            <label htmlFor='confirm_password'>Potvrdite password:</label>
                            <input id='confirm_password' value={this.state.confirm_password} onChange={this.handleChange} type='password' className='form-control' name='confirm_password'/>
                            <div className="invalid-feedback">
                                Ponovite prethodno uneseni password.
                            </div>
                            {(this.state.pogresanPass && this.state.confirm_password) &&
                                <div className="small text-danger">
                                    Ponovite prethodno uneseni password.
                                </div>
                            }
                        </div>
                        
                    </div>
                    <div className='col-sm-6'>
                        <div className='form-group'>
                            <label htmlFor='address'>Ulica i broj:</label>
                            <input id='address' value={this.state.address} onChange={this.handleChange} type='text' className='form-control' name='address' required/>
                            <div className="invalid-feedback">
                                Unesite kućnu adresu.
                            </div>
                        </div>
                        <div className='form-group'>
                            <label htmlFor='city'>Grad:</label>
                            <input id='city' value={this.state.city} onChange={this.handleChange} type='text' className='form-control' name='city' required/>
                            <div className="invalid-feedback">
                                Unesite grad.
                            </div>
                        </div>
                        <div className='form-group'>
                            <label htmlFor='country'>Država:</label>
                            <input id='country' value={this.state.country} onChange={this.handleChange} type='text' className='form-control' name='country' required/>
                            <div className="invalid-feedback">
                                Unesite državu.
                            </div>
                        </div>
                        <div className='form-group'>
                            <label htmlFor='phone_no'>Broj telefona:</label>
                            <input id='phone_no' value={this.state.phone_no} onChange={this.handleChange} type='text' className='form-control' name='phone_no' required/>
                            <div className="invalid-feedback">
                                Unesite broj telefona.
                            </div>
                        </div>   
                        <div className='text-right form-group'>
                        {this.state.submitting 
                            ? "Submitting..."
                            : <input type='submit' onClick={this.handleSubmit} className='btn btn-primary mt-5' value='Sačuvaj izmjene'/>
                        }                          
                        </div>
                    </div>
                </form>
            </div>
        );
    }
}
