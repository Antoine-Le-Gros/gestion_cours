import React, { useEffect, useState } from 'react';
import PropTypes from 'prop-types';
import { fetchUserById, fetchAllYears } from '../../services/api.js';
import Loading from '../Atomic/Loading.js';
import BarStacked from '../Molecule/BarStacked.js';

export default function HistoryTeacher({ params }) {
    const professorId = params.id;
    const [professor, setProfessor] = useState(null);
    const [years, setYears] = useState([]);
    const [selectedYearId, setSelectedYearId] = useState(null);
    const [isLoading, setIsLoading] = useState(false);

    useEffect(() => {
        setIsLoading(true);
        fetchUserById(professorId).then((data) => setProfessor(data)).finally(() => setIsLoading(false));
    }, [professorId]);

    useEffect(() => {
        fetchAllYears().then((data) => setYears(data || []));
    }, []);

    useEffect(() => {
        if (selectedYearId) {
            console.log(`L'année sélectionnée a changé : ${selectedYearId}`);
        }
    }, [selectedYearId]);

    if(isLoading){
        return <Loading/>
    }
    if (!professor || !years.length) {
        return <h1 className="d-flex justify-content-center">Aucun information pour ce prof</h1>;
    }

    return (
        <div className="container py-4 text-center">
            <div className="d-flex justify-content-center mb-3">
                <select
                    id="yearSelect"
                    className="form-select w-auto bg-dark text-white"
                    onChange={(e) => setSelectedYearId(Number(e.target.value))}
                    value={selectedYearId || ''}
                >
                    <option value="" disabled>Sélectionner une année</option>
                    {years.map((year) => (
                        <option key={year.id} value={year.id}>
                            {year.name}
                        </option>
                    ))}
                </select>
            </div>

            <h3 className="text-white">
                {professor.lastname} {professor.firstname}
            </h3>

            {selectedYearId && (
                <div className="mt-4">
                    <div className="d-flex justify-content-center gap-3">
                        {years
                            .find((y) => y.id === selectedYearId)
                            ?.semesters.slice(0, 3)
                            .map((semester) => (
                                <a
                                    key={semester.number}
                                    href="#"
                                    className="btn btn-outline-light"
                                >
                                    S{semester.number}
                                </a>
                            ))}
                    </div>

                    <div className="d-flex justify-content-center gap-3 mt-3">
                        {years
                            .find((y) => y.id === selectedYearId)
                            ?.semesters.slice(3, 6)
                            .map((semester) => (
                                <a
                                    key={semester.number}
                                    href="#"
                                    className="btn btn-outline-light"
                                >
                                    S{semester.number}
                                </a>
                            ))}
                    </div>
                </div>
            )}

            {selectedYearId && (
                <div className="mt-4">
                    <BarStacked userId={professorId} yearId={selectedYearId} />
                </div>
            )}
        </div>
    );
}

HistoryTeacher.propTypes = {
    params: PropTypes.shape({
        id: PropTypes.string.isRequired,
    }).isRequired,
};
