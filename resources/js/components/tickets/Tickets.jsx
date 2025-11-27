import React, { Component, useState } from 'react';
import { createRoot } from 'react-dom/client';
import {
    BrowserRouter as Router,
    Route,
    Routes,
} from "react-router-dom";
import TicketCreate from "./TicketCreate";
import TicketEntry from "./TicketEntry";
import TicketList from "./TicketList";

export default class Tickets extends Component {
    render() {
        return (
            <Router>
                <div>
                    <Routes>
                        <Route path="/tickets/create" element={<TicketCreate />} />
                        <Route path="/tickets/:ticketId" element={<TicketEntry minimal={this.props.minimal} />}/>
                        <Route path="/tickets" element={<TicketList minimal={this.props.minimal} />} />
                    </Routes>
                </div>
            </Router>
        );
    }
}

var tickets = document.getElementsByTagName('react-tickets');

for (var index in tickets) {
    const component = tickets[index];
    if(typeof component === 'object') {
        const props = Object.assign({}, component.dataset);
        const root = createRoot(component);
        root.render(<Tickets {...props} />);
        // ReactDOM.render(<Tickets {...props} />, component);
    }
}

