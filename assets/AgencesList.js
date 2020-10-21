"use strict";

var React = require("react");
var ReactDOM = require("react-dom");

const e = React.createElement;

class AgencesList extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            error: null,
            isLoaded: false,
            agences: []
        };
    }

    componentDidMount() {
        fetch("/api/agences.json")
            .then(res => res.json())
            .then(
                (result) => {
                    this.setState({
                        isLoaded: true,
                        agences: result
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
        $("#agenceslistloading").remove();

        if (this.state.agences.length > 0) {
            return this.state.agences.map((item, index) => {
                const viewLink = Routing.generate('agences_view', { id: item.id }, true);
                const editLink = Routing.generate('agences_edit', { id: item.id }, true);

                return /*#__PURE__*/React.createElement("tr", { key: index }, /*#__PURE__*/React.createElement("td", {
                    className: "text-center"
                }, React.createElement("img", {
                    src: "/img/agences/" + item.imageAg,
                    alt: "",
                    width: "150px",
                    height: "auto",
                    className: "rounded-circle"
                })), /*#__PURE__*/React.createElement("td", {
                    className: "text-center"
                }, item.nomAg), /*#__PURE__*/React.createElement("td", {
                    className: "text-center"
                }, /*#__PURE__*/React.createElement("p", null, item.adresseAg), /*#__PURE__*/React.createElement("p", null, item.complementAg), /*#__PURE__*/React.createElement("p", null, item.codepostalAg + " " + item.villeAg)), /*#__PURE__*/React.createElement("td", {
                    className: "text-center"
                }, item.numero), /*#__PURE__*/React.createElement("td", {
                    className: "text-center"
                }, item.faxAg), /*#__PURE__*/React.createElement("td", {
                    className: "text-center"
                }, /*#__PURE__*/React.createElement("a", {
                    href: viewLink,
                    className: "btn btn-primary btn-sm rounded-circle",
                    title: "Voir le d\xE9tail de cette agence"
                }, /*#__PURE__*/React.createElement("i", {
                    className: "fas fa-eye"
                })), /*#__PURE__*/React.createElement("a", {
                    href: editLink,
                    className: "btn btn-primary btn-sm rounded-circle",
                    title: "\xC9diter de cette agence"
                }, /*#__PURE__*/React.createElement("i", {
                    className: "fas fa-edit"
                }))));
            });
        } else {
            return React.createElement("tr", {
                key: 1
            }, React.createElement("td", {
                className: "text-center",
                colSpan: 12
            }, "Aucune agence n'est actuellement enregistrée."));
        }
    }
}

const domContainer = document.querySelector('#agenceslist');
ReactDOM.render(e(AgencesList), domContainer);