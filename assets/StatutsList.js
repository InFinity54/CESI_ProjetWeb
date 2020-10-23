"use strict";

var React = require("react");
var ReactDOM = require("react-dom");

const e = React.createElement;

class StatutsList extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            error: null,
            isLoaded: false,
            statuts: []
        };
    }

    componentDidMount() {
        fetch("/api/statuses.json")
            .then(res => res.json())
            .then(
                (result) => {
                    this.setState({
                        isLoaded: true,
                        statuts: result
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
        $("#statutslistloading").remove();

        if (this.state.statuts.length > 0) {
            return this.state.statuts.map((item, index) => {
                const removeLink = Routing.generate('status_remove', { id: item.id }, true);
                const vehiclesWithStatut = item.vehicles.length;

                return /*#__PURE__*/React.createElement("tr", { key: index }, /*#__PURE__*/React.createElement("td", {
                    className: "text-center"
                }, React.createElement("span", {
                    className: 'badge badge-' + item.color
                }, item.name)), /*#__PURE__*/React.createElement("td", {
                    className: "text-center"
                }, vehiclesWithStatut), /*#__PURE__*/React.createElement("td", {
                    className: "text-center"
                }, /*#__PURE__*/React.createElement("a", {
                    href: removeLink,
                    className: "btn btn-danger btn-sm rounded-circle",
                    title: "Supprimer ce statut"
                }, /*#__PURE__*/React.createElement("i", {
                    className: "fas fa-trash"
                }))));
            });
        } else {
            return React.createElement("tr", {
                key: 1
            }, React.createElement("td", {
                className: "text-center",
                colSpan: 12
            }, "Aucun statut n'est actuellement enregistré."));
        }
    }
}

const domContainer = document.querySelector('#statutslist');
ReactDOM.render(e(StatutsList), domContainer);