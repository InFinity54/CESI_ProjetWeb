"use strict";

var React = require("react");
var ReactDOM = require("react-dom");

const e = React.createElement;

class VehiclesDisabledList extends React.Component {
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
        $("#vehiclesdisabledlistloading").remove();

        if (this.state.vehicles.filter(vehicle => !vehicle.isActivated).length > 0) {
            return this.state.vehicles.map((item, index) => {
                const viewLink = Routing.generate('vehicles_view', { id: item.numberplate }, true);
                const editLink = Routing.generate('vehicles_edit', { id: item.numberplate }, true);
                const enableLink = Routing.generate('vehicles_enable', { id: item.numberplate }, true);
                const vehiclePhotos = (item.photos + '').split(",");
                const vehicleManufactureDate = item.manufacture_date.substring(8, 10) + "/" + item.manufacture_date.substring(5, 7) + "/" + item.manufacture_date.substring(0, 4);

                return /*#__PURE__*/React.createElement("tr", { key: index }, /*#__PURE__*/React.createElement("td", {
                    className: "text-center"
                }, React.createElement("img", {
                    src: "/img/vehicles/" + vehiclePhotos[0],
                    alt: "",
                    width: "150px",
                    height: "auto",
                    className: "rounded-circle"
                })), /*#__PURE__*/React.createElement("td", {
                    className: "text-center"
                }, item.numberplate), /*#__PURE__*/React.createElement("td", {
                    className: "text-center"
                }, item.brand), /*#__PURE__*/React.createElement("td", {
                    className: "text-center"
                }, item.model), /*#__PURE__*/React.createElement("td", {
                    className: "text-center"
                }, vehicleManufactureDate), /*#__PURE__*/React.createElement("td", {
                    className: "text-center"
                }, item.height, " m"), /*#__PURE__*/React.createElement("td", {
                    className: "text-center"
                }, item.width, " m"), /*#__PURE__*/React.createElement("td", {
                    className: "text-center"
                }, item.weight, " tonnes"), /*#__PURE__*/React.createElement("td", {
                    className: "text-center"
                }, item.power, " ch"), /*#__PURE__*/React.createElement("td", {
                    className: "text-center"
                }, item.agence.nom_ag), /*#__PURE__*/React.createElement("td", {
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
                    href: enableLink,
                    className: "btn btn-success btn-sm rounded-circle",
                    title: "Activer ce v\xE9hicule"
                }, /*#__PURE__*/React.createElement("i", {
                    className: "fas fa-check"
                }))));
            });
        } else {
            return React.createElement("tr", {
                key: 1
            }, React.createElement("td", {
                className: "text-center",
                colSpan: 12
            }, "Aucun véhicule n'est actuellement désactivé."));
        }
    }
}

const domContainer = document.querySelector('#vehiclesdisabledlist');
ReactDOM.render(e(VehiclesDisabledList), domContainer);