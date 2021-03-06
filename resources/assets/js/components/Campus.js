import React, { Component } from 'react';
import ReactTable from "react-table";
import matchSorter from 'match-sorter'
import CampusModal from './CampusModal';
import Modal from 'react-responsive-modal';



export default class Campus extends Component {
    constructor() {
        super();
        this.state = {
            open: false,
            rowValue: []
        }
        this.onOpenModal = this.onOpenModal.bind(this);
        this.onCloseModal = this.onCloseModal.bind(this);
    }

    onOpenModal(row) {   
        this.setState({ 
            open: true,
            rowValue: row,
        });
    };
    
    onCloseModal() {
        this.setState({
           open: false 
        });
    };

    render() {
        const institutions = this.props.institutions;

        const columns = [{
            Header: 'Name',
            id: 'institutionName',
            accessor: 'institutionName', // String-based value accessors!
            filterMethod: (filter, rows) =>
                matchSorter(rows, filter.value, { keys: ['institutionName'] }),
            filterAll: true,
            style: {'whiteSpace': 'unset'}
          }, {
            Header: 'Type',
            id: 'schoolTypeName',
            accessor: 'schoolTypeName',
            filterMethod: (filter, rows) =>
                matchSorter(rows, filter.value, { keys: ['schoolTypeName'] }),
            filterAll: true,
            style: {'whiteSpace': 'unset'}
          }, {
            Header: 'Location',
            id: 'location',
            accessor: 'location',
            filterMethod: (filter, rows) =>
                matchSorter(rows, filter.value, { keys: ['location'] }),
            filterAll: true,
            style: {'whiteSpace': 'unset'}
          }, {
            Header: 'Actions', // Custom header components!
            accessor: 'institutionID',
            Cell: row => (
                <div style={{textAlign: "center"}}>
                    <a onClick={() => this.onOpenModal(row.value)} href="#update">Update</a>
                </div>
            ),
            filterable: false,
            style: {'whiteSpace': 'unset'}
          }]; 
          
        
    
        return (
            <div>
                <ReactTable
                    defaultPageSize={5}
                    filterable
                    defaultFilterMethod={(filter, row) =>
                        String(row[filter.id]) === filter.value}
                    data={institutions}
                    columns={columns}
                    className="-striped -highlight"
                    />
                <Modal open={this.state.open} onClose={this.onCloseModal} center>
                    <CampusModal originalInstitution={this.state.rowValue} open={this.state.open} />
                </Modal>
            </div>
            

        );
    }
}
