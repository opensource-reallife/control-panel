import React, { Component } from 'react';
import { createRoot } from 'react-dom/client';
import {BrowserRouter as Router, Route, Routes} from "react-router-dom";
import PollActive from "./PollActive";
import PollList from "./PollList";
import PollEntry from "./PollEntry";

export default class Polls extends Component {
    render() {
        return (
            <Router>
                <div>
                    <Routes>
                        <Route path="/admin/polls/history" element={<PollList />} />
                        <Route path="/admin/polls/:pollId" element={<PollEntry />} />
                        <Route path="/admin/polls" element={<PollActive />} />
                    </Routes>
                </div>
            </Router>
        );
    }
}

var polls = document.getElementsByTagName('react-admin-polls');

for (var index in polls) {
    const component = polls[index];
    if(typeof component === 'object') {
        const props = Object.assign({}, component.dataset);
        const root = createRoot(component);
        root.render(<Polls {...props} />);
        // ReactDOM.render(<Polls {...props} />, component);
    }
}

