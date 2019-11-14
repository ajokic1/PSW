import React, { Component } from 'react';

export default class Register extends Component {
    constructor(props) {
        super(props);
        this.state={
            first_name: '',
            last_name: '',
            email: '',
            password: '',
            confirm_password: '',
            address: '',
            city: '',
            country: '',
            phone_no: '',
            insurance_no: '',
            pogresanPass: false,
            registering: false,
        }
        this.form = React.createRef();
        this.handleChange=this.handleChange.bind(this);
        this.handleSubmit=this.handleSubmit.bind(this);
    }
    handleSubmit(event) {
        event.preventDefault();
        if(this.form.current.reportValidity()){
            this.register(event);
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
    register(event) {        
        let formData = new FormData(); 
        formData.append("password", this.state.password);
        formData.append("email", this.state.email);
        formData.append("first_name", this.state.first_name);
        formData.append("last_name", this.state.last_name);
        formData.append("address", this.state.address);
        formData.append("city", this.state.city);
        formData.append("country", this.state.country);
        formData.append("insurance_no", this.state.insurance_no);
        formData.append("phone_no", this.state.phone_no);
        this.setState({registering: true});

        axios
            .post('api/register', formData)
            .then((json) => {
                if(json.data.success) {
                    this.props.authSuccess(false, {}, true);
                } else this.props.authSuccess(false);
            }).catch(error => {
                console.log(error);
                this.props.authSuccess(false);
            });
    }
    render() {
        return (
            <div className='card shadow col-md-12 col-lg-8 mx-auto my-5 px-5'>
                <div className='card-body'>
                    <h2 className='d-block text-center my-4'>Registracija novog korisnika</h2>
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
                                <label htmlFor='email'>E-mail:</label>
                                <input id='email' value={this.state.email} onChange={this.handleChange} type='text' className='form-control' name='email' required/>
                                <div className="invalid-feedback">
                                    Unesite vašu e-mail adresu.
                                </div>
                            </div>
                            <div className='form-group'>
                                <label htmlFor='password'>Password:</label>
                                <input id='password' value={this.state.password} onChange={this.handleChange} type='password' className='form-control' name='password' required/>
                                <div className="invalid-feedback">
                                    Unesite željeni password.
                                </div>
                            </div>
                            <div className='form-group'>
                                <label htmlFor='confirm_password'>Potvrdite password:</label>
                                <input id='confirm_password' value={this.state.confirm_password} onChange={this.handleChange} type='password' className='form-control' name='confirm_password' required/>
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
                            <div className='form-group'>
                                <label htmlFor='insurance_no'>Jedinstveni broj osiguranika (JBO):</label>
                                <input id='insurance_no' value={this.state.insurance_no} onChange={this.handleChange} type='text' className='form-control' name='insurance_no' required/>
                                <div className="invalid-feedback">
                                    Unesite jedinstveni broj osiguranika.
                                </div>
                            </div>    
                            <div className='text-right form-group'>
                            {this.state.registering 
                                ? "Submitting..."
                                : <input type='submit' onClick={this.handleSubmit} className='btn btn-primary mt-5' value='Registracija'/>
                            }                          
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        );
    }
}
