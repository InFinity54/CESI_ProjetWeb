"use strict";

var React = require("react");
var ReactDOM = require("react-dom");

const e = React.createElement;

class AgentsDisabledList extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            error: null,
            isLoaded: false,
            agents: []
        };
    }

    componentDidMount() {
        fetch("/api/agents.json")
            .then(res => res.json())
            .then(
                (result) => {
                    this.setState({
                        isLoaded: true,
                        agents: result
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
        $("#agentsdisabledlistloading").remove();

        if (this.state.agents.filter(agent => agent.isActivated).length > 0) {
            return this.state.agents.map((item, index) => {
                if (item.isActivated === false) {
                    const viewLink = Routing.generate('agents_view', { id: item.id }, true);
                    const editLink = Routing.generate('agents_edit', { id: item.id }, true);
                    const enableLink = Routing.generate('agents_enable', { id: item.id }, true);

                    return /*#__PURE__*/React.createElement("tr", { key: index }, /*#__PURE__*/React.createElement("td", {
                        className: "text-center"
                    }, React.createElement("img", {
                        src: "/img/agents/" + item.photo,
                        alt: "",
                        width: "50px",
                        height: "auto",
                        className: "rounded-circle"
                    })), /*#__PURE__*/React.createElement("td", {
                        className: "text-center"
                    }, item.lastname + " " + item.firstname), /*#__PURE__*/React.createElement("td", {
                        className: "text-center"
                    }, item.username), /*#__PURE__*/React.createElement("td", {
                        className: "text-center"
                    }, /*#__PURE__*/React.createElement("span", {
                        className: 'badge badge-danger'
                    }, "Désactivé")), /*#__PURE__*/React.createElement("td", {
                        className: "text-center"
                    }, /*#__PURE__*/React.createElement("a", {
                        href: viewLink,
                        className: "btn btn-primary btn-sm rounded-circle",
                        title: "Voir le d\xE9tail de cet agent"
                    }, /*#__PURE__*/React.createElement("i", {
                        className: "fas fa-eye"
                    })), /*#__PURE__*/React.createElement("a", {
                        href: editLink,
                        className: "btn btn-primary btn-sm rounded-circle",
                        title: "\xC9diter de cet agent"
                    }, /*#__PURE__*/React.createElement("i", {
                        className: "fas fa-edit"
                    })), /*#__PURE__*/React.createElement("a", {
                        href: enableLink,
                        className: "btn btn-danger btn-sm rounded-circle",
                        title: "Activer cet agent"
                    }, /*#__PURE__*/React.createElement("i", {
                        className: "fas fa-times"
                    }))));
                }
            });
        } else {
            return React.createElement("tr", {
                key: 1
            }, React.createElement("td", {
                className: "text-center",
                colSpan: 5
            }, "Aucun agent n'est actuellement actif."));
        }
    }
}

const domContainer = document.querySelector('#agentsdisabledlist');
ReactDOM.render(e(AgentsDisabledList), domContainer);