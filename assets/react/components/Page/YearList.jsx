import React, { useEffect, useState } from "react";
import { fetchAllYears } from "../../services/api.js";

export default function YearList() {
    const [years, setYears] = useState([]);
    const [filteredYears, setFilteredYears] = useState([]);
    const [searchTerm, setSearchTerm] = useState("");
    const [selectedYear, setSelectedYear] = useState(null);

    useEffect(() => {
        fetchAllYears().then((data) => {
            setYears(data || []);
            setFilteredYears(data || []);
        });
    }, []);

    useEffect(() => {
        if (searchTerm.trim() === "") {
            setFilteredYears(years);
        } else {
            const filtered = years.filter((year) =>
                year.name.toLowerCase().includes(searchTerm.toLowerCase())
            );
            setFilteredYears(filtered);
        }
    }, [searchTerm, years]);

    const handleYearClick = (year) => {
        setSelectedYear(year === selectedYear ? null : year);
    };

    return (
        <div className="container bg-dark text-light py-4 rounded">
            <div className="input-group mb-4">
                <input
                    type="text"
                    className="form-control bg-dark text-white"
                    placeholder="Rechercher une année..."
                    onChange={(e) => setSearchTerm(e.target.value)}
                />
                <button className="btn btn-outline-light">
                    Rechercher
                </button>
            </div>

            <div className="row g-3">
                {Array.isArray(filteredYears) && filteredYears.map((year) => (
                    <div key={year.id} className="col-4 p-2">
                        <div
                            className={`card text-center p-3 ${
                                selectedYear === year ? "bg-secondary" : "bg-dark"
                            } text-light border-light`}
                            onClick={() => handleYearClick(year)}
                            style={{ cursor: "pointer" }}
                        >
                            <h5>{year.name}</h5>
                            {selectedYear === year && year.semesters && (
                                <div className="mt-2">
                                    {year.semesters.map((semester) => (
                                        <a
                                            key={semester.id}
                                            href={`/history/${year.id}/${semester.id}`}
                                            className="badge bg-light text-dark me-1"
                                            style={{ textDecoration: "none" }}
                                        >
                                            S{semester.number}
                                        </a>
                                    ))}
                                </div>
                            )}
                        </div>
                    </div>
                ))}
            </div>
        </div>
    );
}
