import React from 'react';
import {Route, Router} from 'wouter';
import Home from "../components/Home.js";
import UserProfile from "../components/Page/UserProfile.js";
import YearList from "../components/Page/YearList.js";
import CourseDiscovery from "../components/Page/CourseDiscovery.js";
import HistoryTeacher from "../components/Page/HistoryTeacher.js";

console.log("App component loaded");
function App() {
    return (
        <div className="app">
            <Router>
                <Route path="/react">
                    <Home />
                </Route>
                <Route path="/me">
                    <UserProfile/>
                </Route>
                <Route path="/history/year">
                    <YearList/>
                </Route>
                <Route path="/discover">
                    <CourseDiscovery/>
                </Route>
                <Route path={"/history/teacher/:id"}>
                    {(params)=><HistoryTeacher params={{id: params.id}}></HistoryTeacher>}
                </Route>
            </Router>
        </div>
    );
}

export default App;
