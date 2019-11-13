import React, { Component } from 'react';

export default function Message(props) {
    return (
        <div className='card shadow col-sm-9 col-md-7 col-lg-6 mx-auto my-5 px-5'>
            <div className='card-body'>
                <h3>{props.title}</h3>
                <p>{props.message}</p>
            </div>
        </div>
    );
}
