import React, { Component } from 'react';
import SiteHeader from "../../components/SiteHeader/index";
import SiteFooter from "../../components/SiteFooter/index";
import '../../styles/main.css';
import { connect } from "react-redux";
class Layout extends Component {
    render() {
        const children = this.props.children;
        let childrenWithProps = React.Children.map(children, child =>
            React.cloneElement(child, {
                newProps: true
            }));
        return (
            <div>
                {/*<SiteHeader/>*/}
                <main className="main">
                    {childrenWithProps}
                </main>
                {/*<SiteFooter/>*/}
            </div>
        )
    }
}
export default connect(null, null)(Layout);