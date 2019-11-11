import React, { Component } from 'react';
import { Route, Redirect } from 'react-router-dom';

export default function PrivateRoute ({isLoggedIn: isLoggedIn, children: children, ...rest }) {
    //Route accessible only to authenticated users
    return (
        <Route {...rest}>
            {isLoggedIn === true
            ? children
            : <Redirect to='/login' />}
        </Route>
    );
}