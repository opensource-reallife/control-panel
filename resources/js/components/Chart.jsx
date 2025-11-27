import React, { Component, useState } from 'react';
import { createRoot } from 'react-dom/client';
import axios from 'axios';
import {Line, Doughnut, Bar} from 'react-chartjs-2';
import { Spinner } from 'react-bootstrap';

import {
  Chart as ChartJS,
  ArcElement,
  LineElement,
  BarElement,
  CategoryScale,
  LinearScale,
  PointElement,
  Tooltip,
  Legend,
  Title,
} from 'chart.js';

// Registrierung aller genutzten Elemente
ChartJS.register(
  ArcElement,     // für Doughnut
  LineElement,    // für Line
  BarElement,     // für Bar
  CategoryScale,  // X-Achse
  LinearScale,    // Y-Achse
  PointElement,   // Punkte im LineChart
  Tooltip,
  Legend,
  Title
);

import 'bootstrap-daterangepicker/daterangepicker.css';

export default class Chart extends Component {

    constructor() {
        super();
        this.state = {
            data: null,
            status: null
        };
    }

    async componentDidMount() {

        try {
            let suffix = '';

            if(this.props.date) {
                suffix = '?date=' + this.props.date;
            }

            const response = await axios.get('/api/charts/' + this.props.chart + suffix);

            if(response.data && response.data.tooltipsLabel) {
                if (!response.data.options) {
                    response.data.options = {};
                }
                if (!response.data.options.tooltips) {
                    response.data.options.tooltips = {};
                }
                response.data.options.tooltips.callbacks = {};
                response.data.options.tooltips.callbacks.label = this.handleLabel.bind(this);
            }

            if(response.data && response.data.tooltipsTitle) {
                if (!response.data.options) {
                    response.data.options = {};
                }
                if (!response.data.options.tooltips) {
                    response.data.options.tooltips = {};
                }
                response.data.options.tooltips.callbacks = {};
                response.data.options.tooltips.callbacks.title = this.handleTitle.bind(this);
            }

            this.setState({
                data: response.data,
                status: response.data.status
            });
        } catch (error) {
            this.setState({
                status: 'Access Denied'
            })
        }
    }

    handleLabel(tooltipItem, data) {
        var label = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index]
            ? data.datasets[tooltipItem.datasetIndex].label + ': ' + data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index]
            : '';

        if(this.state.data.tooltipsLabel === 'money') {
            return data.datasets[tooltipItem.datasetIndex].label + ': ' + '$ ' + Number(data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index]).toLocaleString('de-AT');
        }

        return label;
    }

    handleTitle(tooltipItems, data) {
        var label = data.label;

        if(this.state.data.tooltipsTitle === 'datasetLabel') {
            return data.datasets[tooltipItems[0].datasetIndex].label;
        }

        return label;
    }

    async handleDateChange(e) {
        console.log(e);
    }

    render() {
        if(this.state.status) {
            if(this.state.status !== 'Success') {
                let message = <p>Der Zugriff auf die Daten wurde verweigert!</p>;

                if(this.state.status === 'Error') {
                    message = <p>Die Daten konnten nicht geladen werden!</p>;
                }

                return (
                    <div className="card">
                        <div className="card-header">{this.props.title}
                        </div>

                        <div className="card-body">
                            <div className="text-center">
                                {message}
                            </div>
                        </div>
                    </div>
                );
            } else {
                let chart;
                let fromTo;

                if(this.state.data.type === 'doughnut') {
                    chart = <Doughnut  data={this.state.data.data} options={this.state.data.options} />;
                } else if(this.state.data.type === 'bar') {
                    chart = <Bar data={this.state.data.data} options={this.state.data.options} />;
                } else {
                    chart = <Line  data={this.state.data.data} options={this.state.data.options} />;
                }

                if(this.state.data.from && this.state.data.to) {
                    fromTo = <span>{this.state.data.from} - {this.state.data.to}</span>;
                } else if(this.state.data.date) {
                    fromTo = <span>{this.state.data.date}</span>;
                }



                return (
                    <div className="card">
                        <div className="card-header">{this.props.title}
                            <div className="float-right">
                                {fromTo}
                            </div>
                        </div>

                        <div className="card-body"  style={{'height': this.props.height ? this.props.height : '40vh'}}>
                            {chart}
                        </div>
                    </div>
                );
            }
        } else {

            return (
                <div className="card">
                    <div className="card-header">{this.props.title}
                    </div>

                    <div className="card-body">
                        <div className="text-center">
                            <Spinner animation="border" />
                        </div>
                    </div>
                </div>
            );
        }
    }
}

var charts = document.getElementsByTagName('react-chart');
for (var index in charts) {
    const component = charts[index];
    if(typeof component === 'object') {
        const props = Object.assign({}, component.dataset);
        const root = createRoot(component);
        root.render(<Chart {...props} />);
        // ReactDOM.render(<Chart {...props} />, component);
    }
}

