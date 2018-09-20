import React from 'react';
import { Router, Switch, Route, Redirect } from 'react-router-dom';
import { connect } from "react-redux";

import Layout from '../Layout';
import HomePage from '../HomePage';

class App extends React.Component {
    wrap(Component, props) {
        return (
            <Layout {...props} >
                <Component {...props}/>
            </Layout>
        );
    }

    render() {
        return (
            <Router history={this.props.history}>
                <Switch>
                    <Route
                        exact
                        path="/"
                        render={props => this.wrap(HomePage, props)}
                    />
                </Switch>
            </Router>
        );
    }
}

const mapDispatchToProps = dispatch => {
    return {
       dispatch
    }
};
export default connect(null, mapDispatchToProps)(App);