import React,{ useEffect, useState } from "react";
import { fetchAllYears, fetchSemestersByYear } from "../../services/api.js";

export default function YearList() {
    const [years, setYears] = useState([]);
    const [searchTerm, setSearchTerm] = useState("");
    const [selectedYear, setSelectedYear] = useState(null);
    const [semesters, setSemesters] = useState({});


    useEffect(() => {
        fetchAllYears().then((data) => {
            console.log("Fetched years:", data);
            setYears(data || []);
        });
    }, []);


    const fetchYearSemesters = (yearId) => {
        fetchSemestersByYear(yearId).then((data) => {
            setSemesters((prevState) => ({
                ...prevState,
                [yearId]: data || [],
            }));
        });
    };


    const handleSearchChange = (event) => {
        const term = event.target.value;
        setSearchTerm(term);


        if (term.trim() === "") {
            fetchAllYears().then((data) => setYears(data || []));
        } else {
            const filteredYears = years.filter((year) =>
                year.name.toLowerCase().includes(term.toLowerCase())
            );
            setYears(filteredYears);
        }
    };


    const handleYearClick = (year) => {
        setSelectedYear(year === selectedYear ? null : year);
        if (year !== selectedYear) {
            fetchYearSemesters(year.id);
        } else {
            setSemesters((prevState) => ({
                ...prevState,
                [year.id]: [],
            }));
        }
    };

    return (
        <div className="container bg-dark text-light py-4 rounded">
            <div className="mb-4">
                <input
                    type="text"
                    placeholder="Rechercher une année..."
                    className="form-control"
                    value={searchTerm}
                    onChange={handleSearchChange}
                />
            </div>

            <div className="row g-3">
                {Array.isArray(years) && years.map((year) => (
                    <div key={year.id} className="col-4 p-2">
                        <div
                            className={`card text-center p-3 ${
                                selectedYear === year ? "bg-secondary" : "bg-dark"
                            } text-light border-light`}
                            onClick={() => handleYearClick(year)}
                            style={{ cursor: "pointer" }}
                        >
                            <h5>{year.name}</h5>
                            {selectedYear === year && semesters[year.id] && (
                                <div className="mt-2">
                                    {semesters[year.id].map((semester) => (
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
