import React, { useEffect, useState } from "react";
import TeacherItem from "../Molecule/TeacherItem.js";
import {fetchRoles, fetchUsersByRole} from "../../services/api.js";

export default function TeacherList() {
    const [teacherData, setTeacherData] = useState([]);
    const [search, setSearch] = useState('');
    const [roles, setRoles] = useState([]);
    const [selectedRole, setSelectedRole] = useState('');
    useEffect(() => {
        fetchRoles().then((data) => {
            setRoles(data['hydra:member'])
        });
        fetchUsersByRole(search, selectedRole).then((data) => {
            setTeacherData(data['hydra:member'])
        });
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
            <div className="d-flex justify-content-center mt-5">
                <div className="d-flex flex-column align-items-center">
                    {teacherData.map((teacher) => (
                        <TeacherItem data={teacher}/>
                    ))}
                </div>
            </div>
        </div>
    );
}