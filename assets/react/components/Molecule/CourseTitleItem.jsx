import React from "react";
import PropTypes from "prop-types";

export default function CourseTitleItem({ data = {} }) {
    const calculateTotals = (courseTitle) => {
        let totalGlobal = 0;
        const totalByType = {};
        courseTitle.courses.forEach((course) => {
            const { typeCourse, hourlyVolumes } = course;
            const courseTotal = hourlyVolumes.reduce((sum, hv) => sum + hv.volume, 0);
            totalGlobal += courseTotal;
            const typeName = typeCourse.name;
            if (totalByType[typeName]) {
                totalByType[typeName] += courseTotal;
            } else {
                totalByType[typeName] = courseTotal;
            }
        });

        return { totalGlobal, totalByType };
    };

    const { totalGlobal, totalByType } = calculateTotals(data);
    return (
        <div className="card bg-dark mb-3 text-white w-75">
            <div className="card-body">
                <h5 className="card-title">{data.name}</h5>
                <div className="d-flex flex-column mb-3">
                <div className="d-flex flex-row mb-3">
                    <span className="flex-grow-1 d-flex flex-column">description : <p className="text-break">{data.description ?? "Aucune"}</p></span>
                    <span className="flex-grow-1">
                        Tags :
                        <ul>{data.tags?.map((tag) => (
                            <li key={tag.id} >{tag.name}</li>
                        )) ?? 'Aucun'}
                        </ul>
                    </span>
                    <span className="flex-grow-1">Volume horaire total : {totalGlobal}h</span>
                    <ul>Groupe(s) : {Object.entries(totalByType).map(([type,total]) => (<li>{type} : {total}h</li>))}</ul>
                </div>
                </div>
            </div>
        </div>
    );
}

CourseTitleItem.propTypes = {
    data: PropTypes.shape({
        name: PropTypes.string.isRequired,
        description: PropTypes.string.isRequired,
        courses: PropTypes.object,
        tags: PropTypes.object,
    }).isRequired
};
