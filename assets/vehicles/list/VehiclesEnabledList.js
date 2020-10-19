"use strict";

var React = require("react");
var ReactDOM = require("react-dom");

const e = React.createElement;

class VehiclesEnabledList extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            error: null,
            isLoaded: false,
            vehicles: []
        };
    }

    componentDidMount() {
        fetch("/api/vehicles.json")
            .then(res => res.json())
            .then(
                (result) => {
                    this.setState({
                        isLoaded: true,
                        vehicles: result
                    });
                }, (error) => {
                    this.setState({
                        isLoaded: true,
                        error
                    });
                }
            )
    }

    render() {
        /*return this.state.items.map((item) => {
            const viewLink = Routing.generate('vehicles_view', {id: item.numberplate}, true);
            const editLink = Routing.generate('vehicles_edit', {id: item.numberplate}, true);
            const disableLink = Routing.generate('vehicles_disable', {id: item.numberplate}, true);

            return (
                <tr>
                    <td className='text-center'>{item.photos}</td>
                    <td className='text-center'>{item.numberplate}</td>
                    <td className='text-center'>{item.brand}</td>
                    <td className='text-center'>{item.model}</td>
                    <td className='text-center'>{item.manufactureDate|date('d/m/Y')}</td>
                    <td className='text-center'>{item.height}} m</td>
                    <td className='text-center'>{item.width}} m</td>
                    <td className='text-center'>{item.weight} tonnes</td>
                    <td className='text-center'>{item.power} ch</td>
                    <td className='text-center'>{item.getAgence().getNomAg()}</td>
                    <td className='text-center'><span className={'badge badge-'+item.status.color}>{item.status.name}</span></td>
                    <td className='text-center'>
                        <a href={viewLink} className='btn btn-primary btn-sm rounded-circle' title='Voir le détail de ce véhicule'>
                            <i className='fas fa-eye'></i>
                        </a>
                        <a href={editLink} className='btn btn-primary btn-sm rounded-circle' title='Éditer de ce véhicule'>
                            <i className='fas fa-edit'></i>
                        </a>
                        <a href={disableLink} className='btn btn-danger btn-sm rounded-circle' title='Désactiver ce véhicule'>
                            <i className='fas fa-times'></i>
                        </a>
                    </td>
                </tr>
            );
        });*/
        return this.state.vehicles.map((item, index) => {
            const viewLink = Routing.generate('vehicles_view', { id: item.numberplate }, true);
            const editLink = Routing.generate('vehicles_edit', { id: item.numberplate }, true);
            const disableLink = Routing.generate('vehicles_disable', { id: item.numberplate }, true);
            
            return /*#__PURE__*/React.createElement("tr", null, /*#__PURE__*/React.createElement("td", {
                className: "text-center",
                key: index
            }, item.photos), /*#__PURE__*/React.createElement("td", {
                className: "text-center"
            }, item.numberplate), /*#__PURE__*/React.createElement("td", {
                className: "text-center"
            }, item.brand), /*#__PURE__*/React.createElement("td", {
                className: "text-center"
            }, item.model), /*#__PURE__*/React.createElement("td", {
                className: "text-center"
            }, item.manufactureDate), /*#__PURE__*/React.createElement("td", {
                className: "text-center"
            }, item.height, "} m"), /*#__PURE__*/React.createElement("td", {
                className: "text-center"
            }, item.width, "} m"), /*#__PURE__*/React.createElement("td", {
                className: "text-center"
            }, item.weight, " tonnes"), /*#__PURE__*/React.createElement("td", {
                className: "text-center"
            }, item.power, " ch"), /*#__PURE__*/React.createElement("td", {
                className: "text-center"
            }, item.agence), /*#__PURE__*/React.createElement("td", {
                className: "text-center"
            }, /*#__PURE__*/React.createElement("span", {
                className: 'badge badge-' + item.status.color
            }, item.status.name)), /*#__PURE__*/React.createElement("td", {
                className: "text-center"
            }, /*#__PURE__*/React.createElement("a", {
                href: viewLink,
                className: "btn btn-primary btn-sm rounded-circle",
                title: "Voir le d\xE9tail de ce v\xE9hicule"
            }, /*#__PURE__*/React.createElement("i", {
                className: "fas fa-eye"
            })), /*#__PURE__*/React.createElement("a", {
                href: editLink,
                className: "btn btn-primary btn-sm rounded-circle",
                title: "\xC9diter de ce v\xE9hicule"
            }, /*#__PURE__*/React.createElement("i", {
                className: "fas fa-edit"
            })), /*#__PURE__*/React.createElement("a", {
                href: disableLink,
                className: "btn btn-danger btn-sm rounded-circle",
                title: "D\xE9sactiver ce v\xE9hicule"
            }, /*#__PURE__*/React.createElement("i", {
                className: "fas fa-times"
            }))));
        });
    }
}

const domContainer = document.querySelector('#vehiclesenabledlist');
ReactDOM.render(e(VehiclesEnabledList), domContainer);