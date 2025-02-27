import React, { useEffect, useState } from "react";
import TeacherItem from "../Molecule/TeacherItem.js";
import {fetchRoles, fetchUsersByRole} from "../../services/api.js";
import Loading from "../Atomic/Loading.js";

export default function TeacherList() {
    const [teacherData, setTeacherData] = useState([]);
    const [search, setSearch] = useState('');
    const [roles, setRoles] = useState([]);
    const [selectedRole, setSelectedRole] = useState('');
    const [isLoading, setIsLoading] = useState(false);

    useEffect(() => {
        fetchRoles().then((data) => {
            setRoles(data['hydra:member'])
        });
        setIsLoading(true);
        fetchUsersByRole(search, selectedRole).then((data) => {
            setTeacherData(data['hydra:member'])
        }).finally(() => setIsLoading(false));
    }, [selectedRole, search]);
    return (
        <div>
            <div className="input-group mb-4">
                <select className="form-select bg-dark text-white" value={selectedRole}
                        onChange={(e) => setSelectedRole(e.target.value)}>
                    <option value="">Tous les roles</option>
                    {roles.map((role) => (
                        <option value={role}>{role}</option>
                    ))}
                </select>
                <input
                    type="text"
                    className="form-control bg-dark text-white"
                    placeholder="Rechercher..."
                    onChange={(e) => setSearch(e.target.value)}
                />
                <button className="btn btn-outline-light">
                    Rechercher
                </button>
            </div>
            <div className="container mt-4">
                <div className="row">
                    { isLoading ? (
                        <Loading/>
                    ) : teacherData.length === 0 ? (
                            <h1 className="d-flex justify-content-center">Aucun professeur à consulter</h1>
                        ) : (teacherData.map((teacher) => (
                        <div className="col-12 col-md-3 mb-3">
                            <TeacherItem data={teacher}/>
                        </div>
                    )))}
                </div>
            </div>
        </div>
    );
}