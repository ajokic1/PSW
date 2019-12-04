import React, { Component } from 'react';
import moment from 'moment';

export default function AppointmentTime(props) {
    timeString() {
        const start = moment(props.a.time, 'HH:mm:ss');
        const end = start.clone().add(moment.duration(props.a.duration));
        return start.format('H:mm') + '-' + end.format('H:mm');
    }
    return (
        <div className='yeet_box'>
            {timeString()}            
        </div>
    );
}
