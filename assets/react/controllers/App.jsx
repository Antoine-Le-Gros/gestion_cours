import React from 'react';
import {Route, Router} from 'wouter';
import Home from "../components/Home.js";
import UserProfile from "../components/Page/UserProfile.js";

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
            </Router>
        </div>
    );
}

export default App;
