import React, { Component } from 'react';
import { connect } from "react-redux";
import * as actionCreators from './actions';
import ListsWrapper from './components/ListsWrapper';

class HomePage extends Component {
    render() {
        const { prosList, consList, focusedIndex } = this.props;
        return (
            <section id="banner">
                <div className="container">

                    <div className="sign-up-form">
                        <a href="index.html" className="logo"><img src="../../styles/logo.png" alt="Friend Finder"/></a>
                        <h2 className="text-white">Find My Friends</h2>
                        <div className="line-divider"/>
                        <div className="form-wrapper">
                            <p className="signup-text">Signup now and meet awesome people around the world</p>
                            <form action="#">
                                <fieldset className="form-group">
                                    <input type="text" className="form-control" id="example-name"
                                           placeholder="Enter name"/>
                                </fieldset>
                                <fieldset className="form-group">
                                    <input type="email" className="form-control" id="example-email"
                                           placeholder="Enter email"/>
                                </fieldset>
                                <fieldset className="form-group">
                                    <input type="password" className="form-control" id="example-password"
                                           placeholder="Enter a password"/>
                                </fieldset>
                            </form>
                            <p>By signning up you agree to the terms</p>
                            <button className="btn-secondary">Signup</button>
                        </div>
                        <a href="#">Already have an account?</a>
                        <img className="form-shadow" src="../../styles/images/bottom-shadow.png" alt=""/>
                    </div>

                    <svg className="arrows hidden-xs hidden-sm">
                        <path className="a1" d="M0 0 L30 32 L60 0"/>
                        <path className="a2" d="M0 20 L30 52 L60 20"/>
                        <path className="a3" d="M0 40 L30 72 L60 40"/>
                    </svg>
                </div>
            </section>
        );
    }
}

const mapStateToProps = state => ({

});

const mapDispatchToProps = dispatch => {
    return {
        dispatch
    }
};
export default connect(mapStateToProps, mapDispatchToProps)(HomePage);