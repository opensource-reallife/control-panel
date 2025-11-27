import React, { Component, useState } from 'react';
import { createRoot } from 'react-dom/client';
import {
    BrowserRouter as Router,
    Routes,
    Route,
} from "react-router-dom";
import TrainingEntry from "./TrainingEntry";
import TrainingList from "./TrainingList";

export default class Trainings extends Component {
    render() {
        return (
            <Router>
                <div>
                    <Routes>
                        <Route path="/trainings/:trainingId" element={<TrainingEntry />} />
                        <Route path="/trainings" element={<TrainingList />} />
                    </Routes>
                </div>
            </Router>
        );
    }
}

var practices = document.getElementsByTagName('react-trainings');

for (var index in practices) {
    const component = practices[index];
    if(typeof component === 'object') {
        const props = Object.assign({}, component.dataset);
        const root = createRoot(component);
        root.render(<Trainings {...props} />);
        // ReactDOM.render(<Trainings {...props} />, component);
    }
}

