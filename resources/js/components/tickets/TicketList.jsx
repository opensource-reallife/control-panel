import React, { Component, useState } from 'react';
import ReactDOM from 'react-dom';
import { Button, Modal, Spinner, Form } from 'react-bootstrap';
import axios from "axios";
import TicketListEntry from "./TicketListEntry";
import {Link} from "react-router-dom";
import {element} from "prop-types";
import Row from "react-bootstrap/Row";
import Col from "react-bootstrap/Col";
import InputGroup from "react-bootstrap/InputGroup";

export default class TicketList extends Component {
    constructor() {
        super();
        this.state = {
            showCreate: false,
            data: null,
            selectedTicket: null,
            state: 'open',
            search: '',
            page: 0,
            loading: false,
        };

        if(Exo.Rank > 0) {
            Echo.private(`tickets`)
                .listen('TicketCreated', this.updateTicket.bind(this))
                .listen('TicketUpdated', this.updateTicket.bind(this));
        } else {
            Echo.private(`tickets.user.${Exo.UserId}`)
                .listen('TicketUpdated', this.updateTicket.bind(this));
        }
    }
/*
    async handleNewTicket(data) {
        console.log(data);
        console.log(this.state.data);
        let newData = this.state.data;

        let index = -1;

        newData.items.forEach((element, i) => {
            if(element.Id === data.ticket.Id) {
                index = i;
            }
        })


        if(index !== -1) {
            this.state.data.items[index] = data.ticket;
            this.setState({
                data: newData
            });
        } else {
            newData.items.push(data.ticket);
            this.setState({
                data: newData
            });
        }
    }
    */

    async updateTicket(data) {
        let newData = this.state.data;

        let index = -1;

        newData.items.forEach((element, i) => {
            if(element.Id === data.ticket.Id) {
                index = i;
            }
        })


        if(index !== -1) {
            this.state.data.items[index] = data.ticket;
            this.setState({
                data: newData
            });
        } else {
            newData.items.unshift(data.ticket);
            this.setState({
                data: newData
            });
        }
    }

    async componentDidMount() {
        if(this.state.data === null) {
            this.loadData(this.state.state);
        }
    }

    async loadData(state) {
        if(this.state.loading)
            return;

        await this.setState({
            page: 1,
            loading: true,
            error: null
        });

        const response = await axios.get('/api/tickets?state=' + state);

        try {
            this.setState({
                data: response.data,
                loading: false
            });
        } catch (error) {
            this.setState({
                loading: false,
                error: 'Ein Fehler ist aufgetreten!'
            });
            console.log(error);
        }
    }

    async changeState(state) {
        this.setState({
            state: state,
            search: ''
        });
        this.loadData(state);
    }

    async onChange(e) {
        if(e.target.type === 'checkbox') {
            this.setState({ [e.target.name]: e.target.checked });
        } else {
            this.setState({ [e.target.name]: e.target.value });
        }
    }

    async search(event) {
        if(event) {
            event.preventDefault();
            event.stopPropagation();
        }

        if(this.state.loading)
            return;

        await this.setState({
            page: 1,
            loading: true,
            error: null
        });

        try {
            const response = await axios.get('/api/tickets?state=' + this.state.state + '&search=' + this.state.search);
            this.setState({
                data: response.data,
                loading: false
            });
        } catch(error) {
            this.setState({
                loading: false,
                error: 'Ein Fehler ist aufgetreten!'
            });
            console.log(error);
        }
    }

    async navigatePage(page) {
        if(this.state.loading)
            return;

        await this.setState({
            page: page,
            loading: true,
            error: null
        });

        try {
            const response = await axios.get('/api/tickets?state=' + this.state.state + '&search=' + this.state.search + '&page=' + this.state.page);
            this.setState({
                data: response.data,
                loading: false
            });
        } catch(error) {
            this.setState({
                loading: false,
                error: 'Ein Fehler ist aufgetreten!'
            });
            console.log(error);
        }
    }

