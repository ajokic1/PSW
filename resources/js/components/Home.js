import React, { Component } from 'react';
import { Link } from 'react-router-dom'

export default class Home extends Component {
    render() {
        return (
            <div className='h-100 m-0 p-5' style={style}>
                {
                //<div className='display-3 mt-5 text-center'>Pregledaj.me</div>
                }
                <div className='d-flex flex-row justify-content-center mt-5'>
                    <Link to='/clinics' style={{ textDecoration: 'none', color: 'black' }}>
                        <div className='card_outline'>
                            <div className='fas fa-hospital mb-5 icon-font'></div>
                            <div className='h4'>Klinike</div>
                        </div>
                    </Link>
                    <Link to='/appointments' style={{ textDecoration: 'none', color: 'black' }}>
                        <div className='card_outline'>
                            <div className='fas fa-history mb-5 icon-font'></div>
                            <div className='h4'>Pregledi</div>
                        </div>
                    </Link>
                    <Link to='/medical_history' style={{ textDecoration: 'none', color: 'black' }}>
                        <div className='card_outline'>
                            <div className='fas fa-file-medical mb-5 icon-font'></div>
                            <div className='h4'>Karton</div>
                        </div>
                    </Link>
                    <Link to='/profile' style={{ textDecoration: 'none', color: 'black' }}>
                        <div className='card_outline'>
                            <div className='fas fa-id-card mb-5 icon-font'></div>
                            <div className='h4'>Profil</div>
                        </div>
                    </Link>
                    
                </div>
            </div>
        );
    }
}
const style = {
    width: '100vw',
    height: '100%',
    backgroundImage: 'url("../images/bg.png")',
    backgroundPosition: 'center',
    backgroundSize: 'cover',
}
