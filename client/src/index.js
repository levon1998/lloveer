import React from 'react';
import ReactDOM from 'react-dom';
import { Provider } from 'react-redux';
import App from './containers/App/index';
import {store, history} from './utils/store'
import registerServiceWorker from './registerServiceWorker';
import { IntlProvider } from "react-intl";

history.listen((location, action) => {
    window.scrollTo(0, 0)
});

ReactDOM.render(
    <Provider store={store}>
        <IntlProvider locale='en'>
            <App history={history}/>
        </IntlProvider>
    </Provider>,
    document.getElementById('root')
);
registerServiceWorker();
