import React, { Component } from 'react';
import { Route, Redirect } from 'react-router-dom';

export default function LoginRoute ({isLoggedIn: isLoggedIn, children: children, ...rest }) {
    //Route accessible only to non-authenticated users
    return (
        <Route {...rest}>
            {isLoggedIn === false
            ? children
            : <Redirect to='/' />}
        </Route>
    );
}