    render() {
        let navigation = <></>;
        let body = <div className="text-center"><Spinner animation="border"/></div>


        if(this.state.data !== null && this.state.loading === false) {
            if(this.state.data.lastPage > 1) {
                navigation = <nav>
                    <ul className="pagination">
                        <li className={this.state.data.currentPage > 1 ? 'page-item' : 'page-item disabled'} aria-disabled={this.state.data.currentPage > 1 ? 'true' : 'false'}>
                            <button className="page-link" onClick={this.navigatePage.bind(this, this.state.data.currentPage - 1)}>« Zurück</button>
                        </li>
                        <li className={this.state.data.currentPage < this.state.data.lastPage ? 'page-item' : 'page-item disabled'} aria-disabled={this.state.data.currentPage < this.state.data.lastPage ? 'true' : 'false'}>
                            <button className="page-link" onClick={this.navigatePage.bind(this, this.state.data.currentPage + 1)}>Weiter »</button>
                        </li>
                    </ul>
                </nav>;
            }

            body = <div className="row">
                <div className="col-md-12">
                    <div className="card">
                        <div className="card-header">
                            Tickets
                        </div>
                        <div className="card-body">
                            <table className="table table-sm">
                                <thead>
                                <tr>
                                    <th className="d-sm-none">
                                        Benutzer/<br />Kategorie
                                    </th>
                                    <th className="d-none d-sm-table-cell">Benutzer</th>
                                    <th className="d-none d-sm-table-cell">Kategorie</th>
                                    <th className="d-none d-md-table-cell">Titel</th>
                                    <th className="d-sm-none">
                                        Teammitglied/<br />Status
                                    </th>
                                    <th className="d-none d-sm-table-cell">Zugw. Teammitglied</th>
                                    <th className="d-none d-sm-table-cell">Status</th>
                                    <th className="d-none d-md-table-cell">Datum</th>
                                    <th className="d-none d-md-table-cell">Antworten</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                {this.state.data.items.map((ticket, i) => {
                                    if(this.state.state === 'both' ||
                                        (this.state.state === 'open' && ticket.State === 'Open') ||
                                        (this.state.state === 'closed' && ticket.State === 'Closed')) {
                                        return <TicketListEntry key={ticket.Id} ticket={ticket} minimal={this.props.minimal} open={this.showEntry}></TicketListEntry>;
                                    }
                                })}
                                </tbody>
                            </table>

                            {navigation}
                        </div>
                    </div>
                </div>
            </div>
        }

        return (
            <>
                <div className="row mb-4">
                    <div className="col-md-12">
                        <div className="btn-group" role="group" aria-label="Basic example">
                            <Button variant="secondary" className={this.state.state === 'open' ? 'active' : ''} onClick={(evt) => this.changeState('open')}>Offen</Button>
                            <Button variant="secondary" className={this.state.state === 'both' ? 'active' : ''} onClick={(evt) => this.changeState('both')}>Offen / Geschlossen</Button>
                            <Button variant="secondary" className={this.state.state === 'closed' ? 'active' : ''} onClick={(evt) => this.changeState('closed')}>Geschlossen</Button>
                        </div>
                        <Link to="/tickets/create" className="btn btn-primary float-right">Ticket erstellen</Link>
                    </div>
                </div>
                <div className="row mb-4">
                    <div className="col-12 col-sm-8 col-md-6 col-lg-5 col-xl-4">
                        <Form onSubmit={this.search.bind(this)}>
                            <InputGroup>
                                <Form.Control placeholder="Suchebegriff (Benutzer/Titel/Kategorie)" name="search" value={this.state.search} onChange={this.onChange.bind(this)} />
                                <InputGroup.Append>
                                    <Button variant="secondary" onClick={this.search.bind(this)}>Suchen</Button>
                                </InputGroup.Append>
                            </InputGroup>
                        </Form>
                    </div>
                </div>
                {body}
            </>
        );
    }
}
