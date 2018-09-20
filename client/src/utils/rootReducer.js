import { combineReducers } from 'redux';
import appReducer from '../containers/App/reducer';
import homePageReducerReducer from '../containers/HomePage/reducer';


export default combineReducers({
    appStore: appReducer,
    prosConsListStore: homePageReducerReducer,
})