import React, { useEffect, useState } from 'react';
import PropTypes from 'prop-types';
import Loading from '../Atomic/Loading.js';
import {fetchSemesterAffectations} from "../../services/api.js";

export default function HistorySemester({ params }) {
    const [affectationData, setAffectationData] = useState([]);
    const [semester, setSemester] = useState(params.id);
    const [isLoading, setIsLoading] = useState(false);
    useEffect(() => {
        setIsLoading(true);
        fetchSemesterAffectations(semester).then((data) => setAffectationData(data)).finally(() => setIsLoading(false));
    }, [semester]);

    return (
        <>
        <div className="d-flex flex-row justify-content-center gap-3">
            <button
                className="btn btn-dark"
                onClick={() => setSemester(semester - 1)}
                disabled={semester === 1}>
                <i className="bi bi-0-circle">Précédent</i>
            </button>
            <p>S{semester}</p>
            <button
                className="btn btn-dark"
                onClick={() => setSemester(semester + 1)}
                disabled={semester === 6}>
                <i className="bi bi-arrow-right">Suivant</i></button>
        </div>
        <table className="table">
            <thead>
                <tr>
                    <td>Modules</td>
                    <td>Matières</td>
                    <td>Nombre de groupes</td>
                    <td>Type</td>
                    <td>Volume horaire</td>
                    <td>Professeur affecté</td>
                </tr>
            </thead>
            <tbody>
                {isLoading ? (
                    <Loading/>
                ) : affectationData.length === 0 ? (
                    <h1 className="d-flex justify-content-center">Aucune affectation à consulter pour ce semestre et cette
                        année</h1>
                ) : (affectationData.map((affectation) => (
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    )))}
            </tbody>
        </table>
        </>
    );
}

HistorySemester.propTypes = {
    params: PropTypes.shape({
        id: PropTypes.string.isRequired,
    }).isRequired,
};
