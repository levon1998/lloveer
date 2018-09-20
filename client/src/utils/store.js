import createHistory from 'history/createBrowserHistory';
import createSagaMiddleware from 'redux-saga';
import {createStore, applyMiddleware, compose } from "redux";
import rootReducer from '../utils/rootReducer';
import RootSaga from "../utils/rootSaga";

const initialState = {};

const sagaMiddleware = createSagaMiddleware();
export const history = createHistory();

const composeEnhancers = window.__REDUX_DEVTOOLS_EXTENSION_COMPOSE__ || compose;
const enhancer = composeEnhancers(
    applyMiddleware(sagaMiddleware),
    // other store enhancers if any
);
export const store = createStore(rootReducer, initialState, enhancer);
sagaMiddleware.run(RootSaga);
