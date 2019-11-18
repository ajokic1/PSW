import React, { Component } from 'react';
import {Link} from 'react-router-dom';

export default class Login extends Component {
    constructor(props) {
        super(props);
        this.state={
            email:'',
            password:'',
            loggingIn: false,
        }
        this.form = React.createRef();
        this.handleChange = this.handleChange.bind(this);
        this.handleSubmit = this.handleSubmit.bind(this);
    }
    handleChange(event) {
        this.setState({
            [event.target.name]: event.target.value,
        });
    }
    handleSubmit(event) {
        event.preventDefault();
        if(this.form.current.reportValidity()){
            this.login(event);
        }
    }
    login(event) {
        let formData = new FormData(); 
        formData.append("password", this.state.password);
        formData.append("email", this.state.email);

        this.setState({loggingIn: true});
        axios
            .post('api/login', formData)
            .then((json) => {
                if(json.data.success) {
                    let user = {
                        first_name: json.data.data.first_name,
                        last_name: json.data.data.last_name,
                        id: json.data.data.id,
                        email: json.data.data.email,
                        auth_token: json.data.data.auth_token,
                        role: json.data.data.role,
                    };
                    this.props.authSuccess(true, user);
                } else if(json.data.emailNotVerified) this.props.authSuccess(false, {}, true);
                else this.props.authSuccess(false, {});

            }).catch(error => {
                console.log(error);
                this.props.authSuccess(false, {});
            });
    }
    render() {
        return (
            <div className='card shadow col-sm-9 col-md-7 col-lg-4 mx-auto my-5 px-5'>
                <div className='card-body'>
                    <h2 className='d-block text-center my-4'>Prijava</h2>
                    <form ref={this.form} method='post'>
                        <div className='form-group'>
                            <label>E-mail:</label>
                            <input onChange={this.handleChange} type='text' className='form-control' name='email' required/>
                        </div>
                        <div className='form-group'>
                            <label>Password:</label>
                            <input onChange={this.handleChange} type='password' className='form-control' name='password' required/>
                        </div>
                        <div className='text-center form-group'>
                            <Link to='/register'>Registracija</Link>
                            {this.state.loggingIn 
                                ? "Logging in..."
                                : <input onClick={this.handleSubmit} type='submit' className='ml-4 btn btn-primary' value='Prijava'/>
                            }
                        </div>
                    </form>
                </div>
            </div>
        );
    }
}
