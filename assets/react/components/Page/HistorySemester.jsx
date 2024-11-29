import React, { useEffect, useState } from 'react';
import PropTypes from 'prop-types';
import Loading from '../Atomic/Loading.js';
import {fetchSemesterAffectations} from "../../services/api.js";

export default function HistorySemester({ params }) {
    const [affectationData, setAffectationData] = useState([]);
    const [isLoading, setIsLoading] = useState(false);
    const [showWeeks, setShowWeeks] = useState(true);

    useEffect(() => {
        setIsLoading(true);
        fetchSemesterAffectations(params.id)
            .then((data) => setAffectationData(data['hydra:member']))
            .finally(() => setIsLoading(false));
    }, []);

    return (
        <>
            <div className="d-flex flex-row  gap-3 m-3">
                <button className="btn btn-primary" onClick={() => setShowWeeks(!showWeeks)}>
                    {showWeeks ? "Cacher les semaines" : "Afficher les semaines"}
                </button>
            </div>
            <table className="table table-dark">
                <thead>
                <tr>
                    <td className="bg-black">Modules</td>
                    <td className="bg-black">Matières</td>
                    <td className="bg-black">Nombre de groupes</td>
                    <td className="bg-black">Type</td>
                    <td className="bg-black">Volume horaire</td>
                    <td className="bg-black">Professeur affecté</td>
                    {showWeeks && affectationData.length > 0 &&
                        affectationData[0].course.courseTitle.modules[0].semester.weeks.map((week) => (
                            <td className="bg-black">{week.number}</td>
                        ))}
                </tr>
            </thead>
            <tbody>
                {isLoading ? (
                    <Loading/>
                ) : affectationData.length === 0 ? (
                    <tr>
                    <td className="d-flex justify-content-center" colSpan="6">Aucune affectation à consulter pour ce semestre et cette
                        année</td></tr>
                ) : (affectationData.map((affectation) => (
                    <tr>
                        <td>
                            {affectation.course.courseTitle.modules
                                .map((module) => module.name).join(", ")}
                        </td>
                        <td>{affectation.course.courseTitle.name}</td>
                        <td>{affectation.numberGroupTaken}</td>
                        <td>{affectation.course.typeCourse.name}</td>
                        <td>{affectation.course.hourlyVolumes.reduce((total, hv) => total + hv.volume, 0)}</td>
                        <td>{String(affectation.teacher.firstname).charAt(0).toUpperCase() + String(affectation.teacher.firstname).slice(1)} {String(affectation.teacher.lastname).toUpperCase()}</td>
                        {showWeeks && affectation.course.courseTitle.modules[0].semester.weeks.map((week) => {
                            const hourlyVolume = affectation.course.hourlyVolumes.find(
                                (hv) => hv.week.number === week.number
                            );
                            return (
                                <td className={!hourlyVolume ? "bg-secondary" : ""}>
                                    {hourlyVolume ? hourlyVolume.volume : ""}
                                </td>
                            );
                        })}
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
